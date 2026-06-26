<?php

namespace App\Http\Controllers;

use App\Models\Boutique;
use App\Models\Dette;
use App\Models\PaiementAvance;
use App\Models\ProductBoutigue;
use App\Models\settings;
use App\Models\Vente;
use App\Models\VenteDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;

class CustomHomeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $today_Dette = 0;
        $mois_PB = 0;
        $today_Vente = 0;
        $prodMonth = 0;
        $prodTotal = 0;
        $venteTotals = 0;
        $detteMonth = 0;
        $paiementTotal = 0;
        $avantMonth = 0;
        $venteMonth = 0;
        $detteTotal = 0;
        $detteT = 0;
        $detteQteMont = 0;
        $venteTotal = 0;
        $venteReductionMois = 0;
        $venteT = 0;
        $total = 0;
        $totalVenteUnitaire = 0;
        $detteMonths = 0;
        $venteMonths = 0;
        $paiementMonths = 0;
        $today_DB = 0;
        $paiementT = 0;
        $prodT = 0;
        $today_V = 0;
        $today_D = 0;
        $today_S = 0;
        $today_P = 0;
        $venteReductionJours = 0;
        $today_Stock = 0;
        $today_Paiement = 0;
        $this_monthStocks = 0;
        $this_monthPaiement = 0;
        $this_monthVentes = 0;
        $totalByMonth = 0;

        $boutiqueId = auth()->user()->id_boutigue ?? session('selected_boutique_id');

        $url = "https://lowcost.skillcodiing.com/dashboard";

        $today = Carbon::now()->startOfDay(); // Start of today (00:00:00)
        $check = settings::where('id', auth()->user()->id_setting)->first();

        if (!empty($check)) {
            if ($check->app_statut == 'NON') {
                auth()->logout(); // Log out the user
                return redirect()->route('login', ['check' => $check])->with('message', 'Les frais de votre hébergement sont arrivés à expiration. Pour continuer à bénéficier du service, veuillez effectuer le paiement.');
            }

            $warning = $check->warning_message;
            $warning .= "<br> Fin d'abonnement : " . $this->FormatDate($check->date_fin);

            // Remove the time part from the dates by using 'toDateString' to get the date in YYYY-MM-DD format
            $createdAt = Carbon::parse($check->created_at)->toDateString(); // Get the date part only
            $dateFin = Carbon::parse($check->date_fin)->toDateString(); // Get the date part only

            // Calculate the difference in days between today and the expiration date
            $expDateWarning = $today->diffInDays(Carbon::parse($dateFin));

            // If expiration date is within 20 days, show a warning
            if ($expDateWarning <= 20) {
                Session::flash('warning', $warning);
            }

            // If expiration date is today or has passed, log the user out and redirect
            if (Carbon::parse($dateFin)->isToday() || Carbon::parse($dateFin)->isBefore($today)) {
                $check->update(['app_statut' => 'NON']);
                auth()->logout(); // Log out the user
                return redirect()->route('login', ['check' => $check])->with('message', 'Les frais de votre hébergement sont arrivés à expiration. Pour continuer à bénéficier du service, veuillez effectuer le paiement.');
                //return redirect()->route('login', compact("check"))->with('message', 'Votre abonnement a expiré. Veuillez vous reconnecter.');
            }
        }


        if (url()->current() == $url && auth()->user()->roles == "Vendeur") {
            return redirect()->route('vente.create');
        }


        /*
        $today = Carbon::now();
        $check = settings::where('id', auth()->user()->id_setting)->first();
        if (!empty($check)) {
            $warning = $check->warning_message;
            $warning = $warning . "<br> Fin d'abonnement : " . $this->FormatDate($check->date_fin);
            $expDateWarning = $today->diffInDays($check->date_fin);

            if ($expDateWarning <= 15) {
                //Afficher le message
                Session::flash('warning', $warning);
            }
        }
        */

        if (auth()->user()->roles == "Super Admin" || auth()->user()->roles == "Admin" || auth()->user()->roles == "Controlleur" || auth()->user()->roles == "Gestionaire") {
            $obj = new Vente();
            $datas = (new Vente)->getAllLatest();
            $dataSts = (new Vente)->getAllLatestN(5);

            //============ Monthly report ===================
            $prodMonth = ProductBoutigue::whereMonth('created_at', Carbon::now()->month)
                ->where('id_setting', auth()->user()->id_setting)
                ->where("id_boutique", $boutiqueId)
                ->get();
            $detteMonth = Dette::whereMonth('created_at', Carbon::now()->month)
                ->where('id_setting', auth()->user()->id_setting)
                ->where("id_boutique", $boutiqueId)
                ->get();
            $venteMonth = Vente::whereMonth('created_at', Carbon::now()->month)
                ->where('id_setting', auth()->user()->id_setting)
                ->where("id_boutique", $boutiqueId)
                ->get();
            $avantMonth = PaiementAvance::whereMonth('created_at', Carbon::now()->month)
                ->where('id_setting', auth()->user()->id_setting)
                ->where("id_boutique", $boutiqueId)
                ->get();

            //============ Daily Report ===================
            $venteDay = Vente::whereDate('created_at', Carbon::today())
                ->where('id_setting', auth()->user()->id_setting)
                ->where("id_boutique", $boutiqueId)
                ->get();
            $detteDay = Dette::whereDate('created_at', Carbon::today())
                ->where('id_setting', auth()->user()->id_setting)
                ->where("id_boutique", $boutiqueId)
                ->get();
            $prodDay = ProductBoutigue::whereDate('created_at', Carbon::today())
                ->where('id_setting', auth()->user()->id_setting)
                ->where("id_boutique", $boutiqueId)
                ->get();
            $avantDay = PaiementAvance::whereDate('created_at', Carbon::today())
                ->where('id_setting', auth()->user()->id_setting)
                ->where("id_boutique", $boutiqueId)
                ->get();

            //last month data

            $venteByMonths = Vente::whereMonth('created_at', Carbon::now()->subMonth()->month)
                ->where('id_setting', auth()->user()->id_setting)
                ->where("id_boutique", $boutiqueId)->get();

            foreach ($venteByMonths as $item) {
                $totalByMonth = $item->sum('montantDonner') - $item->sum('restant') + $item->sum('reduction');
            }

            $last_monthVentes = $totalByMonth;

            $product_boutiques = ProductBoutigue::whereMonth('created_at', Carbon::now()->subMonth()->month)
                ->where('id_setting', auth()->user()->id_setting)
                ->where("id_boutique", $boutiqueId);

            $total_achat = 0;
            foreach ($product_boutiques as $product) {
                $total_achat += $product->quantite * $product->prix_achat;
            }
            $last_monthStocks = $total_achat;

            $last_monthDettes = Dette::select('montant')
                ->whereMonth('created_at', Carbon::now()->subMonth()->month)
                ->where('id_setting', auth()->user()->id_setting)
                ->where("id_boutique", $boutiqueId)
                ->sum('montant');

            $last_monthPaiement = PaiementAvance::select('montant')
                ->whereMonth('created_at', Carbon::now()->subMonth()->month)
                ->where('id_setting', auth()->user()->id_setting)
                ->where("id_boutique", $boutiqueId)
                ->sum('montantPay');

            $detteMonths = $detteMonth;
            $venteMonths = $venteMonth;
            $paiementMonths = $avantMonth;
        } else {

            $datas = Vente::where('username', auth()->user()->id)
                ->where("id_boutique", $boutiqueId)
                ->latest()->get();
            $dataSts = Vente::where('username', auth()->user()->id)
                ->where("id_boutique", $boutiqueId)
                ->latest()->limit(5)->get();

            //============ Monthly report ===================
            $prodMonth = ProductBoutigue::whereMonth('created_at', Carbon::now()->month)
                ->where('username', auth()->user()->name)->where('id_setting', auth()->user()->id_setting)
                ->where("id_boutique", $boutiqueId)
                ->get();
            $detteMonth = Dette::whereMonth('created_at', Carbon::now()->month)
                ->where("id_boutique", $boutiqueId)
                ->where('username', auth()->user()->id)->get();
            $venteMonth = Vente::whereMonth('created_at', Carbon::now()->month)
                ->where('username', auth()->user()->id)->get();
            $avantMonth = PaiementAvance::whereMonth('created_at', Carbon::now()->month)
                ->where('done_by', auth()->user()->name)->where('id_setting', auth()->user()->id_setting)->get();

            //============ Daily Report ===================
            $venteDay = Vente::whereDate('created_at', Carbon::today())
                ->where('username', auth()->user()->id)->get();
            $detteDay = Dette::whereDate('created_at', Carbon::today())
                ->where('username', auth()->user()->id)->get();
            $prodDay = ProductBoutigue::whereDate('created_at', Carbon::today())
                ->where('username', auth()->user()->name)->where('id_setting', auth()->user()->id_setting)->get();
            $avantDay = PaiementAvance::whereDate('created_at', Carbon::today())
                ->where('done_by', auth()->user()->name)->where('id_setting', auth()->user()->id_setting)->get();

            //last month data
            $last_monthVentes = Vente::select('montant')
                ->whereMonth('created_at', Carbon::now()->subMonth()->month)
                ->where('username', auth()->user()->id)
                ->sum('montant');

            $product_boutiqByUser = ProductBoutigue::whereMonth('created_at', Carbon::now()->subMonth()->month)
                ->where('username', auth()->user()->id);
            $total_achat = 0;
            foreach ($product_boutiqByUser as $product) {
                $total_achat += $product->quantite * $product->prix_achat;
            }
            $last_monthStocks = $total_achat;

            $last_monthDettes = Dette::select('montant')
                ->whereMonth('created_at', Carbon::now()->subMonth()->month)
                ->where('username', auth()->user()->id)
                ->sum('montant');

            $last_monthPaiement = PaiementAvance::select('montant')
                ->whereMonth('created_at', Carbon::now()->subMonth()->month)
                ->where('done_by', auth()->user()->name)
                ->where('id_setting', auth()->user()->id_setting)
                ->sum('montantPay');

            $detteMonths = $detteMonth;
            $venteMonths = $venteMonth;
            $paiementMonths = $avantMonth;
        }

        //Monthily
        if (!empty($avantMonth)) {
            foreach ($avantMonth as $val) {
                $paiementTotal += $val->montantPay;
                $paiementT += $val->qte;
                $this_monthPaiement += $paiementTotal;
            }
        }

        //Daily 
        if (!empty($avantDay)) {
            foreach ($avantDay as $val) {
                $today_Paiement += $val->montantPay;
                $today_P += $val->qte;
            }
        }

        //Monthily Product loop
        if (!empty($prodMonth)) {
            foreach ($prodMonth as $val) {
                $total = $val->quantite * $val->prix_achat;
                $totalVenteUnitaire = $val->quantite * $val->prix_vente_unitaire;
                $prodTotal += $total;
                $mois_PB += $totalVenteUnitaire - $total;
                $prodT += $val->quantite;
            }
        }

        //daily Product loop
        if (!empty($prodDay)) {
            foreach ($prodDay as $val) {
                $total = $val->quantite * $val->prix_achat;
                $totalVenteUnitaire = $val->quantite * $val->prix_vente_unitaire;
                $today_Stock += $total;
                $mois_PB += $totalVenteUnitaire - $total;
                $today_S += $val->quantite;

                $this_monthStocks += $total;
            }
        }

        //Monthly loop
        $clientId = '';
        if (!empty($detteMonth)) {
            $montantPayer = 0;
            $detteTotal = 0;
            $detteT = 0;
            foreach ($detteMonth as $val) {
                if ($clientId != $val->clientId) {
                    $clientId = $val->clientId;
                    $detteT += $val->quantite;
                    $montantPayer += $val->montantDonner;

                    if ($val->tva == '') {
                        $detteTotal += $val->total_ht;
                    } else {
                        $detteTotal += $val->total_ttc;
                    }
                }
            }
            $this_monthDettes = ($detteTotal - $montantPayer);
            $detteQteMont = $detteT;
            if ($this_monthDettes == 0.0) {
                $detteQteMont = 0;
            }
        }

        //Daily loop
        $clientId = '';
        if (!empty($detteDay)) {
            $montantPayer = 0;
            $detteTotal = 0;
            $detteT = 0;

            foreach ($detteDay as $val) {
                if ($clientId != $val->clientId) {
                    $clientId = $val->clientId;
                    $detteT += $val->quantite;
                    $montantPayer += $val->montantDonner;
                    if ($val->tva == '') {
                        $detteTotal += $val->total_ht;
                    } else {
                        $detteTotal += $val->total_ttc;
                    }
                }
            }

            $today_D = $detteT;
            $today_Dette = ($detteTotal - $montantPayer);
            if ($today_Dette == 0.0) {
                $today_D = 0;
            }
        }
        $today_dataDBs = $detteDay;

        if (!empty($venteMonth)) {
            foreach ($venteMonth as $item) {
                // $venteTotal = $item->quantite * $item->prix;
                // $venteReductionMois += $item->reduction;
                // $venteT += $item->quantite;
                $venteTotal = $item->sum('montantDonner') - $item->sum('restant') + $item->sum('reduction');
                $this_monthVentes = $venteTotal;
            }
        }

        if (!empty($venteDay)) {

            foreach ($venteDay as $val) {
                $venteTotals = ($val->quantite * $val->prix);
                $venteReductionJours += $val->reduction;
                $today_V += $val->quantite;
                $today_Vente = $val->sum('montantDonner') - $val->sum('restant') + $val->sum('reduction');
            }
        }
        $today_dataVBs = $venteDay;
        return view("layout.home", compact(
            "paiementTotal",
            "detteQteMont",
            "venteReductionJours",
            "venteReductionMois",
            "detteMonths",
            "venteMonths",
            "mois_PB",
            "today_dataVBs",
            "today_dataDBs",
            "paiementT",
            "today_P",
            "today_Paiement",
            "this_monthPaiement",
            "last_monthPaiement",
            "prodT",
            "detteT",
            "venteT",
            "today_V",
            "today_D",
            "today_S",
            "today_Vente",
            "today_Dette",
            "today_Stock",
            "this_monthVentes",
            "last_monthVentes",
            "this_monthStocks",
            "last_monthStocks",
            "this_monthDettes",
            "last_monthDettes",
            "datas",
            "dataSts",
            "detteTotal",
            "prodTotal",
            "venteTotal"
        ));
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
        //
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

    public function FormatDate($date)
    {
        return date('d/m/Y', strtotime($date));
    }

    public function minidashboard()
    {

        $boutiques = Boutique::where('id_setting', auth()->user()->id_setting)->get(); // You can restrict by permission if needed
        session()->forget(['selected_boutique_id', 'selected_boutique_name']);
        return view("layout.mini_dashboard", compact("boutiques"));
    }

    public function handleSelection(Request $request)
    {
        $validated = $request->validate([
            'boutique_id' => 'required|exists:boutiques,id',
        ]);

        $boutique = Boutique::find($validated['boutique_id']);

        session([
            'selected_boutique_id' => $boutique->id,
            'selected_boutique_name' => $boutique->nom_boutique,
        ]);

        return redirect()->route('dashboard')->with('info', 'Vous êtes maintenant dans la boutique : ' . $boutique->nom_boutique);
    }

    public function switchBoutique(Request $request)
    {
        session()->forget(['selected_boutique_id', 'selected_boutique_name']);
        return redirect()->route('minidashboard');
    }
}
