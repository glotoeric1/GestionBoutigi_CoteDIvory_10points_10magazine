<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\Categorie;
use App\Models\Entrepot;
use App\Models\EntreSortieStock;
use App\Models\Fournisseur;
use App\Models\Produit;
use App\Models\Stock;
use App\Models\Supply;
use App\Models\Type;
use Carbon\Carbon;
use App\Models\PaiementCmd;
use App\Models\Client;
use App\Models\SupplieDetail;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SupplyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $obj = new Supply();
        $total = 0;
        $importer = 0;
        $exporter = 0;
        $importerCounter = 0;
        $exporterCounter = 0;
        $qte_commander = 0;
        $qte_valider = 0;
        //-----------------------------
        $datas = Supply::whereMonth('created_at', Carbon::now()->month)
            ->where('id_setting', auth()->user()->id_setting)
            ->latest()->simplePaginate(10);
        $detailCmds = SupplieDetail::whereMonth('created_at', Carbon::now()->month)->get();
        $entrepots = Entrepot::where("id_setting", auth()->user()->id_setting)->get();

        if (count($detailCmds) > 0) {
            foreach ($detailCmds as $item) {
                $qte_commander += $item->qte_commander;
                $qte_valider += $item->qte_valider;
                $importerCounter = $item->qte_valider != '' ? $item->qte_valider * $item->prix : $item->qte_commander * $item->prix;
                $importer += $importerCounter;
                // $exporterCounter = $item->qte_valider * $item->prix;
                // $exporter += $exporterCounter;
                $total += $item->montant;
            }
            
        }
        return view("supply.index", compact("datas", "qte_commander", "qte_valider", "exporter", "importer", "total", "entrepots"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $fours = Fournisseur::where('id_setting', auth()->user()->id_setting)->latest()->get();
        $pros = Stock::distinct()->where('id_setting', auth()->user()->id_setting)->latest()->orderBy('libelle', 'asc')->get();
        $cats = Categorie::where('id_setting', auth()->user()->id_setting)->get();
        $types = Type::where('id_setting', auth()->user()->id_setting)->get();
        $clients = Client::where('id_setting', auth()->user()->id_setting)->get();
        return view("supply.create", compact("fours", "pros", "cats", "types", "clients"));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $datas = $request->validate([
            "total_ht" => ["required"],
            "montantDonner" => ["required"],
            "restant" => ["required"],
            "dates" => ["required"],
            "id_fournisseur" => ["required"]
        ]);
        // $datas['id_prod'] = $obj->generateUniqueProductId();
        // foreach (session()->get('commande') as $cart) {
        //     $datas['prix'] = $cart['prix'];
        //     $datas['qte_commander'] = $cart['qte'];
        //     $datas['montant'] = $cart['total'];
        //     $datas['id_cat'] = $cart['categorie'];
        //     $datas['id_type'] = $cart['id_type'];
        //     $datas['username'] = $cart['username'];
        //     $datas['prix_detail'] = $cart['prix_detail'];
        //     $datas['total_detail'] = $cart['total_detail'];
        //     $datas['prix_gros'] = $cart['prix_gros'];
        //     $datas['total_gros'] = $cart['total_gros'];
        // }

        $objAmount = new CurrencyConverterController();
        $amountInWords = 0;
        $datas['id_setting'] = auth()->user()->id_setting;
        $dts['id_setting'] = auth()->user()->id_setting;

        if (!$request->session()->has('commande') && !filled($request->session()->get('commande'))) {
            return back()->with("error", "Whoops : Votre panier est vide");
        }

        $datas['cart'] = serialize(session()->get('commande'));

        $obj = new Supply();

        $cmd = [
            "numero_commande" => $obj->generateNumCMD('CMD'),
            "operation" => "Importer",
            "total_ht" => $datas['total_ht'],
            "id_fournisseur" => $datas['id_fournisseur'],
            "dates" => $datas['dates'],
            "restant" => $datas['restant'],
            "id_user" => auth()->user()->id,
            "fraisTransit" => $request->fraisLogistique ? $request->fraisLogistique : '0',
            "fraisLogistique" => $request->fraisTransit ? $request->fraisTransit : '0',
            "montantDonner" => $datas['montantDonner'],
            "id_setting" => $datas['id_setting'],
        ];

        $desc = "Rapport de Commande";
        $operation = "COMMANDE";

        try {
            DB::beginTransaction();

            $data = $obj->StoreSupply($cmd);

            if ($data) {
                foreach (session()->get('commande') as $cart) {
                    SupplieDetail::create([
                        "supplie_id" => $data->id,
                        "id_prod" => $cart['id'],
                        "id_cat" => $cart['categorie'],
                        "qte_commander" => $cart['qte'],
                        "prix" => $cart['prix'],
                        "montant" => $cart['qte'] * $cart['prix'],
                        "prix_detail" => $cart['prix_detail'],
                        "total_detail" => $cart['total_detail'],
                        "prix_gros" => $cart['prix_gros'],
                        "total_gros" => $cart['total_gros'],
                    ]);
                }
                if ($request->montantDonner > 0) {
                    PaiementCmd::create([
                        "id_commande" => $data->id,
                        "numero_commande" => $cmd['numero_commande'],
                        "montant" => $request->montantDonner,
                        'date_paiement' => $datas['dates'],
                        'commentaire' => 'Paiement d\'un montant de ' . $request->montantDonner . ' lors de la commande',
                    ]);
                }
                if ($request->valider == "print") {
                    $amountInWords = $objAmount->convertAmountToWords($request->total_ht);
                    $frs = Fournisseur::find($cmd['id_fournisseur']);
                    $datass = [
                        'fournisseur' => $frs->nom_fournisseur,
                        'numero_commande' => $cmd['numero_commande'],
                        'carts' => $datas['cart'],
                        'total_ht' => $request->total_ht,
                        'fraisTransit' => $cmd["fraisTransit"],
                        'fraisLogistique' => $cmd["fraisLogistique"],
                        'montantDonner' => $cmd['montantDonner'],
                        'restant' => $cmd['restant'],
                        'date_hr' => $request->dates,
                        "username" => $cmd["id_user"],
                        "operation" => $operation,
                        "desc" => $desc,
                        "amountInWords" => $amountInWords,
                    ];
                    $pdf = PDF::loadView('pdf.commande', $datass);
                    session()->flash('succes', 'Votre commande à été effectué');
                    DB::commit();
                    return $pdf->download("Facture_commande" . $cmd['numero_commande'] . ".pdf");
                }
                session()->forget('commande');
                session('commande', []);
                DB::commit();
                return back()->with("succes", "Enregistrement effectué avec succès.");
            }
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with(
                'error',
                "Une erreur est survenue. Veuillez réessayer.".$e
            );
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = Supply::find($id);
        $detailCmds = SupplieDetail::where('supplie_id', $id)->get();
        $entrepots = Entrepot::where("id_setting", auth()->user()->id_setting)->get();
        $paiementCmds = PaiementCmd::where('id_commande', $id)->get();
        return view("supply.show", compact("data", "detailCmds", "paiementCmds"));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = Supply::find($id);
        $fours = Fournisseur::where('id_setting', auth()->user()->id_setting)->latest()->get();
        $entrepots = Entrepot::where('id_setting', auth()->user()->id_setting)->latest()->get();
        return view("supply.edit", compact("data", "fours", "entrepots"));
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
        if ($request->btn == "UPDATES") {
            $request->validate([
                "montant" => 'required',
                "date_paiement" => 'required'
            ]);
            $supply = Supply::find($id);

            $cmd_paiement = [
                "id_commande" => $id,
                "numero_commande" => $request->numero_commande,
                "montant" => $request->montant,
                'date_paiement' => $request->date_paiement,
                'commentaire' => $request->commentaire,
            ];

            if ($request->montant_restant >= $request->montant) {
                $datas['montantDonner'] = $supply->montantDonner + $request->montant;
                $datas['restant'] = $supply->restant - $request->montant;
                $data = $supply->update($datas);
                if ($data) {
                    PaiementCmd::create($cmd_paiement);
                    return back()->with("succes", "Le paiement est effectué avec succès.");
                } else {
                    return back()->with("error", "Paiement non effectué.");
                }
            } else {
                return back()->with('error', "Le montant que vous avez saisie est supérieur au montant restant");
            }
        }

        $datas = $request->validate([
            "numero" => ['required'],
            "libelle" => ['required'],
            "qte_commander" => ['required'],
            "prix" => ['required'],
            "operation" => ['required'],
            "total" => ['required'],
            "done_by" => ['required'],
            "dates" => ['required'],
        ]);
        $datas['id_user'] = auth()->user()->name;
        $datas['descs'] = $request->descs;
        $obj = new Supply();
        $data = $obj->updateSupply($id, $datas);
        if ($data) {
            return redirect()->route("caisses.index")->with("succes", "Mise à jour effectué avec succès.");
        }
        return back()->with("error", "Mise à jour non effectué.");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $obj = new Supply();
        $data = $obj->deleteSupply($id);
        if ($data) {
            return back()->with("succes", "Supression reussie");
        }
        return back()->with("error", "Supression non reussie");
    }

    public function showDetail($id)
    {
        $data = Supply::find($id);
        $type = "detail";
        $detailCmds = SupplieDetail::where('supplie_id', $id)->get();
        $paiementCmds = PaiementCmd::where('id_commande', $id)->get();
        return view("supply.show", compact("data", "type", "detailCmds", "paiementCmds"));
    }

    public function updateDetail(Request $request, $id)
    {

        $datas = $request->validate([
            "qte_commander" => "required",
            "prix" => "required",
            "prix_detail" => "required",
            "prix_gros" => "required"
        ]);

        $detail_cmd = SupplieDetail::find($id);
        if ($detail_cmd) {
            $datas['total_detail'] = $datas['prix_detail'] * $datas['qte_commander'];
            $datas['total_gros'] = $datas['prix_gros'] * $datas['qte_commander'];
            $datas['montant'] = $datas['qte_commander'] * $datas['prix'];
            $detail_cmd->update($datas);
            return back()->with('succes', 'La ligne de commande a été mis en jour avec succès');
        } else {
            return back()->with('info', "L'information est introuvable");
        }
    }

    public function detailDelete($id)
    {
        $supplie_detail = SupplieDetail::find($id);
        if ($supplie_detail) {
            $supplie_detail->delete();
            return back()->with('succes', "La ligne de commande a été retirer avec succès");
        } else {
            return back()->with('info', "L'information est introuvable");
        }
    }

    public function paiementDelete($id)
    {
        $supplie_paiement = PaiementCMD::find($id);
        if ($supplie_paiement) {
            $supply = Supply::find($supplie_paiement->id_commande);
            $supply->montantDonner = $supply->montantDonner - $supplie_paiement->montant;
            $supply->restant = $supply->restant + $supplie_paiement->montant;
            $supply->save();

            $supplie_paiement->delete();
            return back()->with('succes', "La ligne de paiement a été annuler avec succès");
        } else {
            return back()->with('info', "L'information est introuvable");
        }
    }

    public function Recharche(Request $request)
    {
        if (!empty($request->dateDebut) && !empty($request->dateFin)) {
            $datas = Supply::whereDate('created_at', '>=', $request->dateDebut)
                ->whereDate('created_at', '<=', $request->dateFin)
                ->where('id_setting', auth()->user()->id_setting)
                ->simplePaginate(15);
            //->get();
            $depenseT = $datas;

            $depenseM = Supply::whereDate('created_at', '>=', $request->dateDebut)
                ->whereDate('created_at', '<=', $request->dateFin)
                ->where('id_setting', auth()->user()->id_setting)
                ->sum("montant");

            return view("supply.index", compact("datas", "depenseT", "depenseM"));
        }
        return redirect()->route("supply.index");
    }

    public function RechercheSupply(Request $request)
    {
        $operation = $request->types;
        if ($operation == "CAISSE") {
            $total = 0;
            $importer = 0;
            $exporter = 0;
            $importerCounter = 0;
            $exporterCounter = 0;
            $qte_commander = 0;
            $qte_valider = 0;
            $datas = 0;

            $desc = "Rapport de paiement d'avance";
            //Here
            if (!empty($request->dateDebut) && !empty($request->dateFin)) {

                Carbon::setLocale('fr');
                $title = "Le Rapport de  " . Carbon::now()->translatedFormat('j Y');


                $datas = Supply::whereDate('created_at', '>=', $request->dateDebut)
                    ->whereDate('created_at', '<=', $request->dateFin)
                    ->where('id_setting', auth()->user()->id_setting)
                    ->simplePaginate(15);
                //->get();

                if (count($datas) > 0) {
                    foreach ($datas as $item) {

                        $qte_commander += $item->qte_commander;
                        $qte_valider += $item->qte_valider;

                        if ($item->operation == "Importer") {
                            $importerCounter = $item->qte_commander * $item->prix;
                            $importer += $importerCounter;
                        }

                        if ($item->operation == "Exporter") {
                            $exporterCounter = $item->qte_valider * $item->prix;
                            $exporter += $exporterCounter;
                        }
                    }
                    $total = $importer + $exporter;
                }
            }
            if ($request->option != "PRINT") {
                return view("supply.index", compact("datas", "qte_commander", "qte_valider", "exporter", "importer", "total"));
            }
        } elseif ($operation == "BANK") {
            $totalM = 0;
            $totalV = 0;
            $totalR = 0;
            $datas = 0;
            $desc = "Rapport de paiement d'avance";

            //Here
            $datas = Bank::whereDate('created_at', '>=', $request->dateDebut)
                ->whereDate('created_at', '<=', $request->dateFin)
                ->where('id_setting', auth()->user()->id_setting)
                ->get();
            if (count($datas) > 0) {
                foreach ($datas as $item) {
                    $totalM = $item->total;
                    $totalV = $item->qte;
                }
            }

            $totalR = Bank::whereDate('created_at', '>=', $request->dateDebut)
                ->whereDate('created_at', '<=', $request->dateFin)
                ->where('id_setting', auth()->user()->id_setting)
                ->distinct()
                ->sum("montantPay");


            Carbon::setLocale('fr');
            $title = "Le Rapport de  " . Carbon::now()->translatedFormat('j Y');

            if ($request->option != "PRINT") {
                return view("supply.index", compact("datas", "depenseT", "depenseM"));
            }
        }

        if ($request->option == "PRINT") {
            if (count($datas) > 0) {

                $datass = [
                    'carts' => $datas,
                    'totalM' => $totalM,
                    'totalR' => $totalR,
                    'totalV' => $totalV,
                    "title" => $title,
                    'date_hr' => Carbon::now(),
                    'operation' => $operation,
                    'desc' => $desc,
                    'productName' => '',
                ];

                $pdf = PDF::loadView('pdf.venteParProd', $datass);
                return $pdf->download("venteParMois" . date('d-m-Y') . ".pdf");
            }
        }
    }

    public function ValiderProd(Request $request, $id)
    {
        // dd($request->all());
        $request->validate([
            "qte_valider" => 'required|array',
            "qte_valider.*.$id" => 'required|numeric|min:1',
            "statut" => 'required',
        ]);

        $supply = Supply::find($id);
        $supply_detail = SupplieDetail::where('supplie_id', $supply->id)->get();
        try {
            DB::beginTransaction();
            if ($request->statut != "non valider") {
                foreach ($supply_detail as $key => $detail) {
                    $supplie_detail = [
                        'qte_valider' => $request->qte[$key][$id],
                        'date_expiration' => $request->date_expiration[$key][$id],
                    ];

                    $produits = [
                        "code_barre" => '',
                        "id_categorie" => $detail->id_cat,
                        "id_fournisseur" => $supply->id_fournisseur,
                        "quantite" => $supplie_detail['qte_valider'],
                        "prix_achat" => $detail->prix,
                        "Total_achat" => $detail->prix * $supplie_detail['qte_valider'],
                        "Total_en_gros" => $detail->prix_gros * $supplie_detail['qte_valider'],
                        "Total_benefice_en_gros" => $supply->total_ht - ($detail->prix_gros * $supplie_detail['qte_valider']),
                        "Total_en_detail" => $detail->prix_detail * $supplie_detail['qte_valider'],
                        "Total_benefice_en_detail" => $supply->total_ht - ($detail->prix_detail * $supplie_detail['qte_valider']),
                        "options_barcode" => 'NON',
                        "qte_par_carton" => 1,
                        "qte_total_en_detail" => $supplie_detail['qte_valider'],
                        "prix_vente_en_gros" => $detail->prix_gros,
                        "prix_vente_unitaire" => $detail->prix_detail,
                        "id_prod" => $detail->id_prod,
                        "username" => auth()->user()->id,
                        "date_expiration" => $supplie_detail['date_expiration'],
                        "id_setting" => auth()->user()->id_setting,
                    ];

                    $dts = [
                        'produit' => $detail->getProductNom($detail->id_prod),
                        'user_name' => auth()->user()->name,
                        'id_prod' => $detail->id_prod,
                        'qte' => "Aucun",
                        'qte_en_stock' => $supplie_detail['qte_valider'],
                        'num_charge' => $supply->numero_commande,
                        'operation' => "Entrer",
                        'service' => "Commande",
                        "id_setting" => auth()->user()->id_setting,
                    ];

                    $detail->update($supplie_detail);
                    (new Produit)->StoreProduit($produits);
                    (new EntreSortieStock)->StoreEntreSortieStock($dts);
                }
                $data['statut'] = $request->statut;
                $supply->update($data);
                DB::commit();
                return redirect()->route('commande.index')->with('succes', "La commande a été valider part visiter l'entrepôt");
            } else {
                $data['statut'] = $request->statut;
                $supply->update($data);
                DB::commit();
                return redirect()->route('commande.index')->with('info', "La commande a été réjeter par non validation");
            }
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with(
                'error',
                "Une erreur est survenue lors de la validation de la commande. Veuillez réessayer."
            );
        }
    }

    public function AddNewProd(Request $request)
    {
        $datas = $request->validate([
            "libelle" => ['required']
        ]);
        $datas['id_setting'] = auth()->user()->id_setting;
        (new Stock)->StoreStock($datas);
        return back()->with("succes", "Enregistrement effectué avec succès");
    }

    public function getProduitPrix($id)
    {
        $data = Supply::where('id', $id)->get();
        return response()->json($data);
    }

    public function PrintCmdAfterSale(Request $request)
    {
        $objAmount = new CurrencyConverterController();
        $amountInWords = 0;
        $desc = "Rapport de Commande";
        $operation = "COMMANDES";

        $supply = Supply::find($request->id);
        $cart = SupplieDetail::where('supplie_id', $request->id)->get();
        $frs = Fournisseur::find($supply['id_fournisseur']);
        if ($request->valider == "print") {
            $amountInWords = $objAmount->convertAmountToWords($supply->total_ht);
            $datass = [
                'fournisseur' => $frs->nom_fournisseur,
                'numero_commande' => $supply['numero_commande'],
                'carts' => $cart,
                'total_ht' => $supply->total_ht,
                'fraisTransit' => $supply["fraisTransit"],
                'fraisLogistique' => $supply["fraisLogistique"],
                'montantDonner' => $supply['montantDonner'],
                'restant' => $supply['restant'],
                'date_hr' => $supply->dates,
                "username" => $supply["username"],
                "operation" => $operation,
                "desc" => $desc,
                "amountInWords" => $amountInWords,
            ];
            $pdf = PDF::loadView('pdf.commande', $datass);
            session()->flash('succes', 'Votre commande à été effectué');
            return $pdf->download("Facture_commande_" . $supply['numero_commande'] . ".pdf");
        }
    }


    //Write method to show supply by numero_commande
    public function showByNumeroCommande($numero_commande)
    {
        $data = Supply::where("numero_commande", $numero_commande)->get();
        return view("supply.valider", compact("data"));
    }
}
