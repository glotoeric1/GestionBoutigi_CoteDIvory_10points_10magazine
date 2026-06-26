<?php

namespace App\Http\Controllers;

use App\Models\Boutique;
use App\Models\Categorie;
use App\Models\EntreSortieStock;
use App\Models\Fournisseur;
use App\Models\HistoryTransfer;
use App\Models\Produit;
use App\Models\settings;
use App\Models\Supply;
use App\Models\Type;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Entrepot;
use App\Models\Stock;

use function PHPSTORM_META\type;

class ProduitController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $totalQ = 0;
        $qte = 0;
        $totalA = 0;
        $totalAu = 0;
        $totalG = 0;
        $sommeTotalA = 0;
        $qte_alert = settings::first()->qte_alert;
        $complets = Produit::where('id_setting', auth()->user()->id_setting);
        $categories = Categorie::latest()->get();
        $produits = Stock::latest()->get();
        $datas = $complets->limit(100000)->get();

        if (count($complets->get()) > 0) {
            foreach ($complets->get() as $val) {
                $qte = $val->quantite;
                $sommeTotalA = $val->quantite * $val->prix_achat;
                $sommetotalAu = $val->quantite * $val->prix_vente_unitaire;
                $sommetotalG = $val->quantite * $val->prix_vente_en_gros;

                $totalA += $sommeTotalA;
                $totalAu += $sommetotalAu;
                $totalG += $sommetotalG;
                $totalQ += $qte;
            }
        }

        return view("produit.index", compact("datas", "qte_alert", "totalAu", "totalA", "totalG", "totalQ", "produits", "categories"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $cats = Categorie::where('id_setting', auth()->user()->id_setting)->get();
        $types = Type::where('id_setting', auth()->user()->id_setting)->get();
        $fours = Fournisseur::where('id_setting', auth()->user()->id_setting)->get();
        $pros = Supply::where('statut', 'Valider')->where('id_setting', auth()->user()->id_setting)->get();

        return view("produit.create", compact("cats", "types", "fours", "pros"));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //dd($request->all());
        $request->validate([
            "nom_produit" => ["required"],
            "id_categorie" => ["required"],
            "id_type" => ["required"],
            "quantite" => ["required"],
            "prix_achat" => ["required"],
            "Total_achat" => ["required"],
            "Total_en_gros" => ["required"],
            "Total_benefice_en_gros" => ["required"],
            "Total_en_detail" => ["required"],
            "Total_benefice_en_detail" => ["required"],
            "options_barcode" => ["required"],
            "qte_par_carton" => ["required"],
            "qte_total_en_detail" => ["required"],
            "prix_vente_en_gros" => ["required"],
            "prix_vente_unitaire" => ["required"],

        ]);
        $obj = new Produit();

        $datas = $request->all();
        $datas['username'] = auth()->user()->id;
        $datas['id_setting'] = auth()->user()->id_setting;
        $datas['numero_comm'] = $request->numero_comm;
        $datas['date_expiration'] = $request->date_expiration;
        $this->QteOperation($request->numero_comm, $request->quantite, "ADD");

        $data = $obj->StoreProduit($datas);
        if ($data) {
            $dts['produit'] = $request->nom_produit;
            $dts['user_name'] = auth()->user()->name;
            $dts['id_setting'] = auth()->user()->id_setting;
            $dts['qte'] = $request->quantite;
            $dts['num_charge'] = $request->numero_comm;
            $dts['operation'] = "Sortir";
            (new EntreSortieStock)->StoreEntreSortieStock($dts);

            return back()->with("succes", "Enregistrement effectué avec succès.");
        }
        return back()->with("error", "Enregistrement non effectué!");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = Produit::find($id);
        $boutiques = Boutique::where('id_setting', auth()->user()->id_setting)->latest()->get();
        $magasins = Entrepot::where('id_setting',  auth()->user()->id_setting)->get();
        return view("produit.show", compact("data", "boutiques", "magasins"));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $datas = Produit::find($id);
        $cats = Categorie::where('id_setting', auth()->user()->id_setting)->get();
        $types = Type::where('id_setting', auth()->user()->id_setting)->get();
        $fours = Fournisseur::where('id_setting', auth()->user()->id_setting)->get();
        return view("produit.edit", compact("datas", "cats", "types", "fours"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //dd($request->all());
        $request->validate([
            "nom_produit" => ["required"],
            "quantite" => ["required"],
            "prix_achat" => ["required"],
            "Total_achat" => ["required"],
            "Total_en_gros" => ["required"],
            "Total_benefice_en_gros" => ["required"],
            "Total_en_detail" => ["required"],
            "Total_benefice_en_detail" => ["required"],
            "options_barcode" => ["required"],
            "qte_par_carton" => ["required"],
            "qte_total_en_detail" => ["required"],
            "prix_vente_en_gros" => ["required"],
            "prix_vente_unitaire" => ["required"],

        ]);
        $obj = new Produit();
        $data = $request->all();
        $data['id_setting'] = auth()->user()->id_setting;
        $datas = $obj->updateProduit($id, $data);

        if ($datas) {
            return redirect()->route("produit.index")->with("succes", "Mise à jour effectuée avec succès.");
        }
        return back()->with("error", "Mise à jour non effectuée!");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $obj = new Produit();
        $dt = Produit::find($id);
        $this->QteOperation($dt->numero_comm, $dt->quantite, "REMOVE");
        $data = $obj->deleteProduit($id);
        if ($data) {
            return back()->with("succes", "Supression reussie");
        }
        return back()->with("error", "Supression non reussie");
    }

    public function getCat($id)
    {
        $data = Type::where('categorie', $id)->get();
        return response()->json($data);
    }

    public function AddQte(Request $request)
    {
        // dd($request->qte);
        $request->validate([
            "qte" => ["required", "numeric", "min:1"]
        ]);

        $obj = new Produit();
        $dt = Produit::find($request->id);
        $qte = $dt->quantite;

        // dd($request->qte, $qte);

        $prix_achat = $dt->prix_achat;
        $total_qte = $qte + $request->qte;
        $Total_achat = $prix_achat * $total_qte;
        $prix_vente_en_gros = $dt->prix_vente_en_gros;
        $prix_vente_unitaire = $dt->prix_vente_unitaire;

        $Total_en_detail = $prix_vente_unitaire * $total_qte;
        $Total_en_gros = $prix_vente_en_gros * $total_qte;

        $beneficeGro = ($prix_vente_en_gros - $prix_achat) * $total_qte;
        $beneficeDetail = ($prix_vente_unitaire - $prix_achat) * $total_qte;

        $Total_benefice_en_detail = $beneficeDetail;
        $Total_benefice_en_gros = $beneficeGro;


        $datas['Total_achat'] = $Total_achat;
        $datas['Total_en_detail'] = $Total_en_detail;
        $datas['Total_en_gros'] = $Total_en_gros;
        $datas['Total_benefice_en_detail'] = $Total_benefice_en_detail;
        $datas['Total_benefice_en_gros'] = $Total_benefice_en_gros;


        //need to add some data about 
        $datas['quantite'] = $qte + $request->qte;
        $data = $obj->updateProduit($request->id, $datas);

        if ($data) {
            return redirect()->route("produit.index")->with("succes", "Mise à jour effectuée avec succès.");
        }
        return back()->with("error", "Mise à jour non effectuée!");
    }

    public function QteOperation($numero_comm, $qte, $operation)
    {
        $quantite = 0;
        $data = Supply::where('numero_commande', $numero_comm)->first();
        if (!empty($data)) {
            if (empty($data->qte_valider)) {
                $quantite = 0;
            }
            $quantite = $data->qte_valider;

            if ($operation == "ADD" || $operation == "EDIT") {

                $error = "Vous avez " . $data->qte_valider . " quantité(s) en stock.";
                if ($qte <= $data->qte_valider) {
                    $qteStock = ($quantite - $qte);
                    $data->qte_valider = $qteStock;
                    $data->update();
                } else {
                    return back()->with("error", $error);
                }
            } elseif ($operation == "REMOVE") {
                if ($qte > 0) {
                    $qteStock = ($quantite + $qte);
                    $data->qte_valider = $qteStock;
                    $data->update();
                }
            }
        }
        return;
    }

    function AddBarCode(Request $request)
    {
        $request->validate([
            'codebar' => ['required'],
        ]);

        $data = Produit::where("id", $request->id)
            ->where('id_setting', auth()->user()->id_setting)
            ->update([
                "code_barre" => $request->codebar,
                "options_barcode" => "OUI"
            ]);

        if ($data) {
            return back()->with("succes", "Mise à jour effectuée avec succès.");
        }
        return back()->with("error", "Mise à jour non effectuée");
    }

    public function historyTransfert()
    {
        $datas = HistoryTransfer::where('id_setting', auth()->user()->id_setting)
            ->latest()
            ->limit(200)
            ->get();

        return view('produit.historyTransfert', compact('datas'));
    }

    public function searchProduit(Request $request)
    {
        $totalQ = 0;
        $qte = 0;
        $totalA = 0;
        $totalAu = 0;
        $totalG = 0;
        $sommeTotalA = 0;
        $qte_alert = settings::first()->qte_alert;
        $complets = Produit::where('id_setting', auth()->user()->id_setting);
        $categories = Categorie::latest()->get();
        $produits = Stock::latest()->get();
        $datas = $complets->limit(100000)->get();
        if ($request->id_categorie && $request->produit) {
            $complets = Produit::where('id_setting', auth()->user()->id_setting)
                ->where('id_categorie', $request->id_categorie)
                ->where('id_prod', $request->produit);
            $datas = $complets->limit(100000)->get();
        } elseif ($request->id_categorie) {
            $complets = Produit::where('id_setting', auth()->user()->id_setting)
                ->where('id_categorie', $request->id_categorie);
            $datas = $complets->limit(100000)->get();
        } elseif ($request->produit) {
            $complets = Produit::where('id_setting', auth()->user()->id_setting)
                ->where('id_prod', $request->produit);
            $datas = $complets->limit(100000)->get();
        }

        if (count($complets->get()) > 0) {
            foreach ($complets->get() as $val) {
                $qte = $val->quantite;
                $sommeTotalA = $val->quantite * $val->prix_achat;
                $sommetotalAu = $val->quantite * $val->prix_vente_unitaire;
                $sommetotalG = $val->quantite * $val->prix_vente_en_gros;

                $totalA += $sommeTotalA;
                $totalAu += $sommetotalAu;
                $totalG += $sommetotalG;
                $totalQ += $qte;
            }
        }

        return view("produit.index", compact("datas", "qte_alert", "totalAu", "totalA", "totalG", "totalQ", "produits", "categories"));
    }
}
