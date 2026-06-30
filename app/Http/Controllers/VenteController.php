<?php

namespace App\Http\Controllers;

use App\Models\Categorie;
use App\Models\Client;
use App\Models\ClientMouvement;
use App\Models\Dette;
use App\Models\Entrepot;
use App\Models\EntreSortieStock;
use App\Models\PaiementAvance;
use App\Models\ProductBoutigue;
use App\Models\Boutique;
use App\Models\settings;
use App\Models\VenteDetail;
use App\Models\User;
use App\Models\Vente;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use App\Models\WalletTransaction;

class VenteController extends Controller
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
        $boutiqueId = auth()->user()->id_boutigue ?? session('selected_boutique_id');
        $totalR = 0;
        $total_ht = 0;
        $total_ttc = 0;
        $totalV = 0;
        $totalM = 0;

        if (auth()->user()->roles == "Admin" || auth()->user()->roles == "Super Admin") {
            $datas = Vente::whereDate('created_at', Carbon::today())->where('id_setting', auth()->user()->id_setting)
                ->where("id_boutique", $boutiqueId)
                ->get();

            // $datas = Vente::where('id_setting', auth()->user()->id_setting)
            //     ->where("id_boutique", $boutiqueId)
            //     ->get();

            $users = auth()->user()->roles == "Super Admin" ?
                User::where('id', '!=', '1')->where('id_setting', auth()->user()->id_setting)->get() :
                User::where('id', '!=', '1')->where('id_setting', auth()->user()->id_setting)
                    ->where("id_boutigue", $boutiqueId)
                    ->get();

            foreach ($datas as $dt) {
                $totalR += $dt->reduction;
                $totalV += $dt->quantite;
                if ($dt->tva == 0) {
                    $total_ht += $dt->total_ht;
                } else {
                    $total_ttc += $dt->total_ttc;
                }
            }
            $totalM = ($total_ht + $total_ttc);
            return view("vente.index", compact("datas", "cats", "totalR", "totalM", "totalV", "users"));
        }

        $datas = Vente::whereDate('created_at', Carbon::today())
            ->where("username", auth()->user()->id)
            ->where("id_boutique", $boutiqueId)
            ->get();
        $users = User::where('id', '!=', '1')->where('id_setting', auth()->user()->id_setting)->get();

        foreach ($datas as $dt) {
            $totalR += $dt->reduction;
            $totalV += $dt->quantite;
            if ($dt->tva == 0) {
                $total_ht += $dt->total_ht;
            } else {
                $total_ttc += $dt->total_ttc;
            }
        }
        $totalM = ($total_ht + $total_ttc);

        return view("vente.index", compact("datas", "cats", "totalR", "totalM", "totalV", "users"));
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
        $codebar = settings::where('id', auth()->user()->id_setting)->latest()->first();
        $check = $codebar->bar_option ?? 'NON';

        $datas = ProductBoutigue::where("stock_id", $magasin->id)->latest()->get();
        $clients = Client::where('id_setting', auth()->user()->id_setting)->latest()->get();
        return view("vente.create", compact("datas", "clients", "check", "magasins", "magasin"));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store_old(Request $request)
    {
        $request->validate([
            "total_ht" => ["required"],
            "montantDonner" => ["required"],
            "restant" => ["required"],
        ]);

        //dd($request->all());

        /*
        "types" => "VENTES"
        "total_ht" => "27500"
        "tva" => null
        "total_tva" => null
        "total_ttc" => null
        "reduction" => null
        "montantDonner" => "10000"
        "restant" => "17500"
        "nom" => "3;88558888;KEITA"
        "opp" => "VENTES"
        "valider" => "valider"
        */

        $boutiqueId = auth()->user()->id_boutigue ?? session('selected_boutique_id');
        $dtSms = settings::find(auth()->user()->id_setting);
        // $message = "{$dtSms->app_name}, Merci pour votre confiance. Voici le lien de la facture: " . route('facture_vente.client', 1);
        $objAmount = new CurrencyConverterController();
        $amountInWords = 0;
        $dts['id_setting'] = auth()->user()->id_setting;
        $dts['id_boutique'] = $boutiqueId;

        if ($request->opp == "VENTES") {
            if (!$request->session()->has('cart') && !filled($request->session()->get('cart'))) {
                return back()->with("error", "Whoops : Votre panier est vide");
            }

            $desc = "Rapport de vente";
            $operation = "VENTE";

            $obj = new Vente();

            if (!empty($request->nom)) {
                $nom = explode(';', $request->nom);
                $nom_client = $nom['2'];
                $contact = $nom['1'];
                $client_id = $nom['0'];

                $nom = explode(';', $request->nom);
                $client_id = $nom[0];

                $clientData = Client::tenant()->findOrFail($client_id);


                /*
                |-----------------------------------
                | 1. CHECK IF CLIENT IS BLOCKED (VERY IMPORTANT)
                |-----------------------------------
                */
                if ($clientData->status === 'blocked') {
                    //dd($clientData);
                    return back()->with('error', 'Ce client est bloqué. Opération refusée.');
                }

            } else {
                $nom = '';
                $nom_client = '';
                $client_id = null;
            }
            //dd($client_id);
            $cart = session()->get('cart');
            try {
                DB::beginTransaction();

                if ($request->tva != '') {
                    $tva_val = [
                        "total_ht" => $request->total_ht,
                        "tva" => $request->tva,
                        "total_tva" => $request->total_tva,
                        "total_ttc" => $request->total_ttc,
                    ];
                    if (empty($client_id) && $request->montantDonner < $request->total_ttc) {

                        return back()->with(
                            'error',
                            'Client non sélectionné. Le paiement intégral de ' .
                            number_format($request->total_ttc, 0, ',', ' ') .
                            ' F CFA est requis.'
                        );
                    }
                    $amountInWords = $objAmount->convertAmountToWords($request->total_ttc);
                } else {
                    $tva_val = [
                        "tva" => 0,
                        "total_tva" => 0,
                        "total_ttc" => 0,
                        "total_ht" => $request->total_ht,
                    ];
                    if (empty($client_id) && $request->montantDonner < $request->total_ht) {

                        return back()->with(
                            'error',
                            'Client non sélectionné. Le paiement intégral de ' .
                            number_format($request->total_ttc, 0, ',', ' ') .
                            ' F CFA est requis.'
                        );
                    }

                    $amountInWords = $objAmount->convertAmountToWords($request->total_ht);
                }

                $datas = [
                    "num_vente" => $obj->generateNumVente('V'),
                    "restant" => $request->restant ?? 0,
                    "client_id" => $client_id,
                    "total_ht" => $tva_val['total_ht'],
                    "tva" => $tva_val['tva'],
                    "total_tva" => $tva_val['total_tva'],
                    "total_ttc" => $tva_val['total_ttc'],
                    "montantDonner" => $request->montantDonner,
                    "username" => auth()->user()->id,
                    "reduction" => $request->reduction ?? '0',
                    "id_setting" => auth()->user()->id_setting,
                    'id_boutique' => $boutiqueId,
                ];

                // dd($datas);
                $data = $obj->StoreVente($datas);
                if ($data) {
                    foreach ($cart as $cart_item) {
                        VenteDetail::create([
                            "vente_id" => $data->id,
                            "id_prod" => $cart_item['prod'],
                            "options" => $cart_item['options'],
                            "prix" => $cart_item['prix'],
                            "quantite" => $cart_item['qte'],
                            "montant" => $cart_item['total'],
                            "categorie" => $cart_item['id_categorie'],
                            "stock_id" => $cart_item['stock_id'],
                            "client_id" => $client_id ?? null,
                        ]);

                        (new EntreSortieStock)->StoreEntreSortieStock([
                            "produit" => $cart_item['produit'],
                            "id_prod" => $cart_item['prod'],
                            "user_name" => auth()->user()->id,
                            "qte_en_stock" => (int) (new ProductBoutigue())->get_prodQte($cart_item['prod']),
                            "qte" => $cart_item['qte'],
                            'service' => 'Vente',
                            'operation' => 'Sortir',
                            'stock_id' => $cart_item['stock_id'],
                            'id_setting' => auth()->user()->id_setting,
                            "id_boutique" => $boutiqueId,
                        ]);
                    }


                    if ($dtSms->sms == "OUI" && $contact != "") {
                        //"verifier" => "on"
                        // $msg = explode('[numero]', $dtSms->msgAchat);
                        // $message = $msg[0] . $datas['num_vente'] . $msg[1];
                        // $message = explode('[operation]', $message);
                        // $message = $message[0] . 'achat' . $message[1];
                        $message = "{$dtSms->app_name}, Merci pour votre confiance. Voici le lien de la facture: " . route('facture_vente.client', $data->id);

                        Http::post(self::SMS_API_LINK, [
                            'email' => $dtSms->email,
                            'password' => $dtSms->password,
                            'phoneNumber' => $contact,
                            'senderName' => $dtSms->senderName,
                            'message' => $message,
                        ]);
                    }

                    if (!empty($request->nom)) {
                        $nom = explode(';', $request->nom);
                        $client_id = $nom['0'];
                        $clientData = Client::tenant()->findOrfail($client_id);

                        // Determine the type of movement based on the payment
                        $total = $request->total_ht ? $request->total_ht : $request->total_ttc;
                        $paid = $request->montantDonner;

                        if ($paid >= $total) {

                            $type_mouvement = 'achat_cash';
                            $remaining = 0;
                            $credit = 0;

                        } elseif ($paid > 0) {

                            $type_mouvement = 'achat_credit';
                            $remaining = $total - $paid;
                            $credit = $remaining;

                        } else {

                            $type_mouvement = 'achat_credit';
                            $remaining = $total;
                            $credit = $total;

                        }

                        //I want to do it here 
                        $clientData->update([
                            'wallet_balance' => wallllet_balance,
                            'credit_used' => credit_used
                        ]);

                        ClientMouvement::create([
                            'num_mouvement' => (new ClientMouvement())->numMouvement(),
                            'client_id' => $client_id,
                            'type_mouvement' => $type_mouvement,
                            'total' => $total,
                            'montant_payer' => $paid,
                            'montant_credit' => $credit,
                            'montant_restant' => $remaining,
                            'id_setting' => $dts['id_setting'],
                        ]);
                    }

                    if ($request->valider == "print") {
                        $client = $client_id ? Client::find($client_id) : null;
                        $user = User::find(auth()->user()->id);

                        $tabNum = explode('-', $datas['num_vente']);
                        $num_liv = 'B-' . $tabNum[1] . '-' . $tabNum[2];

                        $datass = [
                            'nom' => $client ? $client->nom : '',
                            'num_vente' => $datas['num_vente'],
                            'num_liv' => $num_liv,
                            'contact' => $client ? $client->contact : '',
                            'carts' => $cart,
                            'total_ht' => $request->total_ht,
                            'total_tva' => $request->total_tva,
                            'total_ttc' => $request->total_ttc,
                            'tva' => $request->tva,
                            'amountInWords' => $amountInWords,
                            'montantApayer' => $datas['total_ht'],
                            'montantDonner' => $datas['montantDonner'],
                            'restant' => $datas['restant'],
                            'date_hr' => Carbon::now(),
                            "username" => $user ? $user->name : '',
                            "reduction" => $datas['reduction'],
                            "operation" => $operation,
                            "operation2" => $operation,
                            "desc" => $desc,
                        ];

                        // Set $paperSize based on user input or any other source (e.g., form data)
                        $paperSize = $dtSms->address; // Set this dynamically as required

                        // Load the PDF view with data
                        $pdf = PDF::loadView('pdf.invoice', $datass);

                        // Set the paper size based on the $paperSize value
                        switch ($paperSize) {
                            case 'A5':
                                $pdf->setPaper('a5'); // Standard A5
                                break;
                            case 'A6':
                                $pdf->setPaper([0, 0, 298, 420]); // Custom dimensions for A6 in points
                                break;
                            case 'A7':
                                $pdf->setPaper([0, 0, 210, 298]); // Custom dimensions for A7 in points
                                break;
                            case 'A8':
                                $pdf->setPaper([0, 0, 148, 210]); // Custom dimensions for A8 in points
                                break;
                            default:
                                $pdf->setPaper('a4'); // Default to A4 if no size is specified
                                break;
                        }

                        // Flash a success message
                        session()->flash('success', 'Achat a été effectué');
                        DB::commit();
                        // Download the PDF, with the client ID in the filename
                        return $pdf->download("Facture_vente_&_Bon_livraison_n" . $datas['num_vente'] . ".pdf");
                    }
                }
                session()->forget('cart');
                session('cart', []);
                DB::commit();
                return back()->with("succes", "Enregistrement effectué avec succès.");
            } catch (\Throwable $e) {
                DB::rollBack();
                return back()->with(
                    'error',
                    "Une erreur est survenue. Veuillez réessayer.|" . $e
                );
            }
        } else if ($request->opp == "PROFORMA") {
            if (!$request->session()->has('cart') && !filled($request->session()->get('cart'))) {
                return back()->with("error", "Whoops : Votre panier est vide");
            }
            $obj = new Vente();
            $datas['num_vente'] = $obj->generateNumVente('PRO');
            $datas['reduction'] = $request->reduction ?? '0';
            $datas['id_setting'] = $dts['id_setting'];
            $datas['id_boutique'] = $boutiqueId;
            $datas['user_id'] = Auth::user()->id;

            if (!empty($request->nom)) {
                $nom = explode(';', $request->nom);
                $nom_client = $nom['2'];
                $contact = $nom['1'];
                $client_id = $nom['0'];
            } else {
                $nom = '';
                $nom_client = '';
                $client_id = null;
            }

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

            $cart = session()->get('cart');
            $desc = "Proformant ";
            $operation = "PROFORMANT";

            $client = $client_id ? Client::find($client_id) : null;
            $user = $datas["user_id"] ? User::find($datas["user_id"]) : null;

            $datass = [
                'nom' => $client ? $client->nom : '',
                'num_vente' => $datas['num_vente'],
                'contact' => $client ? $client->contact : '',
                'carts' => $cart,
                'total_ht' => $request->total_ht,
                'total_tva' => $request->total_tva,
                'total_ttc' => $request->total_ttc,
                'tva' => $request->tva,
                'amountInWords' => $amountInWords,
                'montantApayer' => $request->total_ht,
                'montantDonner' => $request->montantDonner,
                'restant' => $request->restant ?? 0,
                'date_hr' => Carbon::now(),
                "username" => $user ? $user->name : '',
                "reduction" => $datas['reduction'],
                "operation" => $operation,
                "operation2" => $operation,
                "desc" => $desc,
            ];


            $pdf = PDF::loadView('pdf.proforma', $datass);
            session()->flash('succes', 'Proforma');
            return $pdf->stream("Proforma_" . $datas['num_vente'] . ".pdf");
        }
        return back()->with("error", "Enregistrement non effectué!");
    }

    public function store(Request $request)
    {

        //dd($request->all());
        $request->validate([
            "total_ht" => ["required"],
            "montantDonner" => ["required"],
            "restant" => ["required"],
            "opp" => ["required"],
        ]);

        $boutiqueId = auth()->user()->id_boutigue ?? session('selected_boutique_id');
        $settingId = auth()->user()->id_setting;

        $dtSms = settings::find($settingId);

        $cart = session()->get('cart');

        if (!$cart) {
            return back()->with("error", "Panier vide");
        }

        /*
        |-----------------------------------
        | CLIENT PARSING
        |-----------------------------------
        */
        $client_id = null;
        $clientData = null;

        $total = $request->total_ht ?? $request->total_ttc;
        $paid = $request->montantDonner ?? 0;
        $amountToRemoveFromAccount = 0;

        if (!empty($request->nom)) {
            $nom = explode(';', $request->nom);
            $client_id = $nom[0] ?? null;

            if ($client_id) {
                $clientData = Client::tenant()->findOrFail($client_id);

                if ($clientData->status === 'blocked') {
                    return back()->with('error', 'Client bloqué');
                }
            }
        } else {
            if ($paid < $total) {
                return back()->with(
                    'error',
                    'Client non sélectionné. Le paiement intégral de ' .
                    number_format($total, 0, ',', ' ') . config('app.cc') .
                    ' est requis.'
                );
            }

        }

        /*
        |-----------------------------------
        | AMOUNT CALCULATION
        |-----------------------------------
        */


        DB::beginTransaction();

        if (!empty($request->nom)) {
            $description = "Paiement du " . date('d/m/Y') .
                " - Client : " . ($clientData->nom ?? 'Non renseigné') .
                " - Montant : " . number_format($paid, 0, ',', ' ') . config('app.cc');
            ;
        } else {
            $description = "Paiement du " . date('d/m/Y') .
                " - Client : " . ('Non renseigné') .
                " - Montant : " . number_format($paid, 0, ',', ' ') . config('app.cc');
            ;
        }

        try {
            if ($paid >= $total) {
                $type = 'achat_cash';
                $credit = 0;
                $remaining = 0;

                if (!empty($request->nom)) {
                    WalletTransaction::ajouterOperation(
                        $client_id,
                        'paiement',
                        $paid,
                        $description
                    );
                }

            } elseif ($paid > 0) {
                $type = 'achat_credit';
                $credit = $total - $paid;
                $remaining = $credit;

                if (!empty($request->nom)) {
                    WalletTransaction::ajouterOperation(
                        $client_id,
                        'paiement',
                        $paid,
                        $description
                    );
                }
            } else {
                $type = 'achat_credit';
                $credit = $total;
                $remaining = $total;
            }

            /*
            |-----------------------------------
            | AMOUNT IN WORDS
            |-----------------------------------
            */
            $objAmount = new CurrencyConverterController();

            $totalForWords = $request->tva
                ? $request->total_ttc
                : $request->total_ht;

            $amountInWords = $objAmount->convertAmountToWords($totalForWords);



            $vente = new Vente();

            /*
            |-----------------------------------
            | PROFORMA (NO DB SAVE)
            |-----------------------------------
            */
            if ($request->opp === "PROFORMA") {

                $num = $vente->generateNumVente('PRO');

                if (!empty($request->nom)) {
                    $pdfData = $this->buildInvoiceData(
                        $num,
                        $clientData,
                        $request,
                        $cart,
                        $amountInWords,
                        'PROFORMA',
                        'Proforma'
                    );
                } else {
                    $pdfData = $this->buildInvoiceData(
                        $num,
                        null,
                        $request,
                        $cart,
                        $amountInWords,
                        'PROFORMA',
                        'Proforma'
                    );
                }

                $pdf = $this->generatePdf('pdf.proforma', $pdfData, $dtSms->address ?? 'a4');

                session()->forget('cart');
                DB::commit();

                return $pdf->stream("Proforma_$num.pdf");
            }

            /*
            |-----------------------------------
            | VENTE (SAVE DATABASE)
            |-----------------------------------
            */

            $num = $vente->generateNumVente('V');

            $sale = $vente->StoreVente([
                "num_vente" => $num,
                "client_id" => $client_id ?? null,
                "total_ht" => $request->total_ht,
                "tva" => $request->tva ?? 0,
                "total_tva" => $request->total_tva ?? 0,
                "total_ttc" => $request->total_ttc ?? 0,
                "montantDonner" => $paid,
                "reduction" => $request->reduction ?? 0,
                "id_setting" => $settingId,
                "id_boutique" => $boutiqueId,
                "username" => auth()->id(),
                "restant" => $remaining,
            ]);

            /*
            |-----------------------------------
            | DETAILS + STOCK
            |-----------------------------------
            */
            foreach ($cart as $item) {

                VenteDetail::create([
                    "vente_id" => $sale->id,
                    "id_prod" => $item['prod'],
                    "prix" => $item['prix'],
                    "quantite" => $item['qte'],
                    "montant" => $item['total'],
                    "stock_id" => $item['stock_id'],
                    "client_id" => $client_id ?? null,
                ]);

                (new EntreSortieStock)->StoreEntreSortieStock([
                    "produit" => $item['produit'],
                    "id_prod" => $item['prod'],
                    "qte" => $item['qte'],
                    "service" => "Vente",
                    "operation" => "Sortir",
                    "stock_id" => $item['stock_id'],
                    "id_setting" => $settingId,
                    "id_boutique" => $boutiqueId,
                ]);


                dd($boutiqueId . ' pROD : ' . $item['prod'], );
                ProductBoutigue::updateStock(
                    $item['prod'],
                    $boutiqueId,
                    $item['qte'],
                    'subtract'
                );

            }

            /*
            |-----------------------------------
            | CLIENT UPDATE (CREDIT + WALLET)
            |-----------------------------------
            */
            if (!empty($request->nom) && $clientData) {

                //$clientData->wallet_balance -= $paid;
                //$clientData->credit_used += $credit;
                if ($credit > 0) {
                    if ($clientData->wallet_balance <= 0) {
                        $clientData->credit_used += $credit;
                    } elseif ($credit <= $clientData->wallet_balance) {
                        $clientData->wallet_balance -= $credit;
                    } else {
                        $remainingCredit = $credit - $clientData->wallet_balance;

                        $clientData->wallet_balance = 0;
                        $clientData->credit_used += $remainingCredit;
                    }
                }

                if ($clientData->credit_used > $clientData->credit_limit) {
                    $clientData->status = 'blocked';
                } else {
                    $clientData->status = 'active';
                }

                if ($clientData->wallet_balance < 0) {
                    $clientData->wallet_balance = 0;
                }

                $clientData->save();

                ClientMouvement::create([
                    'num_mouvement' => (new ClientMouvement())->numMouvement(),
                    'client_id' => $client_id,
                    'type_mouvement' => $type,
                    'total' => $total,
                    'montant_payer' => $paid,
                    'montant_credit' => $credit,
                    'montant_restant' => $remaining,
                    'id_setting' => $settingId,
                ]);


                if (
                    $request->boolean('verifier') &&
                    $dtSms &&
                    $dtSms->sms === 'OUI' &&
                    !empty($clientData->contact)
                ) {

                    $message = "{$dtSms->app_name}, Merci pour votre confiance. "
                        . "Voici le lien de la facture : "
                        . route('facture_vente.client', $sale->id);

                    Http::post(self::SMS_API_LINK, [
                        'email' => $dtSms->email,
                        'password' => $dtSms->password,
                        'phoneNumber' => $clientData->contact,
                        'senderName' => $dtSms->senderName,
                        'message' => $message,
                    ]);
                }

            }

            /*
            |-----------------------------------
            | PRINT (OPTIONAL)
            |-----------------------------------
            */
            if ($request->valider === "print") {

                $pdfData = $this->buildInvoiceData(
                    $num,
                    $clientData,
                    $request,
                    $cart,
                    $amountInWords,
                    'VENTE',
                    'Rapport de vente'
                );

                $path = 'pdf.invoice';

                if ($dtSms->address != "a4" || $dtSms->address != "A4") {
                    $path = 'pdf.a5_a8';
                }

                //dd($dtSms->address . ' Path : ' . $path);

                $pdf = $this->generatePdf($path, $pdfData, $dtSms->address ?? $dtSms->address);

                session()->flash('succes', 'Vente effectuée');
                DB::commit();

                return $pdf->download("Facture_vente_n{$num}.pdf");
            }

            session()->forget('cart');
            DB::commit();

            return back()->with("succes", "Vente enregistrée avec succès.");

        } catch (\Throwable $e) {

            DB::rollBack();

            return back()->with("error", "Erreur: " . $e->getMessage());
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
        $data = Vente::find($id);
        $details = VenteDetail::where('vente_id', $id)->get();
        if ($data) {
            return view('vente.show', compact('data', 'details'));
        } else {
            return back()->with('info', "L'information est introuvable");
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $datas = Vente::find($id);
        return view("vente.edit", compact("datas"));
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

        $request->validate([
            "qty" => ["required", "numeric"]
        ]);

        $desc = "Rapport de vente";
        $operation = "update";
        $operation2 = "VENTE";

        $datas = Vente::find($id);
        $clientId = $datas->clientId;
        $datas->montant = $request->prix * $request->qty;
        $datas->quantite = $request->qty;
        $datas->update();
        if ($datas) {
            if ($datas->tva == "") {
                $amountInWords = $objAmount->convertAmountToWords($datas->total_ht);
            } else {
                $amountInWords = $objAmount->convertAmountToWords($datas->total_ttc);
            }

            $dts = Vente::where("clientId", $clientId)->where('id_setting', auth()->user()->id_setting)->get();
            $datass = [
                'nom' => $datas['nom'],
                'clientId' => $datas['clientId'],
                'contact' => $datas['contact'],
                'carts' => $dts,

                'total_ht' => $datas['total_ht'],
                'total_tva' => $datas['total_tva'],
                'total_ttc' => $datas['total_ttc'],
                'tva' => $datas['tva'],

                'montantApayer' => $datas['total_ht'],
                'montantDonner' => $datas['montantDonner'],
                'restant' => $datas['restant'],
                'date_hr' => Carbon::now(),
                "username" => auth()->user()->name,
                "reduction" => $datas["reduction"],
                "operation" => $operation,
                "operation2" => $operation2,
                "amountInWords" => $amountInWords,
                "desc" => $desc,
            ];
            $pdf = PDF::loadView('pdf.invoice', $datass);
            //session()->flash('succes', 'Achat a ete affectue');
            return $pdf->download("invoice_" . $datas['clientId'] . ".pdf");

            return redirect()->route("vente.index")->with("succes", "Mise à jour effectuée avec succès.");
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
        $obj = new Vente();
        $qte = $obj->getOneProduct($id);
        //$qte=$obj->getOneProduct($id)->quantite;
        //dd($qte);

        $this->AddQte($id, $qte->quantite, '');
        $data = $obj->deleteVente($id);
        if ($data) {
            return back()->with("succes", "Supression reussie");
        }
        return back()->with("error", "Supression non reussie");
    }

    public function showValide($id)
    {
        $data = Vente::find($id);
        $vente_details = VenteDetail::where('vente_id', $id)->get();
        if ($data) {
            return view('vente.valide', compact('data', 'vente_details'));
        } else {
            return back()->with('info', "L'information est introuvable");
        }
    }

    public function valideDetail(Request $request, $id)
    {
        $vente_detail = VenteDetail::find($id);
        if ($vente_detail) {
            if ($vente_detail->valider == 'Non valider') {
                $product_boutique = ProductBoutigue::where('id_prod', $vente_detail->id_prod)->where('stock_id', $vente_detail->stock_id)->first();
                // dd($product_boutique->quantite, $vente_detail->quantite);
                if ($product_boutique->quantite >= $vente_detail->quantite) {
                    $product_boutique->quantite -= $vente_detail->quantite;
                    $vente_detail->valider = 'valider';
                    $type = 'succes';
                    $ms = "L'article est marqué retirer";
                    $product_boutique->save();
                    $vente_detail->save();
                    return back()->with($type, $ms);
                }
            } else {
                return back()->with('error', "Impossible d'annuler");
            }
        } else {
            return back()->with('info', "L'information est introuvable");
        }
    }

    public function UpdateAfterBuy(Request $request, $id, $clientId)
    {
        //dd("nnn");
        $obj = new Vente();
        $qte = $obj->getOneProduct($id)->quantite;

        $datas = Vente::where("clientId", $clientId)->where('id_setting', auth()->user()->id_setting)->get();
        foreach ($datas as $data) {
            $montApayer = $data->total_ht;
            $mont = $data->montant;
            $data->total_ht = $montApayer - $mont;
            $data->save();
        }

        $this->AddQte($id, $qte, '');
        $data = $obj->deleteVente($id);
        if ($data) {
            return back()->with("succes", "Supression reussie");
        }
        return back()->with("error", "Supression non reussie");
    }

    public function deleteAfterBuy($id)
    {
        /*
        $quantite = 0;
        $obj = new Vente();
        $datas = Vente::where("clientId", $clientId)->where('id_setting', auth()->user()->id_setting)->get();
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
            $dts['operation'] = "Vente Annuler";
            $dts['service'] = "Vente";
            $dts['id_prod'] = $qte->id_prod;
            (new EntreSortieStock)->StoreEntreSortieStock($dts);
        }

        $data = $obj->deleteVente($qte->id);
        */

        try {

            $vente = Vente::findOrFail($id);

            $vente->deleteVente();

            return back()->with(
                'succes',
                'Vente supprimée avec succès.'
            );

        } catch (\Exception $e) {

            return back()->with(
                'error',
                $e->getMessage()
            );
        }
    }

    public function deleteAfterBuyOne($id, $item)
    {
        try {

            $vente = Vente::findOrFail($id);
            $vente->deleteItemAndRestock($item);
            return back()->with(
                'succes',
                'Vente supprimée avec succès.'
            );

        } catch (\Exception $e) {

            return back()->with(
                'error',
                $e->getMessage()
            );
        }
    }

    public function autocomplete(Request $request)
    {

        $data = ProductBoutigue::select("nom_produit")
            ->where('id_setting', auth()->user()->id_setting)
            ->where("nom_produit", "LIKE", "%{$request->term}%")
            //->get();
            ->pluck('city');
        return response()->json($data);
    }

    public function fatcheStudentInfo($id)
    {
        $data = ProductBoutigue::where('id', $id)
            ->orWhere("code_barre", $id)
            ->get();
        return response()->json($data);
    }

    public function order($id)
    {
        $data = Vente::where('clientId', $id)->get();
        return response()->json($data);
    }

    public function removeCart()
    {
        $cart = session()->get('cart');
        session()->forget('cart');
        unset($cart);
    }


    public function Remove($idprod, $qteAchat, $typeQte)
    {
        //dd($idprod);
        $data = Vente::where("id_prod", $idprod)->latest('id')->first();
        $dataNew = ProductBoutigue::where('id_prod', $data->id_prod)->latest('id')->first();
        if (!empty($dataNew)) {
            $qteStock = $dataNew->quantite;
            $qte_total_en_detail = $dataNew->qte_total_en_detail;
            if ($typeQte == "QTEDETAIL") {
                $dataNew->qte_total_en_detail = $qte_total_en_detail - (int) $qteAchat;
            } else {
                $dataNew->quantite = $qteStock - (int) $qteAchat;
            }

            return $dataNew->update();
        }
        return;
    }



    public function AddQte($idprod, $qteAchat, $typeQte)
    {
        $data = Vente::where("id", $idprod)->first();
        $dataNew = ProductBoutigue::where('id_prod', $data->id_prod)->latest('id')->first();
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

    public function searchProduct(Request $request)
    {
        $boutiqueId = auth()->user()->id_boutigue ?? session('selected_boutique_id');
        $magasin = Entrepot::find($request->product_id);

        if (auth()->user()->roles === "Super Admin") {
            return redirect()->route('vente.index')->with("info", "Les Super Admin ne peuvent pas effectuer de ventes.");
        }
        $magasins = Entrepot::get();
        $codebar = settings::where('id', auth()->user()->id_setting)->latest()->first();
        $check = $codebar->bar_option ?? 'NON';

        $datas = ProductBoutigue::where("stock_id", $magasin->id)->latest()->get();
        $clients = Client::where('id_setting', auth()->user()->id_setting)->latest()->get();
        return view("vente.create", compact("datas", "clients", "check", "magasins", "magasin"));
    }

    public function searchSale(Request $request)
    {
        $cats = Categorie::where(
            'id_setting',
            auth()->user()->id_setting
        )->get();

        $users = User::where('id', '!=', 1)
            ->where('id_setting', auth()->user()->id_setting)
            ->get();

        $query = Vente::query()
            ->where('id_setting', auth()->user()->id_setting);

        // Date filter
        if ($request->filled('dateDebut') && $request->filled('dateFin')) {
            $query->whereBetween('created_at', [
                $request->dateDebut . ' 00:00:00',
                $request->dateFin . ' 23:59:59'
            ]);
        }

        // User filter
        if ($request->filled('username')) {
            $query->where('username', $request->username);
        }

        // Category filter (from vente_details table)
        if ($request->filled('categorie')) {
            $query->whereHas('items', function ($q) use ($request) {
                $q->where('categorie', $request->categorie);
            });
        }

        $datas = $query
            ->with('items')
            ->latest()
            ->get();

        // Totals from ventes table
        $totalM = (clone $query)->sum('total_ttc');

        $totalR = (clone $query)->sum('reduction');

        // Get matching vente IDs
        $venteIds = (clone $query)->pluck('id');

        // Totals from vente_details table
        $detailQuery = VenteDetail::whereIn('vente_id', $venteIds);

        if ($request->filled('categorie')) {
            $detailQuery->where('categorie', $request->categorie);
        }

        $totalV = (clone $detailQuery)->sum('quantite');

        $totalHt = (clone $detailQuery)->sum('montant');

        return view(
            'vente.index',
            compact(
                'datas',
                'cats',
                'users',
                'totalM',
                'totalR',
                'totalV',
                'totalHt'
            )
        );
    }
    public function printVenteInvoice($id)
    {
        $objAmount = new CurrencyConverterController();
        $amountInWords = 0;
        $dtSms = auth()->user() ? settings::find(auth()->user()->id_setting) : settings::first();

        $data = Vente::find($id);
        if (!$data) {
            return view('errors.introuvable');
        }
        $datas = VenteDetail::where('vente_id', $data->id)->get();
        $client = Client::find($data->client_id);
        $user = User::find($data->username);

        if ($data->tva != 0) {
            $amountInWords = $objAmount->convertAmountToWords($data->total_ttc);
        } else {
            $amountInWords = $objAmount->convertAmountToWords($data->total_ht);
        }

        $datass = [
            'data' => $data,
            'nom' => $client ? $client->nom : '-',
            'num_vente' => $data->num_vente,
            'contact' => $client ? $client->contact : '-',
            'carts' => $datas,
            'total_ht' => $data->total_ht,
            'total_tva' => $data->total_tva,
            'total_ttc' => $data->total_ttc,
            'tva' => $data->tva,
            'amountInWords' => $amountInWords,
            'montantApayer' => $data->total_ht,
            'montantDonner' => $data->montantDonner,
            'restant' => $data->restant,
            'date_hr' => $data->created_at->format('d-m-Y H:i:s'),
            "username" => $user ? $user->name : '',
            "reduction" => $data->reduction,
            "operation" => 'update',
        ];
        $paperSize = $dtSms->address; // Set this dynamically as required

        // Load the PDF view with data
        $pdf = PDF::loadView('pdf.invoice', $datass);
        // Set the paper size based on the $paperSize value
        switch ($paperSize) {
            case 'A5':
                $pdf->setPaper('a5'); // Standard A5
                break;
            case 'A6':
                $pdf->setPaper([0, 0, 298, 420]); // Custom dimensions for A6 in points
                break;
            case 'A7':
                $pdf->setPaper([0, 0, 210, 298]); // Custom dimensions for A7 in points
                break;
            case 'A8':
                $pdf->setPaper([0, 0, 148, 210]); // Custom dimensions for A8 in points
                break;
            default:
                $pdf->setPaper('a4'); // Default to A4 if no size is specified
                break;
        }
        return $pdf->download("Facture_vente_" . $data['num_vente'] . ".pdf");
        // $pdf = PDF::loadView('pdf.invoiceAfter', $datass);
        // return $pdf->download("invoice_" . $data->clientId . ".pdf");
    }

    public function PrintInvoice(Request $request)
    {
        $objAmount = new CurrencyConverterController();
        $amountInWords = 0;
        $dtSms = auth()->user() ? settings::find(auth()->user()->id_setting) : settings::first();

        if ($request->option == "VENTE") {
            $data = Vente::find($request->vente_id);
            $datas = VenteDetail::where('vente_id', $data->id)->get();
            $client = Client::find($data->client_id);
            $user = User::find($data->username);

            if ($data->tva != 0) {
                $amountInWords = $objAmount->convertAmountToWords($data->total_ttc);
            } else {
                $amountInWords = $objAmount->convertAmountToWords($data->total_ht);
            }

            $datass = [
                'data' => $data,
                'nom' => $client ? $client->nom : '',
                'num_vente' => $data->num_vente,
                'contact' => $client ? $client->contact : '',
                'carts' => $datas,
                'total_ht' => $data->total_ht,
                'total_tva' => $data->total_tva,
                'total_ttc' => $data->total_ttc,
                'tva' => $data->tva,
                'amountInWords' => $amountInWords,
                'montantApayer' => $data->total_ht,
                'montantDonner' => $data->montantDonner,
                'restant' => $data->restant,
                'date_hr' => $data->created_at->format('d-m-Y H:i:s'),
                "username" => $user ? $user->name : '',
                "reduction" => $data->reduction,
                "operation" => 'update',
            ];
            $paperSize = $dtSms->address; // Set this dynamically as required

            // Load the PDF view with data
            $pdf = PDF::loadView('pdf.invoice', $datass);
            // Set the paper size based on the $paperSize value
            switch ($paperSize) {
                case 'A5':
                    $pdf->setPaper('a5'); // Standard A5
                    break;
                case 'A6':
                    $pdf->setPaper([0, 0, 298, 420]); // Custom dimensions for A6 in points
                    break;
                case 'A7':
                    $pdf->setPaper([0, 0, 210, 298]); // Custom dimensions for A7 in points
                    break;
                case 'A8':
                    $pdf->setPaper([0, 0, 148, 210]); // Custom dimensions for A8 in points
                    break;
                default:
                    $pdf->setPaper('a4'); // Default to A4 if no size is specified
                    break;
            }
            return $pdf->download("Facture_vente_" . $data['num_vente'] . ".pdf");
            // $pdf = PDF::loadView('pdf.invoiceAfter', $datass);
            // return $pdf->download("invoice_" . $data->clientId . ".pdf");
        } else if ($request->option == "LIV") {
            $data = Vente::find($request->vente_id);
            $datas = VenteDetail::where('vente_id', $data->id)->get();
            $client = Client::find($data->client_id);
            $user = User::find($data->username);

            if ($data->tva != 0) {
                $amountInWords = $objAmount->convertAmountToWords($data->total_ttc);
            } else {
                $amountInWords = $objAmount->convertAmountToWords($data->total_ht);
            }

            $tabNum = explode('-', $data->num_vente);
            $num_liv = 'B-' . $tabNum[1] . '-' . $tabNum[2];

            $datass = [
                'carts' => $datas,
                'nom' => $client ? $client->nom : '',
                'num_vente' => $num_liv,
                'contact' => $client ? $client->contact : '',
                'date_hr' => $data->created_at->format('d-m-Y H:i:s'),
                "username" => $user ? $user->name : '',
                "operation" => $request->option,

            ];
            $pdf = PDF::loadView('pdf.bonLivraison', $datass);
            return $pdf->download("bordeLivraison_" . $data->clientId . ".pdf");
        }
    }

    public function GetDailyReport($startFrom, $endAt)
    {
        $desc = "Rapport de vette";
        $operation = "VENTE";
        $totalR = 0;
        $total_ht = 0;
        $total_ttc = 0;
        $totalV = 0;
        $totalM = 0;

        $datas = Vente::whereDate('created_at', '>=', $startFrom)
            ->whereDate('created_at', '<=', $endAt)
            ->where('id_setting', auth()->user()->id_setting)
            ->get();

        foreach ($datas as $dt) {
            $totalR += $dt->reduction;
            $totalV += $dt->quantite;
            if ($dt->tva == "") {
                $total_ht += $dt->total_ht;
            } else {
                $total_ttc += $dt->total_ttc;
            }
        }
        $totalM = ($total_ht + $total_ttc);

        if (count($datas) > 0) {
            $title = "Vente effectuée du " . $this->formatDate($startFrom) . " au " . $this->formatDate($endAt);

            $datass = [
                'carts' => $datas,
                'totalM' => $totalM,
                'totalR' => $totalR,
                'totalV' => $totalV,
                'dateDebut' => $startFrom,
                'dateFin' => $endAt,
                "title" => $title,
                'date_hr' => Carbon::now(),
                'operation' => $operation,
                'desc' => $desc
            ];

            $pdf = PDF::loadView('pdf.venteParDate', $datass);
            return $pdf->download("venteParDate" . date('d-m-Y') . ".pdf");
        }
    }

    public function GetMonthlyReport(Request $request)
    {

        $totalR = 0;
        $total_ht = 0;
        $total_ttc = 0;
        $totalV = 0;
        $totalM = 0;
        $operation = $request->op;
        if ($operation == "VENTE") {
            $desc = "Rapport de vette";
            $datas = Vente::whereMonth('created_at', Carbon::now()->month)
                ->where('id_setting', auth()->user()->id_setting)
                ->get();
            foreach ($datas as $dt) {
                $totalR += $dt->reduction;
                $totalV += $dt->quantite;
                if ($dt->tva == "") {
                    $total_ht += $dt->total_ht;
                } else {
                    $total_ttc += $dt->total_ttc;
                }
            }
            $totalM = ($total_ht + $total_ttc);

            Carbon::setLocale('fr');
            $title = "Le Rapport de  " . Carbon::now()->translatedFormat('F Y');
        } elseif ($operation == "DETTE") {
            $desc = "Rapport de dette";
            $datas = Dette::whereMonth('created_at', Carbon::now()->month)
                ->where('id_setting', auth()->user()->id_setting)
                ->get();
            foreach ($datas as $dt) {
                $totalR += $dt->restant;
                $totalV += $dt->quantite;
                if ($dt->tva == "") {
                    $total_ht += $dt->total_ht;
                } else {
                    $total_ttc += $dt->total_ttc;
                }
            }
            $totalM = ($total_ht + $total_ttc);

            Carbon::setLocale('fr');
            $title = "Le Rapport de  " . Carbon::now()->translatedFormat('F Y');
        } elseif ($operation == "AVANCE") {
            $desc = "Rapport de paiement d'avance";
            $datas = PaiementAvance::whereMonth('created_at', Carbon::now()->month)
                ->where('id_setting', auth()->user()->id_setting)
                ->get();
            foreach ($datas as $dt) {
                $totalR += $dt->montantPay;
                $totalV += $dt->qte;
                if ($dt->tva == "") {
                    $total_ht += $dt->total_ht;
                } else {
                    $total_ttc += $dt->total_ttc;
                }
            }
            $totalM = ($total_ht + $total_ttc);

            Carbon::setLocale('fr');
            $title = "Le Rapport de  " . Carbon::now()->translatedFormat('F Y');
        } elseif ($operation == "AVANCE_JOUR") {
            $desc = "Rapport de paiement d'avance";

            //Here
            $datas = PaiementAvance::whereDate('created_at', '>=', $request->startFrom)
                ->whereDate('created_at', '<=', $request->endAt)
                ->where('id_setting', auth()->user()->id_setting)
                ->get();
            foreach ($datas as $dt) {
                $totalR += $dt->montantPay;
                $totalV += $dt->qte;
                if ($dt->tva == "") {
                    $total_ht += $dt->total_ht;
                } else {
                    $total_ttc += $dt->total_ttc;
                }
            }
            $totalM = ($total_ht + $total_ttc);
            Carbon::setLocale('fr');
            $title = "Le Rapport de  " . Carbon::now()->translatedFormat('j Y');
        }

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

            $pdf = PDF::loadView('pdf.venteParProd', $datass)->setPaper("A4", "landscape");
            return $pdf->download("venteParMois" . date('d-m-Y') . ".pdf");
        }
    }


    public function GetYealyReport(Request $request)
    {

        //$desc="Rapport de vette";
        //$desc=$request->descs;
        $totalM = 0;
        $totalV = 0;
        $totalR = 0;

        $operation = $request->op;
        if ($operation == "VENTE") {
            $desc = "Rapport de vette";
            $datas = Vente::whereYear('created_at', date('Y'))
                ->where('id_setting', auth()->user()->id_setting)
                ->get();
            foreach ($datas as $data) {
                $totalM += ($data->montant + $data->total_tva);
                $totalV += $data->quantite;
                $totalR += $data->reduction;
            }

            Carbon::setLocale('fr');
            $title = "Le Rapport de  " . Carbon::now()->translatedFormat('F Y');
        } elseif ($operation == "DETTE") {
            $desc = "Rapport de dette";
            $datas = Dette::whereYear('created_at', date('Y'))
                ->where('id_setting', auth()->user()->id_setting)
                ->get();
            foreach ($datas as $data) {
                $totalM += ($data->montant + $data->total_tva);
                $totalV += $data->quantite;
                $totalR += $data->restant;
            }

            Carbon::setLocale('fr');
            $title = "Le Rapport de  " . Carbon::now()->translatedFormat('F Y');
        } elseif ($operation == "AVANCE") {
            $desc = "Rapport de paiement d'avance";
            $datas = PaiementAvance::whereYear('created_at', date('Y'))
                ->where('id_setting', auth()->user()->id_setting)
                ->get();

            foreach ($datas as $data) {
                $totalM += $data->total;
                $totalV += $data->qte;
                $totalR += $data->montantPay;
            }

            Carbon::setLocale('fr');
            $title = "Le Rapport de  " . Carbon::now()->translatedFormat('F Y');
        } elseif ($operation == "AVANCE_JOUR") {
            $desc = "Rapport de paiement d'avance";

            //Here
            $datas = PaiementAvance::whereDate('created_at', '>=', $request->startFrom)
                ->whereDate('created_at', '<=', $request->endAt)
                ->where('id_setting', auth()->user()->id_setting)
                ->get();
            foreach ($datas as $data) {
                $totalM += $data->total;
                $totalV += $data->qte;
                $totalR += $data->montantPay;
            }

            Carbon::setLocale('fr');
            $title = "Le Rapport de  " . Carbon::now()->translatedFormat('j Y');
        }

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

            $pdf = PDF::loadView('pdf.venteParProd', $datass)->setPaper("A4", "landscape");
            return $pdf->download("venteParMois" . date('d-m-Y') . ".pdf");
        }
    }


    public function GetUserDailyReport(Request $request)
    {
        $totalM = 0;
        $totalV = 0;
        $totalR = 0;
        $operation = $request->op;
        if ($operation == "VENTE") {

            if ($request->option == "MOIS") {
                $desc = "Rapport de vette";
                if (auth()->user()->roles == "Admin") {
                    $datas = Vente::whereMonth('created_at', Carbon::now()->month)
                        ->where('id_setting', auth()->user()->id_setting)
                        ->get();
                } else {
                    $datas = Vente::whereMonth('created_at', Carbon::now()->month)
                        ->where("username", auth()->user()->id)
                        ->get();
                }


                foreach ($datas as $data) {
                    $totalM += ($data->montant + $data->total_tva);
                    $totalV += $data->quantite;
                    $totalR += $data->reduction;
                }
                Carbon::setLocale('fr');
                $title = "Le Rapport de  " . Carbon::now()->translatedFormat('F Y');
            } elseif ($request->option == "JOUR") {
                $desc = "Rapport de vette";
                if (auth()->user()->roles == "Admin") {
                    $datas = Vente::whereDate('created_at', Carbon::today())
                        ->where('id_setting', auth()->user()->id_setting)
                        ->get();
                } else {
                    $datas = Vente::whereDate('created_at', Carbon::today())
                        ->where("username", auth()->user()->id)
                        ->get();
                }

                foreach ($datas as $data) {
                    $totalM += ($data->montant + $data->total_tva);
                    $totalV += $data->quantite;
                    $totalR += $data->reduction;
                }
                Carbon::setLocale('fr');
                $title = "Le Rapport de  " . Carbon::now()->translatedFormat('F Y');
            } elseif ($request->option == "ANNEE") {
                $desc = "Rapport de vette";
                if (auth()->user()->roles == "Admin") {
                    $datas = Vente::whereYear('created_at', date('Y'))
                        ->where('id_setting', auth()->user()->id_setting)
                        ->get();
                } else {
                    $datas = Vente::whereYear('created_at', date('Y'))
                        ->where("username", auth()->user()->id)
                        ->get();
                }

                foreach ($datas as $data) {
                    $totalM += ($data->montant + $data->total_tva);
                    $totalV += $data->quantite;
                    $totalR += $data->reduction;
                }
                Carbon::setLocale('fr');
                $title = "Le Rapport de  " . Carbon::now()->translatedFormat('F Y');
            }
        } elseif ($operation == "CRÉANCE") {
            //dd("dd");
            $desc = "Rapport de dette";

            if ($request->option == "MOIS") {
                $desc = "Rapport de Créance";

                if (auth()->user()->roles == "Admin") {
                    $datas = Dette::whereMonth('created_at', Carbon::now()->month)
                        ->where('id_setting', auth()->user()->id_setting)
                        ->get();
                } else {
                    $datas = Dette::whereMonth('created_at', Carbon::now()->month)
                        ->where("username", auth()->user()->id)
                        ->get();
                }

                foreach ($datas as $data) {
                    $totalM += ($data->montant + $data->total_tva);
                    $totalV += $data->quantite;
                    $totalR += $data->restant;
                }
                Carbon::setLocale('fr');
                $title = "Le Rapport de  " . Carbon::now()->translatedFormat('F Y');
            } elseif ($request->option == "JOUR") {
                $desc = "Rapport de Créance";

                if (auth()->user()->roles == "Admin") {
                    $datas = Dette::whereDate('created_at', Carbon::today())
                        ->where('id_setting', auth()->user()->id_setting)
                        ->get();
                } else {
                    $datas = Dette::whereDate('created_at', Carbon::today())
                        ->where("username", auth()->user()->id)
                        ->get();
                }

                foreach ($datas as $data) {
                    $totalM += ($data->montant + $data->total_tva);
                    $totalV += $data->quantite;
                    $totalR += $data->restant;
                }
                Carbon::setLocale('fr');
                $title = "Le Rapport de  " . Carbon::now()->translatedFormat('F Y');
            } elseif ($request->option == "ANNEE") {
                $desc = "Rapport de Créance";

                if (auth()->user()->roles == "Admin") {
                    $datas = Dette::whereYear('created_at', date('Y'))
                        ->where('id_setting', auth()->user()->id_setting)
                        ->get();
                } else {
                    $datas = Dette::whereYear('created_at', date('Y'))
                        ->where("username", auth()->user()->id)
                        ->get();
                }

                foreach ($datas as $data) {
                    $totalM += ($data->montant + $data->total_tva);
                    $totalV += $data->quantite;
                    $totalR += $data->restant;
                }
                Carbon::setLocale('fr');
                $title = "Le Rapport de  " . Carbon::now()->translatedFormat('F Y');
            }
        } elseif ($operation == "AVANCE") {

            if ($request->option == "MOIS") {
                $desc = "Rapport de Avance";
                if (auth()->user()->roles == "Admin") {
                    $datas = PaiementAvance::whereMonth('created_at', Carbon::now()->month)
                        ->where('id_setting', auth()->user()->id_setting)
                        ->get();
                } else {
                    $datas = PaiementAvance::whereMonth('created_at', Carbon::now()->month)
                        ->where("username", auth()->user()->id)
                        ->get();
                }

                foreach ($datas as $data) {
                    $totalM += ($data->montant + $data->total_tva);
                    $totalV += $data->qte;
                    $totalR += $data->restant;
                }
                Carbon::setLocale('fr');
                $title = "Le Rapport de  " . Carbon::now()->translatedFormat('F Y');
            } elseif ($request->option == "JOUR") {
                $desc = "Rapport de Avance";

                if (auth()->user()->roles == "Admin") {
                    $datas = PaiementAvance::whereDate('created_at', Carbon::today())
                        ->where('id_setting', auth()->user()->id_setting)
                        ->get();
                } else {
                    $datas = PaiementAvance::whereDate('created_at', Carbon::today())
                        ->where("username", auth()->user()->id)
                        ->get();
                }

                foreach ($datas as $data) {
                    $totalM += ($data->montant + $data->total_tva);
                    $totalV += $data->qte;
                    $totalR += $data->restant;
                }
                Carbon::setLocale('fr');
                $title = "Le Rapport de  " . Carbon::now()->translatedFormat('F Y');
            } elseif ($request->option == "ANNEE") {
                $desc = "Rapport de Avance";

                if (auth()->user()->roles == "Admin") {
                    $datas = PaiementAvance::whereYear('created_at', date('Y'))
                        ->where('id_setting', auth()->user()->id_setting)
                        ->get();
                } else {
                    $datas = PaiementAvance::whereYear('created_at', date('Y'))
                        ->where("username", auth()->user()->id)
                        ->get();
                }

                foreach ($datas as $data) {
                    $totalM += ($data->montant + $data->total_tva);
                    $totalV += $data->quantite;
                    $totalR += $data->restant;
                }
                Carbon::setLocale('fr');
                $title = "Le Rapport de  " . Carbon::now()->translatedFormat('F Y');
            }
        }

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

            $pdf = PDF::loadView('pdf.venteParProd', $datass)->setPaper("A4", "landscape");
            return $pdf->download("venteParMois" . date('d-m-Y') . ".pdf");
        }

        return back()->with("info", "Pas de donnée");
    }

    public function FormatDate($date)
    {
        return date('d-m-Y', strtotime($date));
    }

    public function autocompleteNew(Request $request)
    {
        $data = User::select("nom_produit", "id")
            ->where("nom_produit", "LIKE", "%{$request->code_barre}%")
            ->where('id_setting', auth()->user()->id_setting)
            ->get();
        return response()->json($data);
    }

    public function autocompleteSearch(Request $request)
    {
        $query = $request->get('query');
        $filterResult = ProductBoutigue::where('nom_produit', 'LIKE', '%' . $query . '%')
            ->where('id_setting', auth()->user()->id_setting)
            ->select(['id', 'nom_produit', 'prix_vente_unitaire'])
            ->get();
        return response()->json($filterResult);
    }

    public function fatcheProduitInfo($id)
    {
        $data = DB::table('product_boutigues')->join('categories', 'product_boutigues.id_categorie', 'categories.id')
            ->join('stocks', 'product_boutigues.id_prod', 'stocks.id')->select('product_boutigues.*', 'nom_categorie', 'libelle')
            ->where('product_boutigues.id', $id)->first();

        return response()->json($data);
    }

    public function AddNewClient($nom, $contact, $clientId, $operation)
    {
        $clts["nom"] = $nom;
        $clts["contact"] = $contact;
        $clts["clientId"] = $clientId;
        $clts["types"] = $operation;
        $clt = (new Client)->StoreClient($clts);
    }

    private function buildInvoiceData($num, $client, $request, $cart, $amountInWords, $operation, $desc)
    {
        $tabNum = explode('-', $num);
        $num_liv = 'B-' . ($tabNum[1] ?? '') . '-' . ($tabNum[2] ?? '');

        return [
            'nom' => $client?->nom ?? 'Non Disponible ',
            'contact' => $client?->contact ?? ' Non',
            'num_vente' => $num,
            'num_liv' => $num_liv, // ✅ FIX ADDED HERE
            'carts' => $cart,

            'total_ht' => $request->total_ht,
            'total_tva' => $request->total_tva,
            'total_ttc' => $request->total_ttc,
            'tva' => $request->tva,

            'amountInWords' => $amountInWords,

            'montantApayer' => $request->total_ht,
            'montantDonner' => $request->montantDonner,
            'restant' => $request->restant ?? 0,

            'date_hr' => now(),
            'username' => auth()->user()->name ?? '',

            'reduction' => $request->reduction ?? 0,

            'operation' => $operation,
            'operation2' => $operation,
            'desc' => $desc,
        ];
    }


    private function generatePdf($view, $data, $paperSize)
    {
        $pdf = PDF::loadView($view, $data);

        return match ($paperSize) {
            'A5' => $pdf->setPaper('a5'),
            'A6' => $pdf->setPaper([0, 0, 298, 420]),
            'A7' => $pdf->setPaper([0, 0, 210, 298]),
            'A8' => $pdf->setPaper([0, 0, 148, 210]),
            default => $pdf->setPaper('a4'),
        };
    }
}