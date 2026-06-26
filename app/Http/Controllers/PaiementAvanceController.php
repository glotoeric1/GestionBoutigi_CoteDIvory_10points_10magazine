<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\EntreSortieStock;
use App\Models\PaiementAvance;
use App\Models\ProductBoutigue;
use App\Models\Stock;
use App\Models\settings;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Http;


class PaiementAvanceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $totalT = 0;
        $totalP = 0;
        $totalR = 0;
        $totalAmountPay = 0;
        $totalQ = 0;
        $boutiqueId = auth()->user()->id_boutigue ?? session('selected_boutique_id');

        if (auth()->user()->roles == "Admin" || auth()->user()->roles == "Gestionaire" || auth()->user()->roles == "Controlleur") {
            $obj = new PaiementAvance();
            $datas = PaiementAvance::where('id_boutique', $boutiqueId)->latest()
                ->get();
            $clients = PaiementAvance::where('id_boutique', $boutiqueId)->latest()->get();
            $prods = ProductBoutigue::where('id_boutique', $boutiqueId)->latest()->get();
        } else {
            $obj = new PaiementAvance();
            $datas = PaiementAvance::whereDate('created_at', Carbon::today())
                ->where("done_by", auth()->user()->name)
                ->where('id_boutique', $boutiqueId)
                ->get();
            $clients = PaiementAvance::where("done_by", auth()->user()->name)
                ->where('id_boutique', $boutiqueId)
                ->latest()->get();
            $prods = ProductBoutigue::where("username", auth()->user()->id)
                ->where('id_boutique', $boutiqueId)
                ->latest()->get();
        }

        if (!empty($datas)) {
            foreach ($datas as $val) {
                $totalT += $val->total;
                $totalR += $val->restant;
                $totalQ += $val->qte;
                if ($val->tva == '') {
                    $totalP += $val->total_ht;
                    $totalAmountPay += $val->montantPay;
                } else {
                    $totalP += $val->total_ttc;
                    $totalAmountPay += $val->montantPay;
                }

            }
        }

        return view("paiementavance.index", compact("datas", "totalAmountPay", "prods", "totalQ", "totalT", "totalR", "totalP", "clients"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $boutiqueId = auth()->user()->id_boutigue ?? session('selected_boutique_id');

        $datas = Stock::where("id_setting", auth()->user()->id_setting)
            ->latest()->get();
        $prod_boutiques  = ProductBoutigue::pluck('id_prod')->toArray();
        $clients = Client::where("id_setting", auth()->user()->id_setting)->latest()->get();
        return view("paiementavance.create", compact("datas", "clients", "prod_boutiques"));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $dtSms = settings::where('id', auth()->user()->id_setting)->first();
        //dd($request->all());
        $objAmount = new CurrencyConverterController();
        $amountInWords = 0;

        $datas = $request->validate([

            "montantPay" => ['required', "numeric"],
            "nom" => ['required'],
            "titre" => ['required'],
            "qte" => ['required', "numeric"],
            "montant" => ['required', "numeric"],
            "total" => ['required', "numeric"],
            "restant" => ['required', "numeric"],
        ]);

        $obj = new PaiementAvance();
        $datas["done_by"] = auth()->user()->name;
        $datas["id_setting"] = auth()->user()->id_setting;
        $datas["descs"] = $request->descs;
        $datas["contact"] = "";
        $datas["clientId"] = $obj->generatePayId();
        $datas['id_prod'] = $request->id_prod;

        $contact = explode(';', $request->nom);
        $datas["nom"] = $contact[1];
        $datas["contact"] = $contact[0];

        if ($request->tva_id != '') {
            $datas["tva"] = $request->tva_id;
            $datas["total_tva"] = $request->total_tva;
            $datas["total_ttc"] = $request->total_ttc;
            $datas["total_ht"] = $request->total;
            $amountInWords = $objAmount->convertAmountToWords($request->total_ttc);
        } else {
            $datas["total_ht"] = $request->total;
            $amountInWords = $objAmount->convertAmountToWords($request->total_ht);
        }

        $data = $obj->StorePaiementAvance($datas);
        if ($data) {
            $dts['produit'] = $datas['titre'];
            $dts['user_name'] = auth()->user()->name;
            $dts['id_setting'] = auth()->user()->id_setting;
            $dts['qte'] = $datas['qte'];
            $dts['num_charge'] = $datas['clientId'];
            $dts['operation'] = "Sortir";
            $dts['service'] = "Paiement d'avance";
            (new EntreSortieStock)->StoreEntreSortieStock($dts);

            // if ($dtSms->sms == "OUI" && $datas['contact'] != "") {

            //     $msg = explode('[numero]', $dtSms->msgAchat);
            //     $message = $msg[0] . $datas['clientId'] . $msg[1];
            //     $message = explode('[operation]', $message);
            //     $message = $message[0] . "Paiement d'avance" . $message[1];

            //     Http::post('https://testapi.skillcodiing.com/api/sms/v1.0/sendSms', [
            //         'email' => $dtSms->email,
            //         'password' => $dtSms->password,
            //         'phoneNumber' => $datas['contact'],
            //         'senderName' => $dtSms->senderName,
            //         'message' => $message,
            //     ]);

            // }


            if ($request->saved == "SAVED") {
                return back()->with("succes", "Enregistrement effectué avec succès");
            }
            $datass = [
                'nom' => $datas['nom'],
                'clientId' => $datas['clientId'],
                'contact' => $datas['contact'],
                'prodName' => $obj->ShowNameAvance($request->titre),
                'montantPay' => $datas['montantPay'],
                'montant' => $datas['montant'],
                'total' => $datas['total'],
                'restant' => $datas['restant'],

                'total_ht' => $request->total,
                'total_tva' => $request->total_tva,
                'total_ttc' => $request->total_ttc,
                'tva' => $request->tva_id,
                'amountInWords' => $amountInWords,

                'date_hr' => $this->FormatDate(Carbon::now()) . " à " . $this->FormatHour(Carbon::now()),
                "username" => $datas["done_by"],
                "qte" => $datas["qte"],
                "operation" => "First",
            ];
            $pdf = PDF::loadView('pdf.paymentAvance', $datass);
            return $pdf->download("payment_advance_" . $datas['clientId'] . ".pdf");
        }
        return back()->with("error", "Catégorie n'a pas été ajoutée!");
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
        $boutiqueId = auth()->user()->id_boutigue ?? session('selected_boutique_id');
        $data = PaiementAvance::find($id);
        $datas = ProductBoutigue::where("id_boutique", $boutiqueId)->get();
        return view("paiementavance.edit", compact("data", "datas"));
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
        $objAmount = new CurrencyConverterController();
        $amountInWords = 0;

        if ($request->types == "PAY") {
            $datas = $request->validate([
                "montantPay" => ['required', 'numeric']
            ]);

            //Just for correcting an error
            $totalAmountPaid = $request->montantDejaPayer;
            $amount = $request->montantPay;
            $totalAmountToPay = $request->totalApayer;

            if (($totalAmountPaid + $amount) > $totalAmountToPay) {
                return back()->with("error", "Le montant total (" . number_format($totalAmountPaid + $amount) . " Fcfa ) que vous souhaitez payer est supérieur à ce que vous devez (" . number_format($totalAmountToPay) . " Fcfa )");
            }


            $data = PaiementAvance::find($id);

            $datas["nom"] = $data->nom;
            $datas["contact"] = $data->contact;
            $datas["clientId"] = $data->clientId;
            $datas["titre"] = $data->titre;
            $datas["descs"] = $data->descs;
            $datas["qte"] = $data->qte;
            $datas["montant"] = $data->montant;
            $datas["total"] = 0;
            $datas["restant"] = 0;
            $datas["done_by"] = auth()->user()->name;
            $datas["montantPay"] = $request->montantPay;
            //dd($datas);


            $obj = new PaiementAvance();
            $dt = $obj->StorePaiementAvance($datas);

            if ($dt) {
                if ($request->option == "AVANCE") {
                    $obj = new PaiementAvance();
                    $datas = PaiementAvance::where('clientId', $request->clientId)->latest()->get();
                    $data = PaiementAvance::where('clientId', $request->clientId)->first();
                    //dd($data->titre);

                    if ($request->tva != '') {
                        $amountInWords = $objAmount->convertAmountToWords($data->total_ttc);
                    } else {
                        $amountInWords = $objAmount->convertAmountToWords($data->total_ht);
                    }

                    $datass = [
                        'nom' => $data->nom,
                        'clientId' => $data->clientId,
                        'contact' => $data->contact,
                        'datas' => $datas,
                        'prodName' => $obj->ShowNameAvance($data->titre),
                        'restant' => $data->restant,
                        'date_hr' => $data->created_at,
                        "operation" => $request->option,
                        'montantPay' => $data->montantPay,
                        'montant' => $data->montant,
                        'total' => $data->total,
                        "username" => $data->done_by,
                        "qte" => $data->qte,
                        //
                        'total_ht' => $data->total,
                        'total_tva' => $data->total_tva,
                        'total_ttc' => $data->total_ttc,
                        'tva' => $data->tva,
                        'amountInWords' => $amountInWords,

                    ];
                    $pdf = PDF::loadView('pdf.paymentAvance', $datass);
                    return $pdf->download("payment_advance_" . $data->clientId . ".pdf");
                }
            }
            return back()->with("error", "Mise à jour non effectué.");
        } elseif ($request->types == "AVANCE_EDIT") {

            $datas = $request->validate([
                "montantPay" => ['required', "numeric"],
                "nom" => ['required'],
                "titre" => ['required'],
                "qte" => ['required', "numeric"],
                "montant" => ['required', "numeric"],
                "total" => ['required', "numeric"],
                "restant" => ['required', "numeric"],
            ]);
            $datas = $request->all();

            $data = PaiementAvance::find($id);

            $datas["nom"] = $data->nom;
            $datas["contact"] = $data->contact;
            $datas["clientId"] = $data->clientId;
            $datas["titre"] = $data->titre;
            $datas["descs"] = $data->descs;
            $datas["qte"] = $data->qte;
            $datas["montant"] = $data->montant;
            $datas["total"] = 0;
            $datas["restant"] = 0;
            $datas["done_by"] = auth()->user()->name;
            $datas["montantPay"] = $request->montantPay;
            //dd($datas);

            $obj = new PaiementAvance();
            $dt = $obj->StorePaiementAvance($datas);

            if ($dt) {
                if ($request->option == "AVANCE") {
                    $obj = new PaiementAvance();
                    $datas = PaiementAvance::where('clientId', $request->clientId)->latest()->get();
                    $data = PaiementAvance::where('clientId', $request->clientId)->first();
                    //dd($data->titre);

                    if ($request->tva != '') {
                        $amountInWords = $objAmount->convertAmountToWords($data->total_ttc);
                    } else {
                        $amountInWords = $objAmount->convertAmountToWords($data->total_ht);
                    }

                    $datass = [
                        'nom' => $data->nom,
                        'clientId' => $data->clientId,
                        'contact' => $data->contact,
                        'datas' => $datas,
                        'prodName' => $obj->ShowNameAvance($data->titre),
                        'restant' => $data->restant,
                        'date_hr' => $data->created_at,
                        "operation" => $request->option,
                        'montantPay' => $data->montantPay,
                        'montant' => $data->montant,
                        'total' => $data->total,
                        "username" => $data->done_by,
                        "qte" => $data->qte,
                        //
                        'total_ht' => $data->total,
                        'total_tva' => $data->total_tva,
                        'total_ttc' => $data->total_ttc,
                        'tva' => $data->tva,
                        'amountInWords' => $amountInWords,

                    ];
                    $pdf = PDF::loadView('pdf.paymentAvance', $datass);
                    return $pdf->download("payment_advance_" . $data->clientId . ".pdf");
                }
            }
            return back()->with("error", "Mise à jour non effectué.");
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $obj = new PaiementAvance();
        $data = $obj->deletePaiementAvance($id);
        if ($data) {
            return back()->with("succes", "Supression reussie");
        }
        return back()->with("error", "Supression non reussie");
    }

    public function getproduct($id)
    {
        $data = ProductBoutigue::where('id_prod', $id)->get();
        if($data){
            return response()->json($data);
        }else{
            return null;
        }
        
    }

    public function PrintInvoice(Request $request)
    {
        $objAmount = new CurrencyConverterController();
        $amountInWords = 0;

        if ($request->option == "AVANCE") {
            $obj = new PaiementAvance();
            $datas = PaiementAvance::where('clientId', $request->clientId)->latest()->get();
            $data = PaiementAvance::where('clientId', $request->clientId)->first();
            //dd($data->titre);

            if ($request->tva != '') {
                $amountInWords = $objAmount->convertAmountToWords($data->total_ttc);
            } else {
                $amountInWords = $objAmount->convertAmountToWords($data->total_ht);
            }

            $datass = [
                'nom' => $data->nom,
                'clientId' => $data->clientId,
                'contact' => $data->contact,
                'datas' => $datas,
                'prodName' => $obj->ShowNameAvance($data->titre),
                'restant' => $data->restant,
                'date_hr' => $data->created_at,
                "operation" => $request->option,
                'montantPay' => $data->montantPay,
                'montant' => $data->montant,
                'total' => $data->total,
                "username" => $data->done_by,
                "qte" => $data->qte,
                //
                'total_ht' => $data->total,
                'total_tva' => $data->total_tva,
                'total_ttc' => $data->total_ttc,
                'tva' => $data->tva,
                'amountInWords' => $amountInWords,

            ];
            $pdf = PDF::loadView('pdf.paymentAvance', $datass);
            return $pdf->download("payment_advance_" . $data->clientId . ".pdf");
        }


    }

    public function Recharche(Request $request)
    {

        $boutiqueId = auth()->user()->id_boutigue ?? session('selected_boutique_id');
        $obj = new PaiementAvance();
        $datas = $obj->getAll();
        $totalT = PaiementAvance::whereMonth('created_at', Carbon::now()->month)
            ->where('id_boutique', $boutiqueId)
            ->sum("total");
        $totalP = PaiementAvance::whereMonth('created_at', Carbon::now()->month)
            ->where('id_boutique', $boutiqueId)
            ->sum("montantPay");
        $totalR = PaiementAvance::whereMonth('created_at', Carbon::now()->month)
            ->where('id_boutique', $boutiqueId)
            ->sum("restant");
        $totalQ = PaiementAvance::whereDate('created_at', Carbon::today())
            ->where('id_boutique', $boutiqueId)
            ->sum("qte");

        $clients = PaiementAvance::where('id_boutique', $boutiqueId)->latest()->get();
        $prods = ProductBoutigue::where('id_boutique', $boutiqueId)->latest()->get();

        if ($request->types == "VENTES") {
            $operation = "VENTE";
            $desc = "Rapport de vente";

            if (!empty($request->dateDebut) && !empty($request->dateFin) && !empty($request->id_prod) && !empty($request->username)) {
                $datas = PaiementAvance::whereDate('created_at', '>=', $request->dateDebut)
                    ->whereDate('created_at', '<=', $request->dateFin)
                    ->where('username', $request->username)
                    ->where('id_prod', $request->id_prod)
                    ->get();


                $totalT = PaiementAvance::whereDate('created_at', '>=', $request->dateDebut)
                    ->whereDate('created_at', '<=', $request->dateFin)
                    ->where('username', $request->username)
                    ->where('id_prod', $request->id_prod)
                    ->sum("total");


                $totalQ = PaiementAvance::whereDate('created_at', '>=', $request->dateDebut)
                    ->whereDate('created_at', '<=', $request->dateFin)
                    ->where('username', $request->username)
                    ->where('id_prod', $request->id_prod)
                    ->sum("qte");

                $totalP = PaiementAvance::whereDate('created_at', '>=', $request->dateDebut)
                    ->whereDate('created_at', '<=', $request->dateFin)
                    ->where('username', $request->username)
                    ->where('id_prod', $request->id_prod)
                    ->distinct()
                    ->sum("montantPay");

                if ($request->option == "PRINT") {

                    if (count($datas) > 0) {

                        $prodName = $obj->ShowProdName($request->id_prod);
                        $username = $request->username;
                        $title = "Total " . $prodName . " vendue du " . $this->formatDate($request->dateDebut) . " au " . $this->formatDate($request->dateFin) . " par " . $username;

                        $datass = [
                            'productName' => $prodName,
                            'carts' => $datas,
                            'totalM' => $totalT,
                            'totalR' => $totalR,
                            'totalV' => $totalQ,
                            'dateDebut' => $request->dateDebut,
                            'dateFin' => $request->dateFin,
                            "username" => $username,
                            "title" => $title,
                            'date_hr' => Carbon::now(),
                            'operation' => $operation,
                            'desc' => $desc,
                        ];

                        $pdf = PDF::loadView('pdf.venteStatistics', $datass);
                        return $pdf->download("venteStatistics_" . date('d-m-Y') . ".pdf");
                    }

                }

            } else if (!empty($request->dateDebut) && !empty($request->dateFin) && !empty($request->id_prod)) {
                $datas = PaiementAvance::whereDate('created_at', '>=', $request->dateDebut)
                    ->whereDate('created_at', '<=', $request->dateFin)
                    ->where('id_prod', $request->id_prod)
                    ->get();


                $totalT = PaiementAvance::whereDate('created_at', '>=', $request->dateDebut)
                    ->whereDate('created_at', '<=', $request->dateFin)
                    ->where('id_prod', $request->id_prod)
                    ->sum("total");


                $totalP = PaiementAvance::whereDate('created_at', '>=', $request->dateDebut)
                    ->whereDate('created_at', '<=', $request->dateFin)
                    ->where('id_prod', $request->id_prod)
                    ->distinct()
                    ->sum("reduction");

                $totalQ = PaiementAvance::whereDate('created_at', '>=', $request->dateDebut)
                    ->whereDate('created_at', '<=', $request->dateFin)
                    ->where('id_prod', $request->id_prod)
                    ->sum("qte");

                if ($request->option == "PRINT") {
                    if (count($datas) > 0) {

                        $prodName = $obj->ShowProdName($request->id_prod);
                        $title = "Total " . $prodName . " vendue du " . $this->formatDate($request->dateDebut) . " au " . $this->formatDate($request->dateFin);

                        $datass = [
                            'productName' => $prodName,
                            'carts' => $datas,
                            'totalM' => $totalT,
                            'totalR' => $totalR,
                            'totalV' => $totalQ,
                            'dateDebut' => $request->dateDebut,
                            'dateFin' => $request->dateFin,
                            "title" => $title,
                            'date_hr' => Carbon::now(),
                            'operation' => $operation,
                            'desc' => $desc,
                        ];

                        $pdf = PDF::loadView('pdf.venteparproduit', $datass);
                        return $pdf->download("vente_Par_produit" . date('d-m-Y') . ".pdf");
                    }

                }

            } else if (!empty($request->username) && !empty($request->id_prod)) {
                $datas = PaiementAvance::where('username', $request->username)
                    ->where('id_prod', $request->id_prod)
                    ->get();


                $totalT = PaiementAvance::where('username', $request->username)
                    ->where('id_prod', $request->id_prod)
                    ->sum("montant");


                $totalP = PaiementAvance::where('username', $request->username)
                    ->where('id_prod', $request->id_prod)
                    ->distinct()
                    ->sum("reduction");

                $totalQ = PaiementAvance::where('username', $request->username)
                    ->where('id_prod', $request->id_prod)
                    ->sum("qte");

                if ($request->option == "PRINT") {
                    if (count($datas) > 0) {
                        $prodName = $obj->ShowProdName($request->id_prod);
                        $username = $request->username;
                        $title = "Total " . $prodName . " vendue par " . $username;

                        $datass = [
                            'productName' => $prodName,
                            'carts' => $datas,
                            'totalM' => $totalT,
                            'totalR' => $totalR,
                            'totalV' => $totalQ,
                            "title" => $title,
                            'date_hr' => Carbon::now(),
                            "username" => $username,
                            'operation' => $operation,
                            'desc' => $desc,
                        ];

                        $pdf = PDF::loadView('pdf.venteparproduitparuser', $datass);
                        return $pdf->download("vente_Par_produit_utilisateur" . date('d-m-Y') . ".pdf");
                    }

                }


                //dd($datas);
            } else if (!empty($request->dateDebut) && !empty($request->dateFin) && !empty($request->username)) {
                $datas = PaiementAvance::whereDate('created_at', '>=', $request->dateDebut)
                    ->whereDate('created_at', '<=', $request->dateFin)
                    ->where('username', $request->username)
                    ->where('id_boutique', $boutiqueId)
                    ->get();


                $totalT = PaiementAvance::whereDate('created_at', '>=', $request->dateDebut)
                    ->whereDate('created_at', '<=', $request->dateFin)
                    ->where('username', $request->username)
                    ->where('id_boutique', $boutiqueId)
                    ->sum("total");

                $totalQ = PaiementAvance::whereDate('created_at', '>=', $request->dateDebut)
                    ->whereDate('created_at', '<=', $request->dateFin)
                    ->where('username', $request->username)
                    ->where('id_boutique', $boutiqueId)
                    ->sum("qte");

                $totalP = PaiementAvance::whereDate('created_at', '>=', $request->dateDebut)
                    ->whereDate('created_at', '<=', $request->dateFin)
                    ->where('username', $request->username)
                    ->where('id_boutique', $boutiqueId)
                    ->distinct()
                    ->sum("reduction");

                if ($request->option == "PRINT") {
                    if (count($datas) > 0) {
                        $username = $request->username;
                        $title = "Vente effectuée par " . $username . " du " . $this->formatDate($request->dateDebut) . " au " . $this->formatDate($request->dateFin);

                        $datass = [
                            'carts' => $datas,
                            'totalM' => $totalT,
                            'totalR' => $totalR,
                            'totalV' => $totalQ,
                            'dateDebut' => $request->dateDebut,
                            'dateFin' => $request->dateFin,
                            "title" => $title,
                            "username" => $username,
                            'date_hr' => Carbon::now(),
                            'operation' => $operation,
                            'desc' => $desc,
                        ];

                        $pdf = PDF::loadView('pdf.venteParUserDate', $datass);
                        return $pdf->download("venteParUserDate" . date('d-m-Y') . ".pdf");
                    }

                }

            } else if (!empty($request->dateDebut) && !empty($request->dateFin)) {
                $datas = PaiementAvance::whereDate('created_at', '>=', $request->dateDebut)
                    ->whereDate('created_at', '<=', $request->dateFin)
                    ->where('id_boutique', $boutiqueId)
                    ->get();


                $totalM = PaiementAvance::whereDate('created_at', '>=', $request->dateDebut)
                    ->whereDate('created_at', '<=', $request->dateFin)
                    ->where('id_boutique', $boutiqueId)
                    ->sum("montant");


                $totalV = PaiementAvance::whereDate('created_at', '>=', $request->dateDebut)
                    ->whereDate('created_at', '<=', $request->dateFin)
                    ->where('id_boutique', $boutiqueId)
                    ->sum("qte");

                $totalR = PaiementAvance::whereDate('created_at', '>=', $request->dateDebut)
                    ->whereDate('created_at', '<=', $request->dateFin)
                    ->where('id_boutique', $boutiqueId)
                    ->distinct()
                    ->sum("reduction");

                if ($request->option == "PRINT") {

                    if (count($datas) > 0) {
                        $title = "Vente effectuée du " . $this->formatDate($request->dateDebut) . " au " . $this->formatDate($request->dateFin);

                        $datass = [
                            'carts' => $datas,
                            'totalM' => $totalM,
                            'totalR' => $totalR,
                            'totalV' => $totalV,
                            'dateDebut' => $request->dateDebut,
                            'dateFin' => $request->dateFin,
                            "title" => $title,
                            'date_hr' => Carbon::now(),
                            'operation' => $operation,
                            'desc' => $desc,
                        ];

                        $pdf = PDF::loadView('pdf.venteParDate', $datass);
                        return $pdf->download("venteParDate" . date('d-m-Y') . ".pdf");
                    }

                }


            } else if (!empty($request->username)) {
                $datas = PaiementAvance::where('username', $request->username)
                    ->where('id_boutique', $boutiqueId)
                    ->get();


                $totalR = PaiementAvance::Where('username', $request->username)
                    ->where('id_boutique', $boutiqueId)
                    ->distinct()
                    ->sum("reduction");

                $totalM = PaiementAvance::Where('username', $request->username)
                    ->where('id_boutique', $boutiqueId)
                    ->sum("montant");

                $totalV = PaiementAvance::Where('username', $request->username)
                    ->where('id_boutique', $boutiqueId)
                    ->sum("qte");


                if ($request->option == "PRINT") {
                    if (count($datas) > 0) {
                        $username = $request->username;
                        $title = "Vente effectuée par " . $username;

                        $datass = [
                            'carts' => $datas,
                            'totalM' => $totalM,
                            'totalR' => $totalR,
                            'totalV' => $totalV,
                            "title" => $title,
                            "username" => $username,
                            'date_hr' => Carbon::now(),
                            'operation' => $operation,
                            'desc' => $desc,
                        ];

                        $pdf = PDF::loadView('pdf.venteParUser', $datass);
                        return $pdf->download("venteParUser" . date('d-m-Y') . ".pdf");
                    }

                }

            } else if (!empty($request->id_prod)) {
                $datas = PaiementAvance::where('id_prod', $request->id_prod)->get();

                $totalM = PaiementAvance::where('id_prod', $request->id_prod)
                    ->sum("montant");

                $totalR = PaiementAvance::where('id_prod', $request->id_prod)
                    ->distinct()
                    ->sum("reduction");

                $totalV = PaiementAvance::where('id_prod', $request->id_prod)
                    ->sum("qte");


                if ($request->option == "PRINT") {

                    if (count($datas) > 0) {
                        $prodName = $obj->ShowProdName($request->id_prod);
                        $title = "Total " . $prodName . " vendue";

                        $datass = [
                            'productName' => $prodName,
                            'carts' => $datas,
                            'totalM' => $totalM,
                            'totalR' => $totalR,
                            'totalV' => $totalV,
                            "title" => $title,
                            'date_hr' => Carbon::now(),
                            'operation' => $operation,
                            'desc' => $desc,
                        ];

                        $pdf = PDF::loadView('pdf.venteParProd', $datass);
                        return $pdf->download("venteParProd" . date('d-m-Y') . ".pdf");
                    }

                }

            }

            return view("paiementavance.index", compact("datas", "prods", "totalQ", "totalT", "totalR", "totalP", "clients"));
        }
    }

    public function FormatDate($date)
    {
        return date('d-m-Y', strtotime($date));
    }

    public function FormatHour($date)
    {
        return date('H:i:s', strtotime($date));
    }

    //Show Dette by client
    public function showPaiementAvanceByClient($clientId)
    {
        $datas = PaiementAvance::where('clientId', $clientId)->get();
        return view("paiementavance.showByClient", compact("datas"));
    }



}