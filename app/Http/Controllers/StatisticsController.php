<?php

namespace App\Http\Controllers;

use App\Models\Categorie;
use App\Models\Boutique;
use App\Models\Dette;
use App\Models\PaiementAvance;
use App\Models\Stock;
use App\Models\User;
use App\Models\Vente;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\ProductBoutigue;
use App\Models\VenteDetail;

class StatisticsController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function venteCreate()
    {
        $boutiqueId = auth()->user()->id_boutigue ?? session('selected_boutique_id');
        $totalM = 0;
        $totalV = 0;
        $totalR = "";
        $boutiques = Boutique::where('id_setting', auth()->user()->id_setting)->get();
        $produits = ProductBoutigue::where('id_boutique', $boutiqueId)->get();
        $users = User::where('id_boutigue', $boutiqueId)->where('id_setting', auth()->user()->id_setting)->get();

        $datas = Vente::whereDate('created_at', Carbon::today())
            ->where('id_setting', auth()->user()->id_setting)
            ->get();
        if (count($datas) > 0) {
            foreach ($datas as $data) {
                $totalM += $data->montant;
                $totalV += $data->quantite;
            }
        }


        return view("statistics.vente", compact("datas", "boutiques", "totalR", "totalM", "totalV", "users"));
    }

    public function detteCreate()
    {
        $boutiqueId = auth()->user()->id_boutigue ?? session('selected_boutique_id');

        $totalM = 0;
        $totalQ = 0;
        $totalR = "";
        $produits = ProductBoutigue::where('id_boutique', $boutiqueId)->get();
        $users = User::where('id', '!=', '1')->where('id_setting', auth()->user()->id_setting)->get();

        $datas = Dette::whereDate('created_at', Carbon::today())
            ->where('id_setting', auth()->user()->id_setting)
            ->get();
        if (count($datas) > 0) {
            foreach ($datas as $data) {
                $totalM += $data->montant;
                $totalQ += $data->quantite;
            }
        }
        return view("statistics.dette", compact("datas", "produits", "totalR", "totalM", "totalQ", "users"));
    }

    public function stockCreate()
    {
        $boutiqueId = auth()->user()->id_boutigue ?? session('selected_boutique_id');
        $totalV = 0;
        $totalQ = 0;
        $produits = ProductBoutigue::where('id_boutique', $boutiqueId)->get();
        $users = User::where('id_boutigue', $boutiqueId)->where('id_setting', auth()->user()->id_setting)->get();
        $datas = ProductBoutigue::whereDate('created_at', Carbon::today())->where('id_boutique', $boutiqueId)->get();
        return view("statistics.product", compact("datas", "produits", "totalQ", "totalV", "users"));
    }

    public function StatisticsAll(Request $request)
    {
        $boutiqueId = auth()->user()->id_boutigue ?? session('selected_boutique_id');

        $boutiques = Boutique::where('id_setting', auth()->user()->id_setting)->get();
        $users = User::where('id_boutigue', $boutiqueId)
            ->where('id_setting', auth()->user()->id_setting)
            ->get();

        $produits = ProductBoutigue::where('id_boutique', $boutiqueId)->get();

        $objUser = new User();
        $operation = "VENTE";
        $desc = "Rapport de vente";

        $datas = collect();
        $totalM = 0;
        $totalR = 0;
        $totalV = 0;
        $total_ht = 0;

        $query = Vente::where('id_setting', auth()->user()->id_setting);

        /* =========================
           FILTERS
        ========================= */

        if ($request->filled('dateDebut') && $request->filled('dateFin')) {
            $query->whereBetween('created_at', [
                $request->dateDebut . ' 00:00:00',
                $request->dateFin . ' 23:59:59'
            ]);
        }

        if ($request->filled('username')) {
            $query->where('username', $request->username);
        }

        /* =========================
           PRODUCT FILTER (via details)
        ========================= */
        if ($request->filled('id_prod')) {
            $query->whereHas('items', function ($q) use ($request) {
                $q->where('id_prod', $request->id_prod);
            });
        }

        $datas = $query->with('items')->latest()->get();

        /* =========================
           TOTALS (VENTE TABLE)
        ========================= */
        $totalM = (clone $query)->sum('total_ttc');
        $totalR = (clone $query)->sum('reduction');

        /* =========================
           TOTALS (DETAILS TABLE)
        ========================= */
        $venteIds = (clone $query)->pluck('id');

        $detailQuery = VenteDetail::whereIn('vente_id', $venteIds);

        if ($request->filled('id_prod')) {
            $detailQuery->where('id_prod', $request->id_prod);
        }

        $totalV = (clone $detailQuery)->sum('quantite');
        $total_ht = (clone $detailQuery)->sum('montant');

        /* =========================
           PRINT (OPTIONAL)
        ========================= */
        if ($request->option == "PRINT" && count($datas) > 0) {

            $username = $request->username
                ? $objUser->ShowUserNameVente($request->username)
                : null;

            $title = "Rapport de vente";

            if ($request->filled('dateDebut') && $request->filled('dateFin')) {
                $title .= " du " . $this->formatDate($request->dateDebut)
                    . " au " . $this->formatDate($request->dateFin);
            }

            if ($username) {
                $title .= " - " . $username;
            }

            $datass = [
                'carts' => $datas,
                'totalM' => $totalM,
                'totalR' => $totalR,
                'totalV' => $totalV,
                'total_ht' => $total_ht,
                'title' => $title,
                'date_hr' => Carbon::now(),
                'operation' => $operation,
                'desc' => $desc,
                'username' => $username,
            ];

            $pdf = PDF::loadView('pdf.venteStatistics', $datass)
                ->setPaper("A4", "landscape");

            return $pdf->download("venteStatistics_" . date('d-m-Y') . ".pdf");
        }

        /* =========================
           VIEW RETURN
        ========================= */
        return view("statistics.vente", compact(
            "datas",
            "boutiques",
            "totalR",
            "totalM",
            "totalV",
            "users"
        ));
    }

    public function FormatDate($date)
    {
        return date('d-m-Y', strtotime($date));
    }

    public function FormatDateWithTime($date)
    {
        return date('d-m-Y H:i:s', strtotime($date));
    }
}