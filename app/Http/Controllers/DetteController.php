<?php

namespace App\Http\Controllers;

use App\Models\Categorie;
use App\Models\Client;
use App\Models\Dette;
use App\Models\Entrepot;
use App\Models\EntreSortieStock;
use App\Models\ProductBoutigue;
use App\Models\Produit;
use App\Models\settings;
use App\Models\User;
use App\Models\Vente;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class DetteController extends Controller
{
    const SMS_API_LINK = "https://testapi.skillcodiing.com/api/sms/v1.0/sendSms";
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cats = Categorie::where('id_setting', auth()->user()->id_setting)->get();
        $totalR = 0;
        $total_ht = 0;
        $total_ttc = 0;
        $totalQ = 0;
        $totalM = 0;
        if (auth()->user()->roles == "Admin") {
            //$datas = Dette::whereDate('created_at', Carbon::today())->get();
            $datas = Dette::select('*')
                ->whereBetween(
                    'created_at',
                    [Carbon::now()->subMonth(6), Carbon::now()]
                )->where('id_setting', auth()->user()->id_setting)->latest()
                ->get();

            $users = User::where('id', '!=', '1')->where('id_setting', auth()->user()->id_setting)->get();

            foreach ($datas as $dt) {
                $totalR += $dt->reduction;
                $totalQ += $dt->quantite;
                if ($dt->tva == "") {
                    $total_ht += $dt->total_ht;
                } else {
                    $total_ttc += $dt->total_ttc;
                }
            }
            $totalM = ($total_ht + $total_ttc);

            return view("dette.index", compact("datas", "totalR", "totalM", "totalQ", "cats", "users"));
        }

        //$datas = Dette::whereDate('created_at', Carbon::today())
        //    ->where("username", auth()->user()->id)
        //    ->get();

        $datas = Dette::select('*')
            ->whereBetween(
                'created_at',
                [Carbon::now()->subMonth(6), Carbon::now()]
            )->where("username", auth()->user()->id)->latest()
            ->get();

        $users = User::where('id', '!=', '1')->where('id_setting', auth()->user()->id_setting)->get();

        foreach ($datas as $dt) {
            $totalR += $dt->reduction;
            $totalQ += $dt->quantite;
            if ($dt->tva == "") {
                $total_ht += $dt->total_ht;
            } else {
                $total_ttc += $dt->total_ttc;
            }
        }
        $totalM = ($total_ht + $total_ttc);

        return view("dette.index", compact("datas", "totalR", "totalM", "totalQ", "cats", "users"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $boutiqueId = auth()->user()->id_boutigue ?? session('selected_boutique_id');
        $magasin = Entrepot::where('id_boutique', $boutiqueId)->first();

        if (auth()->user()->roles === "Super Admin") {
            return redirect()->route('vente.index')->with("info", "Les Super Admin ne peuvent pas effectuer de ventes.");
        }
        $magasins = Entrepot::get();
        $codebar = settings::find(auth()->user()->id_setting);
        $check = $codebar->bar_option ?? 'NON';

        $datas = ProductBoutigue::where("stock_id", $magasin->id)->latest()->get();
        $clients = Client::where('id_setting', auth()->user()->id_setting)->latest()->get();
        // session()->forget('dettes');
        return view("dette.create", compact("datas", "clients", "check", "magasins", "magasin"));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!$request->session()->has('dettes') && !filled($request->session()->get('dettes'))) {
            return back()->with("error", "Whoops : Votre panier est vide");
        }
        $request->validate([
            "total_ht" => ["required"],
            "nom" => ["required"],
            "dateApayer" => ["required"]
        ]);

        $boutiqueId = auth()->user()->id_boutigue ?? session('selected_boutique_id');
        $dtSms = settings::where('id', auth()->user()->id_setting)->first();
        $objAmount = new CurrencyConverterController();
        $amountInWords = 0;

        $datas['id_setting'] = auth()->user()->id_setting;
        $dts['id_setting'] = auth()->user()->id_setting;

        $datas['id_boutique'] = $boutiqueId;
        $dts['id_boutique'] = $boutiqueId;
        $datas['montantDonner'] = $request->montantDonner ?? 0;
        $datas['restant'] = $request->restant ?? $request->total_ht;

        $desc = "Rapport de dette";
        $operation = "Créances";
        try {
            DB::beginTransaction();
            $obj = new Dette();
            $datas['clientId'] = $obj->generateClientId();
            $datas['dateApayer'] = $request->dateApayer;
            $datas['comments'] = $request->comments;
            $datas['id_setting'] = auth()->user()->id_setting;

            $nom = explode(';', $request->nom);
            $datas['nom'] = $nom['1'];
            $datas['contact'] = $nom['0'];

            if ($request->tva != '') {
                $datas["tva"] = $request->tva;
                $datas["total_tva"] = $request->total_tva;
                $datas["total_ttc"] = $request->total_ttc;
                $datas["total_ht"] = $request->total_ht;
                $amountInWords = $objAmount->convertAmountToWords($request->total_ttc);
            } else {
                $datas["total_ht"] = $request->total_ht;
                $amountInWords = $objAmount->convertAmountToWords($request->total_ht);
            }

            $carts = session()->get('dettes');

            // dd($carts);
            foreach ($carts as $cart) {
                $data['id_prod'] = $cart['prod'];
                $datas['nom_produit'] = $cart['produit'];
                $datas['prix'] = $cart['prix'];
                $datas['quantite'] = $cart['qte'];
                $datas['options'] = $cart['options'];
                $datas['montant'] = $cart['total'];
                $datas['categorie'] = $cart['categorie'];
                $datas['stock_id'] = $cart['stock_id'];
                $datas['username'] = $cart['username'];
                //Remove qte in stock
                $this->RemoveDette($cart['prod'], $cart['qte'], '', $datas['id_boutique'], $cart['stock_id']);
                $data = $obj->StoreDette($datas);

                $dts['produit'] = $datas['nom_produit'];
                $dts['user_name'] = auth()->user()->name;
                $dts['qte'] = $datas['quantite'];
                $dts['qte_en_stock'] = ((int) (new EntreSortieStock())->QteEnStock($cart['prod']) - (int) $datas['quantite']);
                $dts['num_charge'] = $datas['clientId'];
                $dts['operation'] = "Sortir";
                $dts['service'] = "Créances";
                $dts['id_prod'] = $cart['prod'];
                $dts['id_setting'] = $cart['id_setting'] ?? auth()->user()->id_setting;
                (new EntreSortieStock)->StoreEntreSortieStock($dts);
                
            }

            if ($data) {
                // if ($dtSms->sms == "OUI" && $datas['contact'] != "") {

                //     $msg = explode('[numero]', $dtSms->msgAchat);
                //     $message = $msg[0] . $datas['clientId'] . $msg[1];
                //     $message = explode('[operation]', $message);
                //     $message = $message[0] . 'créances' . $message[1];

                //     Http::post(self::SMS_API_LINK, [
                //         'email' => $dtSms->email,
                //         'password' => $dtSms->password,
                //         'phoneNumber' => $datas['contact'],
                //         'senderName' => $dtSms->senderName,
                //         'message' => $message,
                //     ]);
                // }

                if ($request->valider == "print") {
                    $datass = [
                        'nom' => $datas['nom'],
                        'clientId' => $datas['clientId'],
                        'contact' => $datas['contact'],
                        'carts' => $carts,
                        'total_ht' => $request->total_ht,
                        'total_tva' => $request->total_tva,
                        'total_ttc' => $request->total_ttc,
                        'tva' => $request->tva,
                        'montantApayer' => $datas['total_ht'],
                        'montantDonner' => $datas['montantDonner'],
                        'restant' => $datas['restant'],
                        'date_hr' => Carbon::now(),
                        "username" => $datas["username"],
                        "apayer" => $datas["dateApayer"],
                        'amountInWords' => $amountInWords,
                        "operation" => $operation,
                        "desc" => $desc,

                    ];
                    $pdf = PDF::loadView('pdf.dettes', $datass);
                    session()->flash('succes', 'Achat a ete affectue');
                    DB::commit();
                    return $pdf->stream("Dette_n°_" . $datas['clientId'] . ".pdf");
                }
                DB::commit();
                session()->forget('dettes');
                session('dettes', []);
                return back()->with("succes", "Enregistrement effectué avec succès.");
            }
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with(
                'error',
                "Une erreur est survenue. Veuillez réessayer.|" . $e
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
        $datas = Dette::find($id);
        return view("dette.edit", compact("datas"));
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
        $request->validate([
            "montant" => ["required"]
        ]);
        $datas = Dette::where("clientId", $request->clientId)->get();

        if (count($datas) > 0) {
            foreach ($datas as $dt) {
                $montantApayer = $dt->montantApayer;
                $montantDonner = $dt->montantDonner;
                $totalActualPayer = $montantDonner + $request->montant;
                $restant = $montantApayer - $totalActualPayer;
                $dt->restant = $restant;
                $dt->comments = $request->comments;
                $dt->datepayer = Carbon::now();
                $dt->montantDonner = $totalActualPayer;

                $dt->update();
            }
        }

        if ($datas) {
            return redirect()->route("dette.index")->with("succes", "Mise à jour effectuée avec succès.");
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
        $obj = new Dette();
        $data = $obj->deleteDette($id);
        if ($data) {
            return back()->with("succes", "Supression reussie");
        }
        return back()->with("error", "Supression non reussie");
    }

    public function RemoveDette($idprod, $qteAchat, $typeQte, $id_boutique, $id_stock)
    {
        $data = Dette::where("id_prod", $idprod)->first();
        $dataNew = ProductBoutigue::where('id_prod', $idprod)
            ->where('id_boutique', $id_boutique)
            ->where('stock_id', $id_stock)
            ->first();

        if (!empty($dataNew) && $dataNew->quantite > $qteAchat) {
            $qteStock = $dataNew->quantite;
            $qte_total_en_detail = $dataNew->qte_total_en_detail;
            if ($typeQte == "QTEDETAIL") {
                $dataNew->qte_total_en_detail = $qte_total_en_detail - (int) $qteAchat;
            } else {
                $dataNew->quantite = $qteStock - (int) $qteAchat;
            }

            // dd($dataNew->quantite);

            return $dataNew->update();
        }
        return;
    }

    public function PrintInvoice(Request $request)
    {
        $objAmount = new CurrencyConverterController();
        $amountInWords = 0;

        if ($request->option == "DETTE") {
            $datas = Dette::where('clientId', $request->clientId)->get();
            $data = Dette::where('clientId', $request->clientId)->first();

            if ($data->tva != "") {
                $amountInWords = $objAmount->convertAmountToWords($data->total_ttc);
            } else {
                $amountInWords = $objAmount->convertAmountToWords($data->total_ht);
            }

            $datass = [
                'datas' => $datas,
                'nom' => $data->nom,
                'clientId' => $data->clientId,
                'contact' => $data->contact,
                'carts' => $data->cart,

                'total_ht' => $data->total_ht,
                'total_tva' => $data->total_tva,
                'total_ttc' => $data->total_ttc,
                'tva' => $data->tva,
                'amountInWords' => $amountInWords,

                'montantApayer' => $data->montantApayer,
                'montantDonner' => $data->montantDonner,
                'restant' => $data->restant,
                'date_hr' => $data->created_at,
                "username" => User::find($data->username)->name,
                "operation" => 'créance',

            ];
            $pdf = PDF::loadView('pdf.invoiceAfter', $datass);
            return $pdf->stream("Creance_n°_" . $data->clientId . ".pdf");
        }
    }

    public function deleteAfterBuy($id, $clientId)
    {
        $quantite = 0;
        $obj = new Dette();
        $datas = Dette::where("clientId", $clientId)->get();
        $boutiqueId = auth()->user()->id_boutigue ?? session('selected_boutique_id');
        foreach ($datas as $data) {
            $montApayer = $data->total_ht;
            $mont = $data->montant;
            $data->total_ht = $montApayer - $mont;
            $data->save();
        }
        $qte = $obj->getOneProduct($id);
        if (!empty($qte->quantite)) {
            $quantite = $qte->quantite;
        }
        $this->AddQte($id, $quantite, '');
        if (!empty($qte->id_prod)) {
            $dts['produit'] = $qte->nom_produit;
            $dts['user_name'] = auth()->user()->name;
            $dts['qte'] = "Aucun";
            $dts['qte_en_stock'] = ((int) (new EntreSortieStock())->QteEnStock($qte->id_prod) + (int) $quantite);
            $dts['num_charge'] = $qte->clientId;
            $dts['operation'] = "Créances Annuler";
            $dts['service'] = "Créances";
            $dts['id_prod'] = $qte->id_prod;
            $dts['id_boutique'] = $boutiqueId;
            (new EntreSortieStock)->StoreEntreSortieStock($dts);
        }

        $data = $obj->deleteDette($qte->id);
        if ($data) {
            return back()->with("succes", "Supression reussie");
        }
        return back()->with("error", "Supression non reussie");
    }

    public function AddQte($idprod, $qteAchat, $typeQte)
    {
        $data = Dette::where("id", $idprod)->first();
        $dataNew = ProductBoutigue::where('id_prod', $data->id_prod)->latest()->first();
        if (!empty($dataNew)) {
            $qteStock = $dataNew->quantite;
            $qte_total_en_detail = $dataNew->qte_total_en_detail;
            if ($typeQte == "QTEDETAIL") {
                $dataNew->qte_total_en_detail = $qte_total_en_detail + (int) $qteAchat;
            } else {
                $dataNew->quantite = $qteStock + (int) $qteAchat;
            }

            return $dataNew->update();
        }
        return;
    }

    //Show Dette by client
    public function showDetteByClient($clientId)
    {
        $datas = Dette::where('clientId', $clientId)->get();
        return view("dette.showByClient", compact("datas"));
    }
}
