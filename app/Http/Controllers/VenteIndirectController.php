<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\EntreSortieStock;
use App\Models\Produit;
use App\Models\settings;
use App\Models\Stock;
use App\Models\VenteIndirect;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

class VenteIndirectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $boutiqueId = auth()->user()->id_boutigue ?? session('selected_boutique_id');
        $obj = new VenteIndirect();
        $datas = $obj->getAll();
        $totalQ = 0;
        $totalT = 0;
        foreach ($datas as $key => $data) {
            $totalQ += $data->qte;
            if ($data->tva == "") {
                $totalT += $data->total_ht;
            } else {
                $totalT += $data->total_ttc;
            }
        }
        return view("venteIndirect.index", compact("datas", "totalQ", "totalT"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $boutiqueId = auth()->user()->id_boutigue ?? session('selected_boutique_id');
        $pros = Stock::where('id_setting', auth()->user()->id_setting)->latest()->get();
        $clients = Client::where('id_setting', auth()->user()->id_setting)->latest()->get();
        return view("venteIndirect.create", compact("pros", "clients"));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $boutiqueId = auth()->user()->id_boutigue ?? session('selected_boutique_id');
        //dd($request->all());
        $dtSms = settings::where('id', auth()->user()->id_setting)->first();
        $objAmount = new CurrencyConverterController();
        $amountInWords = 0;

        $datas = $request->validate([
            "nom" => ['required'],
            "produit" => ['required'],
            "qte" => ['required'],
            "montant" => ['required', "numeric"],
            "total_ht" => ['required', "numeric"],
            "prix_init" => ['required', "numeric"],
            "montantPay" => ['required', "numeric"]
        ]);

        $obj = new VenteIndirect();
        $datas["done_by"] = auth()->user()->name;
        $datas["descs"] = $request->descs;
        $datas["clientId"] = $obj->generatePayId();
        $datas["id_setting"] = auth()->user()->id_setting;
        $data['id_boutique'] = $boutiqueId;

        if (!empty($request->nom)) {
            $contact = explode(';', $request->nom);
            $datas['nom'] = $contact[1];
            $datas['contact'] = $contact[0];
        } else {
            $datas['nom'] = '';
            $datas['contact'] = '';
        }


        if ($request->tva_id != '') {
            $datas["tva"] = $request->tva_id;
            $datas["total_tva"] = $request->total_tva;
            $datas["total_ttc"] = $request->total_ttc;
            $datas["total_ht"] = $request->total_ht;
            $amountInWords = $objAmount->convertAmountToWords($request->total_ttc);
        } else {
            $datas["total_ht"] = $request->total_ht;
            $amountInWords = $objAmount->convertAmountToWords($request->total_ht);
        }

        $data = $obj->StoreVenteIndirect($datas);
        if ($data) {
            $dts['produit'] = $datas['produit'];
            $dts['user_name'] = auth()->user()->name;
            $dts['id_setting'] = $datas['id_setting'];
            $dts['qte'] = $datas['qte'];
            $dts['qte_en_stock'] = "Aucun";
            $dts['num_charge'] = $datas['clientId'];
            $dts['operation'] = "Sortir";
            $dts['service'] = "Vente Indirect";
            $dts['id_boutique'] = $boutiqueId;
            (new EntreSortieStock)->StoreEntreSortieStock($dts);

            // if ($dtSms->sms == "OUI" && $datas['contact'] != "") {

            //     $msg = explode('[numero]', $dtSms->msgAchat);
            //     $message = $msg[0] . $datas['clientId'] . $msg[1];
            //     $message = explode('[operation]', $message);
            //     $message = $message[0] . "Achat" . $message[1];

                /*
                Http::post('https://testapi.skillcodiing.com/api/sms/v1.0/sendSms', [
                    'email' => $dtSms->email,
                    'password' => $dtSms->password,
                    'phoneNumber' => $datas['contact'],
                    'senderName' => $dtSms->senderName,
                    'message' => $message,
                ]);
                */

            // }


            if ($request->print == "PRINT") {
                $datass = [
                    /*
                    'nom' => $datas['nom'],
                    'clientId' => $datas['clientId'],
                    'contact' => $datas['contact'],
                    'prodName' => $request->produit,
                    'montantPay' => $datas['montantPay'],
                    'montant' => $datas['montant'],
                    'total' => $datas['total'],
                    'prix_init' => $datas['prix_init'],
                    'date_hr' => $obj->FormatDate(Carbon::now()) . " à " . $obj->FormatHour(Carbon::now()),
                    "username" => $datas["done_by"],
                    "qte" => $datas["qte"],
                    "operation" => "First",
                    'amountInWords' => $amountInWords,
                    */


                    'nom' => $datas['nom'],
                    'clientId' => $datas['clientId'],
                    'contact' => $datas['contact'],
                    'prodName' => $request->produit,
                    'montantPay' => $datas['montantPay'],
                    'montant' => $datas['montant'],

                    'total_ht' => $request->total_ht,
                    'total_tva' => $request->total_tva,
                    'total_ttc' => $request->total_ttc,
                    'tva' => $request->tva_id,
                    'amountInWords' => $amountInWords,

                    'date_hr' => $this->FormatDate(Carbon::now()) . " à " . $this->FormatHour(Carbon::now()),
                    "username" => $datas["done_by"],
                    "qte" => $datas["qte"],
                    "operation" => "First",
                    "descs" => "Achat",
                ];
                $pdf = PDF::loadView('pdf.paymentAvance', $datass);
                return $pdf->download("payment_advance_" . $datas['clientId'] . ".pdf");
            }
            return back()->with("succes", "Enregistrement effectué avec succès");
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
        $data = VenteIndirect::find($id);
        return view('venteIndirect.edit', compact('data'));
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
        $datas = $request->validate([
            "nom" => ['required'],
            "contact" => ['required', "numeric"],
            "produit" => ['required'],
            "qte" => ['required'],
            "montant" => ['required', "numeric"],
            "total" => ['required', "numeric"],
            "prix_init" => ['required', "numeric"],
            "montantPay" => ['required', "numeric"]
        ]);

        $obj = new VenteIndirect();
        $datas["done_by"] = auth()->user()->name;
        $datas["descs"] = $request->descs;
        $data = $obj->updateVenteIndirect($id, $datas);
        $dataFromDb = VenteIndirect::find($id);

        if ($data) {

            if ($dataFromDb->tva_id != '') {
                $amountInWords = $objAmount->convertAmountToWords($request->total_ttc);
                $datass = [
                    'nom' => $datas['nom'],
                    'clientId' => $request->clientId,
                    'contact' => $datas['contact'],
                    'prodName' => $request->produit,
                    'montantPay' => $datas['montantPay'],
                    'montant' => $datas['montant'],
                    'total' => $datas['total'],
                    'prix_init' => $datas['prix_init'],
                    'date_hr' => $obj->FormatDate(Carbon::now()) . " à " . $obj->FormatHour(Carbon::now()),
                    "username" => $datas["done_by"],
                    "qte" => $datas["qte"],
                    "operation" => "First",

                    'total_tva' => $dataFromDb->total_tva,
                    'tva' => $dataFromDb->tva_id,
                    'total_ttc' => $dataFromDb->total_ttc,
                    'total_ht' => $dataFromDb->total_ht,
                    'amountInWords' => $amountInWords,
                ];
            } else {
                $datass = [
                    'nom' => $datas['nom'],
                    'clientId' => $request->clientId,
                    'contact' => $datas['contact'],
                    'prodName' => $request->produit,
                    'montantPay' => $datas['montantPay'],
                    'montant' => $datas['montant'],
                    'total' => $datas['total'],
                    'prix_init' => $datas['prix_init'],
                    'date_hr' => $obj->FormatDate(Carbon::now()) . " à " . $obj->FormatHour(Carbon::now()),
                    "username" => $datas["done_by"],
                    "qte" => $datas["qte"],
                    "operation" => "First",

                    'total_tva' => '',
                    'tva' => '',
                    'total_ttc' => '',
                    'total_ht' => $dataFromDb->total_ht,
                    'amountInWords' => $objAmount->convertAmountToWords($request->total_ht),
                ];
            }


            $pdf = PDF::loadView('pdf.paymentAvance', $datass);
            return $pdf->download("payment_advance_" . $request->clientId . ".pdf");
            //return back()->with("succes","Enregistrement effectué avec succès");
        }
        return back()->with("error", "Catégorie n'a pas été ajoutée!");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $obj = new VenteIndirect();
        $data = $obj->deleteVenteIndirect($id);
        if ($data) {
            return back()->with("succes", "Supression reussie");
        }
        return back()->with("error", "Supression non reussie");

    }
    public function PrintInvoiceVente(Request $request)
    {
        $obj = new VenteIndirect();
        $objAmount = new CurrencyConverterController();
        $amountInWords = 0;
        if ($request->option == "VENTE_INDIRECT") {
            //$datas = VenteIndirect::where('clientId', $request->clientId)->get();
            $datas = VenteIndirect::where('clientId', $request->clientId)->first();

            $amountInWords = $objAmount->convertAmountToWords($datas['montant']);

            $datass = [

                    'clientId' => $datas['clientId'],

                    'total_ht' => $datas['total_ht'],
                    'total_tva' => $datas['total_tva'],
                    'total_ttc' => $datas['total_ttc'],
                    'tva' => $datas['tva_id'],
                    'amountInWords' => $amountInWords,

                    "descs" => "Achat",

                'nom' => $datas['nom'],
                'contact' => $datas['contact'],
                'prodName' => $datas['produit'],
                'montantPay' => $datas['montantPay'],
                'montant' => $datas['montant'],
                'prix_init' => $datas['prix_init'],
                'date_hr' => $obj->FormatDate(Carbon::now()) . " à " . $obj->FormatHour(Carbon::now()),
                "username" => $datas["done_by"],
                "qte" => $datas["qte"],
                "operation" => "First",
            ];
            // dd($datass);
            $pdf = PDF::loadView('pdf.paymentAvance', $datass);
            return $pdf->download("payment_advance_" . $request->clientId . ".pdf");
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

}