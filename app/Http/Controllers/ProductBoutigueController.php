<?php

namespace App\Http\Controllers;

use App\Models\Categorie;
use App\Models\HistoryTransfer;
use App\Models\ProductBoutigue;
use App\Models\Produit;
use App\Models\settings;
use App\Models\Entrepot;
use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class ProductBoutigueController extends Controller
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
        //$boutiques = Boutique::where('id_setting', auth()->user()->id_setting)->latest()->get();
        //$qte_alert = settings::where('id', auth()->user()->id_setting)->pluck('qte_alert');
        //$datas = Produit::where('id_setting', auth()->user()->id_setting)->orderBy('id', 'desc')->limit(100000)->get();
        //$produits = Produit::where('id_setting', auth()->user()->id_setting)->get();
        $cats = Categorie::where('id_setting', auth()->user()->id_setting)->get();
        $qte_alert = settings::where('id', auth()->user()->id_setting)->pluck('qte_alert');

        $magasin = Entrepot::find($request->id);
        if (auth()->user()->roles === "Super Admin") {
            if (empty($request->id)) {
                $datas = ProductBoutigue::latest()->get();
            }
            $datas = ProductBoutigue::where('stock_id', $request->id)->latest()->get();
        } else {
            $datas = ProductBoutigue::where("stock_id", $request->id)->latest()->get();
        }

        if (count($datas) > 0) {
            foreach ($datas as $val) {
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


        return view("produitStock.index", compact('datas', 'totalA', 'totalAu', 'totalG', 'totalQ', 'qte_alert', 'magasin'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */


    public function store(Request $request)
    {
        // Step 1: Validate request
        $datas = $request->validate([
            'id' => ['required', 'exists:produits,id'], // source product ID
            'id_prod' => ['required'],                  // destination product ID (custom identifier or foreign key)
            'quantite' => ['required', 'numeric', 'min:1'],
            'entrepot_id' => 'required',
        ]);

        // Step 2: Fetch source product
        $sourceProduct = Produit::find($request->id);

        if (!$sourceProduct) {
            return back()->with("info", "Produit source introuvable.");
        }

        // Step 3: Verify stock availability
        if ($request->quantite > $sourceProduct->quantite) {
            return back()->with("info", "Stock insuffisant. Vous avez seulement {$sourceProduct->quantite} quantité en stock.");
        }

        $id_boutique = session('selected_boutique_id') ?? auth()->user()->id_boutigue;
        $invoice = session()->get('invoice', []);
        // Wrap in transaction to ensure consistency
        DB::beginTransaction();
        try {
            $dataTrackTransfer['id_prod'] = $sourceProduct->id_prod;
            $dataTrackTransfer['quantite'] = $request->quantite;
            $dataTrackTransfer['qte'] = $request->quantite;
            $dataTrackTransfer['prix_achat'] = $sourceProduct->prix_achat;
            $dataTrackTransfer['username'] = $sourceProduct->username;
            $dataTrackTransfer['id_setting'] = $sourceProduct->id_setting;
            $dataTrackTransfer['id_boutique'] = $id_boutique;
            $dataTrackTransfer['entrepot_id'] = $request->entrepot_id;

            $invoice = [
                'nom_produit' => Stock::find($sourceProduct->id_prod)->libelle,
                'quantite' => $request->quantite,
                'prix_achat' => $sourceProduct->prix_achat,
                'username' => $sourceProduct->username,
                'nom_entrepot' => Entrepot::find($request->entrepot_id)->nom_entrepot,
            ];

            // Step 5: Transfer to destination
            $destinationProduct = ProductBoutigue::where('id_prod', $request->id_prod)
                ->where('id_boutique', $id_boutique)->where('stock_id', $request->entrepot_id)
                ->first();

            // dd($destinationProduct);

            // Step 4: Decrease stock from source
            $sourceProduct->quantite -= $request->quantite;
            $sourceProduct->save();

            if ($destinationProduct) {
                // Update existing destination product quantity
                $destinationProduct->quantite += $request->quantite;

                $destinationProduct->save();
            } else {
                $datas = [
                    'id_prod' => $request->id_prod,
                    'quantite' => $request->quantite,
                    'id_boutique' => $id_boutique,
                    'stock_id' => $request->entrepot_id,
                    "code_barre" => $sourceProduct->code_barre,
                    "id_categorie" => $sourceProduct->id_categorie,
                    "id_fournisseur" => $sourceProduct->id_fournisseur,
                    "prix_achat" => $sourceProduct->prix_achat,
                    "prix_vente_en_gros" => $sourceProduct->prix_vente_en_gros,
                    "prix_vente_unitaire" => $sourceProduct->prix_vente_unitaire,
                    "username" => $sourceProduct->username,
                    "date_expiration" => $sourceProduct->date_expiration,
                    "id_setting" => $sourceProduct->id_setting,
                ];
                ProductBoutigue::create($datas);
            }
            //Store history
            (new HistoryTransfer())->StoreHistoryTransfer($dataTrackTransfer);
            DB::commit();
            session()->put('invoice', $invoice);
            return back()->with("succes", "Transfert de {$request->quantite} quantité effectué avec succès.");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with("error", "Erreur lors du transfert: " . $e->getMessage());
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function printTransfert(Request $request)
    {
        if (!session()->has('invoice') && !filled(session()->get('invoice'))) {
            return back()->with("error", "Oops : La facture a été déjà télécharger ou la session est expiré.");
        }
        $dataPrint = session()->get('invoice');
        // dd($datass['numero_comm']);
        $pdf = PDF::loadView('produit.printCmdBon', compact('dataPrint'));
        return $pdf->download("Bon_de_transfert.pdf");
    }

    public function cancelTransfert(Request $request)
    {
        $request->validate([
            "qte" => "required",
        ]);
        //dd($request->all());
        DB::beginTransaction();

        try {
            // 1. Get transfer history
            $history = HistoryTransfer::find($request->id);
            if (!$history) {
                return back()->with("error", "Transfert introuvable.");
            }

            // 2. Prevent double cancellation
            if ($history->statut == "Cancelled") {
                return back()->with("error", "Ce transfert est déjà annulé.");
            }

            // 3. Get source product (entrepot)
            $sourceProduct = Produit::where('id_prod', $history->id_prod)
                ->first();

            if (!$sourceProduct) {
                return back()->with("error", "Produit source introuvable.");
            }

            // 4. Get destination product (boutique)
            $destinationProduct = ProductBoutigue::where('id_prod', $history->id_prod)
                ->where('id_boutique', $history->id_boutique)
                ->first();

            // dd($destinationProduct);

            if (!$destinationProduct) {
                return back()->with("error", "Ce produit est introuvable dans le magasin " . $history->showEntrepot($history->entrepot_id));
            }

            // 5. Check stock before reversing
            if ($destinationProduct->quantite < $request->qte) {
                return back()->with("error", "La quantite existe plus dans le magasin {$history->showEntrepot($history->entrepot_id)} pour annuler ce transfert.");
            }

            // 6. Reverse stock
            $destinationProduct->quantite -= $request->qte;

            $destinationProduct->save();

            $sourceProduct->quantite += $request->qte;
            $sourceProduct->save();

            // 7. Update status

            if ($history->qte == $request->qte) {
                $history->statut = "Cancelled";
                $history->save();
            }
            // $history->quantite -= $request->qte;
            $history->qte -= $request->qte;
            $history->save();

            DB::commit();

            return back()->with("succes", "Transfert annulé avec succès.");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with("error", "Erreur lors de l'annulation: " . $e->getMessage());
        }
    }
}
