<?php

namespace App\Http\Controllers;

use App\Models\Boutique;
use App\Models\ProductBoutigue;
use App\Models\Vente;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CustomHomeController extends Controller
{
    public function index(Request $request)
    {
        $boutiqueId = auth()->user()->id_boutigue ?? session('selected_boutique_id');

        // ---------- SUBSCRIPTION CHECK ----------
        $today = Carbon::now()->startOfDay();
        $check = \App\Models\settings::where('id', auth()->user()->id_setting)->first();
        if ($check) {
            if ($check->app_statut == 'NON') {
                auth()->logout();
                return redirect()->route('login', ['check' => $check])
                    ->with('message', 'Les frais de votre hébergement sont arrivés à expiration...');
            }
            $dateFin = Carbon::parse($check->date_fin)->toDateString();
            if ($today->diffInDays($dateFin) <= 20) {
                Session::flash('warning', $check->warning_message . "<br> Fin d'abonnement : " . $this->FormatDate($check->date_fin));
            }
            if (Carbon::parse($dateFin)->isToday() || Carbon::parse($dateFin)->isBefore($today)) {
                $check->update(['app_statut' => 'NON']);
                auth()->logout();
                return redirect()->route('login', ['check' => $check])
                    ->with('message', 'Les frais de votre hébergement sont arrivés à expiration...');
            }
        }

        // Redirect vendeur to POS
        if (url()->current() == "https://lowcost.skillcodiing.com/dashboard" && auth()->user()->roles == "Vendeur") {
            return redirect()->route('vente.create');
        }

        // ---------- ACCESS CONTROL & BOUTIQUES ----------
        $isAdmin = in_array(auth()->user()->roles, ['Super Admin', 'Admin', 'Controlleur', 'Gestionaire']);
        if ($isAdmin) {
            $boutiques = Boutique::where('id_setting', auth()->user()->id_setting)->get();
        } else {
            $boutique = Boutique::where('id', $boutiqueId)->first();
            $boutiques = collect([$boutique]);
        }

        // ---------- LATEST SALES PER BOUTIQUE ----------
        $boutiqueSales = collect();
        foreach ($boutiques as $boutique) {
            $ventes = Vente::where('id_boutique', $boutique->id)
                ->with('items')
                ->latest()
                ->limit(5)
                ->get();
            $boutiqueSales->push([
                'boutique' => $boutique,
                'ventes' => $ventes,
            ]);
        }

        // ---------- DAILY DATA (selected boutique) ----------
        $todayVentes = Vente::where('id_boutique', $boutiqueId)
            ->whereDate('created_at', Carbon::today())
            ->with('items')
            ->get();

        $todayDu = 0;   // total dû (HT or TTC)
        $todayPaye = 0;   // montantDonner
        $todayRestant = 0;   // restant
        $todayReduction = 0;
        $todayBenefice = 0;

        foreach ($todayVentes as $vente) {
            // Amount due
            if ($vente->tva == '' || empty($vente->tva)) {
                $todayDu += $vente->total_ht;
            } else {
                $todayDu += $vente->total_ttc;
            }
            $todayPaye += $vente->montantDonner ?? 0;
            $todayRestant += $vente->restant ?? 0;
            $todayReduction += $vente->reduction ?? 0;

            // Profit = sum of (detail revenue - cost*quantity)
            foreach ($vente->items as $item) {
                $product = ProductBoutigue::where('id_prod', $item->id_prod)
                    ->where('id_boutique', $boutiqueId)
                    ->first();
                $cost = $product->prix_achat ?? 0;
                $todayBenefice += ($item->montant - ($cost * $item->quantite));
            }
        }
        $todayNetAPayer = $todayDu - $todayReduction; // what they should have paid

        // Daily stock (current inventory value)
        $allStock = ProductBoutigue::where('id_boutique', $boutiqueId)
            ->where("created_at", $today)
            ->get();
        $today_Stock = $allStock->sum(function ($p) {
            return $p->quantite * $p->prix_achat;
        });
        $today_S = $allStock->sum('quantite');

        // ---------- MONTHLY DATA ----------
        $monthVentes = Vente::where('id_boutique', $boutiqueId)
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->with('items')
            ->get();

        $monthDu = 0;
        $monthPaye = 0;
        $monthRestant = 0;
        $monthReduction = 0;
        $monthBenefice = 0;

        foreach ($monthVentes as $vente) {
            if ($vente->tva == '' || empty($vente->tva)) {
                $monthDu += $vente->total_ht;
            } else {
                $monthDu += $vente->total_ttc;
            }
            $monthPaye += $vente->montantDonner ?? 0;
            $monthRestant += $vente->restant ?? 0;
            $monthReduction += $vente->reduction ?? 0;

            foreach ($vente->items as $item) {
                $product = ProductBoutigue::where('id_prod', $item->id_prod)
                    ->where('id_boutique', $boutiqueId)
                    ->first();
                $cost = $product->prix_achat ?? 0;
                $monthBenefice += ($item->montant - ($cost * $item->quantite));
            }
        }
        $monthNetAPayer = $monthDu - $monthReduction;

        // Monthly stock value = same as current stock (since we don't track historical stock easily)
        $prodTotal = $today_Stock;
        $prodT = $today_S;

        // Month stock (current inventory value)
        $allStockMonth = ProductBoutigue::where('id_boutique', $boutiqueId)
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->get();
        $prodTotal = $allStockMonth->sum(function ($p) {
            return $p->quantite * $p->prix_achat;
        });
        $prodT = $allStockMonth->sum('quantite');

        // ---------- LAST MONTH DATA (for charts) ----------
        $lastMonthVentes = Vente::where('id_boutique', $boutiqueId)
            ->whereMonth('created_at', Carbon::now()->subMonth()->month)
            ->whereYear('created_at', Carbon::now()->subMonth()->year)
            ->get();
        $last_monthVentes = $lastMonthVentes->sum(function ($v) {
            return ($v->tva == '' || empty($v->tva)) ? $v->total_ht : $v->total_ttc;
        }); // simple total due for chart

        $last_monthStocks = $today_Stock; // placeholder (use actual historical data if available)

        return view("layout.home", compact(
            "boutiqueSales",
            // daily
            "todayDu",
            "todayPaye",
            "todayRestant",
            "todayReduction",
            "todayNetAPayer",
            "todayBenefice",
            "today_Stock",
            "today_S",
            // monthly
            "monthDu",
            "monthPaye",
            "monthRestant",
            "monthReduction",
            "monthNetAPayer",
            "monthBenefice",
            "prodTotal",
            "prodT",
            // charts
            "last_monthVentes",
            "last_monthStocks"
        ));
    }

    // ---------- Other methods unchanged ----------
    public function create()
    {
    }
    public function store(Request $request)
    {
    }
    public function show($id)
    {
    }
    public function edit($id)
    {
    }
    public function update(Request $request, $id)
    {
    }
    public function destroy($id)
    {
    }

    public function FormatDate($date)
    {
        return date('d/m/Y', strtotime($date));
    }

    public function minidashboard()
    {
        $boutiques = Boutique::where('id_setting', auth()->user()->id_setting)->get();
        session()->forget(['selected_boutique_id', 'selected_boutique_name']);
        return view("layout.mini_dashboard", compact("boutiques"));
    }

    public function handleSelection(Request $request)
    {
        $validated = $request->validate(['boutique_id' => 'required|exists:boutiques,id']);
        $boutique = Boutique::find($validated['boutique_id']);
        session(['selected_boutique_id' => $boutique->id, 'selected_boutique_name' => $boutique->nom_boutique]);
        return redirect()->route('dashboard')->with('info', 'Vous êtes maintenant dans la boutique : ' . $boutique->nom_boutique);
    }

    public function switchBoutique(Request $request)
    {
        session()->forget(['selected_boutique_id', 'selected_boutique_name']);
        return redirect()->route('minidashboard');
    }
}