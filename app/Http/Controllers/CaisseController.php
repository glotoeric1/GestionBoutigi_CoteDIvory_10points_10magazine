<?php

namespace App\Http\Controllers;

use App\Models\ProductBoutigue;
use App\Models\Vente;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CaisseController extends Controller
{
    public function CaisseGlobal(Request $request)
    {
        $boutiqueId = auth()->user()->id_boutigue ?? session('selected_boutique_id');
        $currency = ' F CFA';

        // --- Default title & date range ---
        $title = "Caisse globale";

        $ventesQuery = Vente::where('id_boutique', $boutiqueId);

        if ($request->filled('dateDebut') && $request->filled('dateFin')) {
            $title = "Caisse du " . $this->FormatDate($request->dateDebut) . " au " . $this->FormatDate($request->dateFin);
            $ventesQuery->whereDate('created_at', '>=', $request->dateDebut)
                ->whereDate('created_at', '<=', $request->dateFin);
        } else {
            // Default: current month
            $ventesQuery->whereMonth('created_at', Carbon::now()->month)
                ->whereYear('created_at', Carbon::now()->year);
            $title = "Caisse du mois de " . Carbon::now()->translatedFormat('F Y');
        }

        $ventes = $ventesQuery->get();

        // --- Loop through EVERY sale to compute correct totals ---
        $totalDu = 0;   // total_ht or total_ttc (what they must pay)
        $totalPaye = 0;   // montantDonner (what they actually paid)
        $totalRestant = 0;   // restant (unpaid)
        $totalReduction = 0;

        $beneficeVenteDetail = 0;
        $beneficeVenteGros = 0;

        $objVente = new Vente();

        foreach ($ventes as $vente) {
            // ---- AMOUNT DUE ----
            if ($vente->tva == '' || empty($vente->tva)) {
                $totalDu += $vente->total_ht;
            } else {
                $totalDu += $vente->total_ttc;
            }

            // ---- PAID, REMAINING, DISCOUNT ----
            $totalPaye += $vente->montantDonner ?? 0;
            $totalRestant += $vente->restant ?? 0;
            $totalReduction += $vente->reduction ?? 0;

            // ---- PROFIT (same logic as before) ----
            if ($vente->options == '2') {
                $beneficeVenteGros += $objVente->CalculerBenefice($vente->id_prod, 'gros');
            } else {
                $beneficeVenteDetail += $objVente->CalculerBenefice($vente->id_prod);
            }
        }

        // ---- Net expected (after reduction) ----
        $netAPayer = $totalDu - $totalReduction;

        // ---- Stock value (current total stock) ----
        $stockProducts = ProductBoutigue::where('id_boutique', $boutiqueId)->get();
        $stockValue = $stockProducts->sum(function ($item) {
            return $item->quantite * $item->prix_achat;
        });
        $stockQuantity = $stockProducts->sum('quantite');

        // --- PRINT OPTION ---
        if ($request->option == "PRINT") {
            $datas = [
                'title' => $title,
                'totalDu' => $totalDu,
                'totalReduction' => $totalReduction,
                'netAPayer' => $netAPayer,
                'totalPaye' => $totalPaye,
                'totalRestant' => $totalRestant,
                'beneficeVenteDetail' => $beneficeVenteDetail,
                'beneficeVenteGros' => $beneficeVenteGros,
                'stockValue' => $stockValue,
                'stockQuantity' => $stockQuantity,
                'date_hr' => Carbon::now(),
                'currency' => $currency,
            ];

            $pdf = Pdf::loadView('pdf.caisse', $datas);
            return $pdf->download("caisse_" . date('d-m-Y') . ".pdf");
        }

        // --- RETURN VIEW ---
        return view("caisses.index", compact(
            'title',
            'totalDu',
            'totalReduction',
            'netAPayer',
            'totalPaye',
            'totalRestant',
            'beneficeVenteDetail',
            'beneficeVenteGros',
            'stockValue',
            'stockQuantity',
            'currency'
        ));
    }

    private function FormatDate($date)
    {
        return date('d/m/Y', strtotime($date));
    }
}