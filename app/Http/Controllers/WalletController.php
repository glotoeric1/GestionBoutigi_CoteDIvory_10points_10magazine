<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;
use App\Models\WalletTransaction;
use Illuminate\Support\Facades\DB;

class WalletController extends Controller
{
    // LIST TRANSACTIONS (ledger)
    public function index($clientId)
    {
        $client = Client::findOrFail($clientId);

        $datas = WalletTransaction::where('client_id', $clientId)
            ->orderBy('id', 'desc')
            ->get();

        $total = $datas->sum('montant');

        return view('wallet.index', compact('client', 'datas', 'total'));
    }

    // DEPOSIT FORM
    public function create($clientId)
    {
        $client = Client::findOrFail($clientId);

        return view('wallet.create', compact('client'));
    }

    // STORE TRANSACTION (DEPOSIT / WITHDRAW)
    public function store(Request $request, $clientId)
    {
        $request->validate([
            'type' => 'required',
            'montant' => 'required|numeric|min:1',
        ]);

        $client = Client::findOrFail($clientId);

        return DB::transaction(function () use ($request, $client) {

            $soldeAvant = $client->wallet_balance;

            // CALCUL
            if ($request->type == 'depot' || $request->type == 'remboursement') {
                $soldeApres = $soldeAvant + $request->montant;
            } else {
                if ($soldeAvant < $request->montant) {
                    return back()->with('error', 'Solde insuffisant');
                }
                $soldeApres = $soldeAvant - $request->montant;
            }

            // UPDATE CLIENT BALANCE
            $client->wallet_balance = $soldeApres;
            $client->save();

            // SAVE TRANSACTION
            WalletTransaction::create([
                'id_setting' => auth()->user()->id_setting ?? null,
                'client_id' => $client->id,
                'type' => $request->type,
                'montant' => $request->montant,
                'solde_avant' => $soldeAvant,
                'solde_apres' => $soldeApres,
                'description' => $request->description,
            ]);

            return redirect()->route('wallet.index', $client->id)
                ->with('succes', 'Opération effectuée avec succès');
        });

    }
}