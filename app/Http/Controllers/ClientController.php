<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\ClientMouvement;
use App\Models\settings;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use App\Models\WalletTransaction;

class ClientController extends Controller
{
    const SMS_API_LINK = "https://testapi.skillcodiing.com/api/sms/v1.0/sendSms";
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $boutiqueId = auth()->user()->id_boutigue ?? session('selected_boutique_id');
        $datas = (new Client())->getAll();
        //dd($datas->sum('wallet_balance'));
        $total = Client::count();
        return view("client.index", compact("datas", "total"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("client.create");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store_old(Request $request)
    {

        if ($request->form_type == "depot") {
            $request->validate([
                'montant' => 'required|numeric',
            ]);

            //dd($request->all());
            //"type_mouvement" => "depot"
            //"client_id" => "1"
            //"form_type" => "depot"
            //"montant" => "500000"


            //'num_mouvement',
            //'client_id',
            //'type_mouvement',
            //'total',
            //'montant_payer',
            //'montant_credit',
            //'montant_restant',
            //'invoice_id',
            //'id_setting',

            $client = Client::find($request->client_id);
            $datas = $request->all();
            $datas['id_setting'] = auth()->user()->id_setting;
            $datas['num_mouvement'] = (new ClientMouvement())->numMouvement();
            $datas['type_mouvement'] = $request->type_mouvement;
            $datas['total'] = $request->montant;
            $datas['montant_payer'] = $request->montant;
            $datas['montant_credit'] = 0;
            $datas['montant_restant'] = 0;

            $dtSms = settings::find(auth()->user()->id_setting);

            $datass = [
                'client' => $client,
                'data' => $datas['montant'],
            ];
            $saveMouv = ClientMouvement::create($datas);
            if ($saveMouv) {
                //Update the client wallet balance
                $client->addDeposit($request->montant);

                if ($request->verifier == "on") {
                    $solde = number_format($request->montant, 2, ',', ' ');
                    $message = "Votre compte a été débuter de {$solde}" . config('app.cc') . ", voici le lien de verification: " . route('client.verified', $saveMouv->id);
                    $msg = "Votre compte a été débuter de {$solde}" . config('app.cc');
                    if ($dtSms->sms == "OUI" && $client->contact != "") {

                        // $msg = explode('[numero]', $dtSms->msgAchat);
                        // $message = $msg[0] . $datas['num_vente'] . $msg[1];
                        // $message = explode('[operation]', $message);
                        // $message = $message[0] . 'achat' . $message[1];
                        // $message = "{$dtSms->app_name}, Merci pour votre confiance. Voici le lien de la facture: " . route('facture_vente.client', $data->id);
                        Http::post(self::SMS_API_LINK, [
                            'email' => $dtSms->email,
                            'password' => $dtSms->password,
                            'phoneNumber' => $client->contact,
                            'senderName' => $dtSms->senderName,
                            'message' => $message,
                        ]);
                    }
                    return back()->with('succes', "{$msg}");
                }
                return redirect()->back()->with('succes', 'Dépôt effectuer avec succès');
            } else {
                return back()->with('error', "Une erreur s'est produite, réessayer ultérieurement!");
            }
        }
        $datas = $request->validate([
            "nom" => ["required"],
            "contact" => ["integer"],
        ]);

        $datas['email'] = $request->email;
        $datas['id_setting'] = auth()->user()->id_setting;
        $datas['adresse'] = $request->adresse;
        $datas['credit_limit'] = $request->credit_limit ?? 0;
        $data = (new Client())->StoreClient($datas);

        if ($data) {
            return back()->with("succes", "Enregistrement effectué avec succès.");
        }
        return back()->with("error", "Enregistrement non effectué!");
    }


    public function store(Request $request)
    {

        //dd($request->all());
        try {

            DB::beginTransaction();

            if ($request->form_type == "depot") {
                //dd($request->all());

                /*
                "client_id" => "1"
                "form_type" => "depot"
                "type_mouvement" => "paiement"
                "montant" => "20000"
                */

                $request->validate([
                    'montant' => 'required|numeric',
                ]);

                $client = Client::find($request->client_id);

                if (!$client) {
                    return back()->with('error', 'Client introuvable');
                }

                $datas = $request->all();
                $datas['id_setting'] = auth()->user()->id_setting;
                $datas['num_mouvement'] = (new ClientMouvement())->numMouvement();
                $datas['type_mouvement'] = $request->type_mouvement;
                $datas['total'] = $request->montant;
                $datas['montant_payer'] = $request->montant;
                $datas['montant_credit'] = 0;
                $datas['montant_restant'] = 0;

                $dtSms = settings::find(auth()->user()->id_setting);

                $description = $request->type_mouvement . " du " . date('d/m/Y') .
                    " - Client : " . ($client->nom ?? 'Non renseigné') .
                    " - Montant : " . number_format($request->montant, 0, ',', ' ') . config('app.cc');
                //Add waletTransaction 
                WalletTransaction::ajouterOperation(
                    $client->id,
                    $request->type_mouvement,
                    $request->montant,
                    $description
                );

                if ($datas['type_mouvement'] === 'paiement_credit') {
                    $datas['type_mouvement'] = 'paiement';
                }

                $saveMouv = ClientMouvement::create($datas);

                if (!$saveMouv) {
                    DB::rollBack();
                    return back()->with('error', "Impossible d'enregistrer le mouvement");
                }

                // Update wallet
                $client->addDeposit($request->montant, $request->type_mouvement);

                // SMS + verification
                if ($request->verifier == "on") {

                    $solde = number_format($request->montant, 2, ',', ' ');
                    $message = "Votre compte a été crédité de {$solde}" . config('app.cc')
                        . ", lien de vérification: " . route('client.verified', $saveMouv->id);

                    $msg = "Votre compte a été crédité de {$solde}" . config('app.cc');

                    if ($dtSms && $dtSms->sms == "OUI" && !empty($client->contact)) {

                        Http::post(self::SMS_API_LINK, [
                            'email' => $dtSms->email,
                            'password' => $dtSms->password,
                            'phoneNumber' => $client->contact,
                            'senderName' => $dtSms->senderName,
                            'message' => $message,
                        ]);
                    }

                    DB::commit();
                    return back()->with('succes', $msg);
                }

                DB::commit();
                return back()->with('succes', 'Dépôt effectué avec succès');
            }

            // =========================
            // CREATE CLIENT SECTION
            // =========================

            $datas = $request->validate([
                "nom" => ["required"],
                "contact" => ["integer"],
            ]);

            $datas['email'] = $request->email;
            $datas['id_setting'] = auth()->user()->id_setting;
            $datas['adresse'] = $request->adresse;
            $datas['credit_limit'] = $request->credit_limit ?? 0;

            $data = (new Client())->StoreClient($datas);

            if (!$data) {
                DB::rollBack();
                return back()->with("error", "Enregistrement non effectué!");
            }

            DB::commit();
            return back()->with("succes", "Enregistrement effectué avec succès.");

        } catch (\Exception $e) {

            DB::rollBack();

            return back()->with("error", "Erreur système: " . $e->getMessage());
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
        $data = Client::find($id);
        $solde = 0;
        $historiques = ClientMouvement::where('client_id', $id)->latest()->get();
        $depot = $historiques->where('type_mouvement', 'depot')->sum('montant');
        $retrait = $historiques->where('type_mouvement', 'retrait')->sum('montant');
        $solde = $depot - $retrait;
        if ($data) {
            return view("client.show", compact("data", "historiques", 'solde'));
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
        $datas = Client::find($id);
        return view("client.edit", compact("datas"));
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
            'nom' => ['required'],
            "contact" => ['required'],
        ]);
        $datas = Client::find($id);
        $datas->nom = $request->input("nom");
        $datas->contact = $request->input("contact");
        $datas->adresse = $request->input("adresse");
        $datas->email = $request->input("email");
        $datas->credit_limit = $request->credit_limit ?? 0;

        $datas->update();
        if ($datas) {
            return redirect()->route("client.index")->with("succes", "Mise à jour effectuée avec succès.");
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
        $obj = new Client();
        $data = $obj->deleteClient($id);
        if ($data) {
            return back()->with("succes", "Supression reussie");
        }
        return back()->with("error", "Supression non reussie");
    }

    public function verifiedSolde($id)
    {
        $detail = ClientMouvement::find($id);
        $pdf = PDF::loadView('pdf.recu_client', compact("detail"))->setPaper([0, 0, 298, 420]);
        return $pdf->stream('Reçu_debut_compte_' . $detail->num_mouvement . 'pdf');
    }

    public function toggleStatus($id)
    {
        $client = Client::findOrFail($id);

        if ($client->status === 'active') {
            $client->status = 'blocked';
        } else {
            $client->status = 'active';
        }

        $client->save();

        return redirect()->back()->with('succes', 'Statut du client mis à jour avec succès.');
    }
}