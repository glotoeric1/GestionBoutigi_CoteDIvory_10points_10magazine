<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Produit;
use App\Models\Service;
use App\Models\settings;
use App\Models\MiniService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Http;

class ServiceController extends Controller
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
        $totalQ = 0;
        $boutiqueId = auth()->user()->id_boutigue ?? session('selected_boutique_id');
        $obj = new Service();
        if (auth()->user()->roles == "Admin") {

            $datas = $obj->getAllLatest();
            if (count($datas) > 0) {
                foreach ($datas as $val) {
                    if ($val->tva == "") {
                        $totalT += $val->total_ht;
                    } else {
                        $totalT += $val->total_ttc;
                    }

                    $totalP += $val->montantPay;
                    $totalR += $val->restant;
                    $totalQ += $val->qte;
                }
            }
            return view("service.index", compact("datas", "totalQ", "totalT", "totalR", "totalP"));
        }
        $datas = Service::whereDate('created_at', Carbon::today())
            ->where("done_by", auth()->user()->id)->latest()->limit(500)
            ->where('id_setting', auth()->user()->id_setting)
            ->get();
        $datas = $obj->getAllLatest();
        if (count($datas) > 0) {
            foreach ($datas as $val) {
                if ($val->tva == "") {
                    $totalT += $val->total_ht;
                } else {
                    $totalT += $val->total_ttc;
                }
                $totalP += $val->montantPay;
                $totalR += $val->restant;
                $totalQ += $val->qte;
            }
        }
        return view("service.index", compact("datas", "totalQ", "totalT", "totalR", "totalP"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $boutiqueId = auth()->user()->id_boutigue ?? session('selected_boutique_id');

        $datas = MiniService::where('id_boutique', $boutiqueId)->latest()->get();
        $clients = Client::where('id_boutique', $boutiqueId)->latest()->get();
        return view("service.create", compact("datas", "clients"));
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
        $boutiqueId = auth()->user()->id_boutigue ?? session('selected_boutique_id');

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

        $obj = new Service();
        $datas["done_by"] = auth()->user()->name;
        $datas["descs"] = $request->descs;
        $datas["reduction"] = $request->reduction;
        $datas["clientId"] = $obj->generatePayId();
        $datas['id_setting'] = auth()->user()->id_setting;
        $contact = explode(';', $request->nom);
        $datas["nom"] = $contact[1];
        $datas["contact"] = $contact[0];
        $datas["id_boutique"] = $boutiqueId;

        if ($request->tva_id != '') {
            $datas["tva"] = $request->tva_id;
            $datas["total_tva"] = $request->total_tva;
            $datas["total_ttc"] = $request->total_ttc;
            $datas["total_ht"] = $request->total;
            //$amountInWords = $objAmount->convertAmountToWords($request->total_ttc);
        } else {
            $datas["total_ht"] = $request->total;
            $amountInWords = $objAmount->convertAmountToWords($request->total);
        }

        $data = $obj->StoreService($datas);
        if ($data) {

            if ($dtSms->sms == "OUI" && $datas['contact'] != "") {

                $msg = explode('[numero]', $dtSms->msgAchat);
                $message = $msg[0] . $datas['clientId'] . $msg[1];
                $message = explode('[operation]', $message);
                $message = $message[0] . 'service' . $message[1];

                Http::post('https://testapi.skillcodiing.com/api/sms/v1.0/sendSms', [
                    'email' => $dtSms->email,
                    'password' => $dtSms->password,
                    'phoneNumber' => $datas['contact'],
                    'senderName' => $dtSms->senderName,
                    'message' => $message,
                ]);

            }


            if ($request->saved == "SAVED") {
                return back()->with("succes", "Enregistrement effectué avec succès");
            }
            $datass = [
                'nom' => $datas['nom'],
                'clientId' => $datas['clientId'],
                'contact' => $datas['contact'],
                'prodName' => $request->titre,
                'montantPay' => $datas['montantPay'],
                'montant' => $datas['montant'],
                'total' => $datas['total'],
                'restant' => $datas['restant'],
                'date_hr' => $this->FormatDate(Carbon::now()) . " à " . $this->FormatHour(Carbon::now()),
                "username" => $datas["done_by"],
                "qte" => $datas["qte"],
                "operation" => "First",

                'total_ht' => $request->total,
                'total_tva' => $request->total_tva,
                'total_ttc' => $request->total_ttc,
                'tva' => $request->tva_id,
                'amountInWords' => $amountInWords
            ];
            $pdf = Pdf::loadView('pdf.services', $datass);
            return $pdf->download("services_" . $datas['clientId'] . ".pdf");
        }
        return back()->with("error", "Service n'a pas été ajoutée!");
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
        $data = Service::find($id);
        $datas = ProductBoutigue::where('id_boutique', $boutiqueId)->get();
        return view("service.edit", compact("data", "datas"));
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

        if ($request->types == "SERVICE") {
            $datas = $request->validate([
                "montantPay" => ['required', 'numeric']
            ]);
            $data = Service::find($id);

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

            $obj = new Service();
            $dt = $obj->StoreService($datas);

            if ($dt) {
                if ($request->option == "SERVICE") {
                    $obj = new Service();
                    $datas = Service::where('clientId', $request->clientId)->latest()->get();
                    $data = Service::where('clientId', $request->clientId)->first();
                    //dd($data->titre);

                    if ($data->tva != "") {
                        $amountInWords = $objAmount->convertAmountToWords($data->total_ttc);
                    } else {
                        $amountInWords = $objAmount->convertAmountToWords($data->total_ht);
                    }

                    $datass = [
                        'nom' => $data->nom,
                        'clientId' => $data->clientId,
                        'contact' => $data->contact,
                        'datas' => $datas,
                        'prodName' => $data->titre,
                        'restant' => $data->restant,
                        'date_hr' => $data->created_at,
                        "operation" => $request->option,
                        'montantPay' => $data->montantPay,
                        'montant' => $data->montant,
                        'total' => $data->total,
                        "username" => $data->done_by,
                        "qte" => $data->qte,

                        'total_ht' => $data->total_ht,
                        'total_tva' => $data->total_tva,
                        'total_ttc' => $data->total_ttc,
                        'tva' => $data->tva,
                        'amountInWords' => $amountInWords

                    ];
                    $pdf = PDF::loadView('pdf.services', $datass);
                    return $pdf->download("service_update_" . $data->clientId . ".pdf");
                }
                return back()->with("succes", "Mise à jour effectuée.");
            }
            return back()->with("error", "Mise à jour non effectuée.");
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
        $obj = new Service();
        $data = $obj->deleteService($id);
        if ($data) {
            return back()->with("succes", "Supression reussie");
        }
        return back()->with("error", "Supression non reussie");
    }

    public function getproduct($id)
    {
        $data = ProductBoutigue::where('id', $id)->get();
        return response()->json($data);

    }

    public function PrintInvoice(Request $request)
    {
        $objAmount = new CurrencyConverterController();
        $amountInWords = 0;

        if ($request->option == "SERVICE") {
            $obj = new Service();
            $datas = Service::where('clientId', $request->clientId)->latest()->get();
            $data = Service::where('clientId', $request->clientId)->first();
            //dd($data->titre);

            if ($data->tva != "") {
                $amountInWords = $objAmount->convertAmountToWords($data->total_ttc);
            } else {
                $amountInWords = $objAmount->convertAmountToWords($data->total_ht);
            }

            $datass = [
                'nom' => $data->nom,
                'clientId' => $data->clientId,
                'contact' => $data->contact,
                'datas' => $datas,
                'prodName' => $data->titre,
                'restant' => $data->restant,
                'date_hr' => $data->created_at,
                "operation" => $request->option,
                'montantPay' => $data->montantPay,
                'montant' => $data->montant,
                'total' => $data->total,
                "username" => $data->done_by,
                "qte" => $data->qte,

                'total_ht' => $data->total_ht,
                'total_tva' => $data->total_tva,
                'total_ttc' => $data->total_ttc,
                'tva' => $data->tva,
                'amountInWords' => $amountInWords

            ];
            $pdf = PDF::loadView('pdf.services', $datass);
            return $pdf->download("service_after_" . $data->clientId . ".pdf");
        }


    }

    public function Recharche(Request $request)
    {

        $boutiqueId = auth()->user()->id_boutigue ?? session('selected_boutique_id');
        $obj = new Service();
        $datas = $obj->getAll();
        $totalT = Service::whereMonth('created_at', Carbon::now()->month)
            ->where('id_boutique', $boutiqueId)
            ->sum("total");
        $totalP = Service::whereMonth('created_at', Carbon::now()->month)
            ->where('id_boutique', $boutiqueId)
            ->sum("montantPay");
        $totalR = Service::whereMonth('created_at', Carbon::now()->month)
            ->where('id_boutique', $boutiqueId)
            ->sum("restant");
        $totalQ = Service::whereDate('created_at', Carbon::today())
            ->where('id_boutique', $boutiqueId)
            ->sum("qte");

        $clients = Service::where('id_boutique', auth()->user()->id_setting)->latest()->get();
        $prods = ProductBoutigue::where('id_boutique', auth()->user()->id_setting)->latest()->get();

        if ($request->types == "VENTES") {
            $operation = "VENTE";
            $desc = "Rapport de vente";

            if (!empty($request->dateDebut) && !empty($request->dateFin) && !empty($request->id_prod) && !empty($request->username)) {
                $datas = Service::whereDate('created_at', '>=', $request->dateDebut)
                    ->whereDate('created_at', '<=', $request->dateFin)
                    ->where('username', $request->username)
                    ->where('id_prod', $request->id_prod)
                    ->where('id_setting', auth()->user()->id_setting)
                    ->get();


                $totalT = Service::whereDate('created_at', '>=', $request->dateDebut)
                    ->whereDate('created_at', '<=', $request->dateFin)
                    ->where('username', $request->username)
                    ->where('id_prod', $request->id_prod)
                    ->where('id_setting', auth()->user()->id_setting)
                    ->sum("total");


                $totalQ = Service::whereDate('created_at', '>=', $request->dateDebut)
                    ->whereDate('created_at', '<=', $request->dateFin)
                    ->where('username', $request->username)
                    ->where('id_prod', $request->id_prod)
                    ->where('id_setting', auth()->user()->id_setting)
                    ->sum("qte");

                $totalP = Service::whereDate('created_at', '>=', $request->dateDebut)
                    ->whereDate('created_at', '<=', $request->dateFin)
                    ->where('username', $request->username)
                    ->where('id_prod', $request->id_prod)
                    ->where('id_setting', auth()->user()->id_setting)
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
                $datas = Service::whereDate('created_at', '>=', $request->dateDebut)
                    ->whereDate('created_at', '<=', $request->dateFin)
                    ->where('id_prod', $request->id_prod)
                    ->where('id_setting', auth()->user()->id_setting)
                    ->get();


                $totalT = Service::whereDate('created_at', '>=', $request->dateDebut)
                    ->whereDate('created_at', '<=', $request->dateFin)
                    ->where('id_prod', $request->id_prod)
                    ->where('id_setting', auth()->user()->id_setting)
                    ->sum("total");


                $totalP = Service::whereDate('created_at', '>=', $request->dateDebut)
                    ->whereDate('created_at', '<=', $request->dateFin)
                    ->where('id_prod', $request->id_prod)
                    ->where('id_setting', auth()->user()->id_setting)
                    ->distinct()
                    ->sum("reduction");

                $totalQ = Service::whereDate('created_at', '>=', $request->dateDebut)
                    ->whereDate('created_at', '<=', $request->dateFin)
                    ->where('id_prod', $request->id_prod)
                    ->where('id_setting', auth()->user()->id_setting)
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
                $datas = Service::where('username', $request->username)
                    ->where('id_prod', $request->id_prod)
                    ->where('id_setting', auth()->user()->id_setting)
                    ->get();


                $totalT = Service::where('username', $request->username)
                    ->where('id_prod', $request->id_prod)
                    ->where('id_setting', auth()->user()->id_setting)
                    ->sum("montant");


                $totalP = Service::where('username', $request->username)
                    ->where('id_prod', $request->id_prod)
                    ->where('id_setting', auth()->user()->id_setting)
                    ->distinct()
                    ->sum("reduction");

                $totalQ = Service::where('username', $request->username)
                    ->where('id_prod', $request->id_prod)
                    ->where('id_setting', auth()->user()->id_setting)
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
                $datas = Service::whereDate('created_at', '>=', $request->dateDebut)
                    ->whereDate('created_at', '<=', $request->dateFin)
                    ->where('username', $request->username)
                    ->where('id_setting', auth()->user()->id_setting)
                    ->get();


                $totalT = Service::whereDate('created_at', '>=', $request->dateDebut)
                    ->whereDate('created_at', '<=', $request->dateFin)
                    ->where('username', $request->username)
                    ->where('id_setting', auth()->user()->id_setting)
                    ->sum("total");

                $totalQ = Service::whereDate('created_at', '>=', $request->dateDebut)
                    ->whereDate('created_at', '<=', $request->dateFin)
                    ->where('username', $request->username)
                    ->where('id_setting', auth()->user()->id_setting)
                    ->sum("qte");

                $totalP = Service::whereDate('created_at', '>=', $request->dateDebut)
                    ->whereDate('created_at', '<=', $request->dateFin)
                    ->where('username', $request->username)
                    ->where('id_setting', auth()->user()->id_setting)
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
                $datas = Service::whereDate('created_at', '>=', $request->dateDebut)
                    ->whereDate('created_at', '<=', $request->dateFin)
                    ->where('id_setting', auth()->user()->id_setting)
                    ->get();


                $totalM = Service::whereDate('created_at', '>=', $request->dateDebut)
                    ->whereDate('created_at', '<=', $request->dateFin)
                    ->where('id_setting', auth()->user()->id_setting)
                    ->sum("montant");


                $totalV = Service::whereDate('created_at', '>=', $request->dateDebut)
                    ->whereDate('created_at', '<=', $request->dateFin)
                    ->where('id_setting', auth()->user()->id_setting)
                    ->sum("qte");

                $totalR = Service::whereDate('created_at', '>=', $request->dateDebut)
                    ->whereDate('created_at', '<=', $request->dateFin)
                    ->where('id_setting', auth()->user()->id_setting)
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
                $datas = Service::where('username', $request->username)
                    ->where('id_setting', auth()->user()->id_setting)
                    ->get();


                $totalR = Service::Where('username', $request->username)
                    ->where('id_setting', auth()->user()->id_setting)
                    ->distinct()
                    ->sum("reduction");

                $totalM = Service::Where('username', $request->username)
                    ->where('id_setting', auth()->user()->id_setting)
                    ->sum("montant");

                $totalV = Service::Where('username', $request->username)
                    ->where('id_setting', auth()->user()->id_setting)
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
                $datas = Service::where('id_prod', $request->id_prod)
                    ->where('id_setting', auth()->user()->id_setting)
                    ->get();

                $totalM = Service::where('id_prod', $request->id_prod)
                    ->where('id_setting', auth()->user()->id_setting)
                    ->sum("montant");

                $totalR = Service::where('id_prod', $request->id_prod)
                    ->where('id_setting', auth()->user()->id_setting)
                    ->distinct()
                    ->sum("reduction");

                $totalV = Service::where('id_prod', $request->id_prod)
                    ->where('id_setting', auth()->user()->id_setting)
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

            return view("service.index", compact("datas", "prods", "totalQ", "totalT", "totalR", "totalP", "clients"));
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

    public function AddMiniService(Request $request)
    {
        $request->validate([
            'nom_service' => ['required'],
            'montant' => ['required']
        ]);
        $datas = $request->all();
        $datas['id_setting'] = auth()->user()->id_setting;

        $data = (new MiniService)->StoreMiniService($datas);
        if ($data) {
            return back()->with("succes", "Enregistrement effectué avec succès");
        }
        return back()->with("error", "Enregistrement n'a pas été effectué!");
    }

    public function getMontant($id)
    {
        $data = MiniService::where('id', $id)->where('id_setting', auth()->user()->id_setting)->get();
        return response()->json($data);

    }
}