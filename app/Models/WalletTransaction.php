<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WalletTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_setting',
        'client_id',
        'type',
        'montant',
        'solde_avant',
        'solde_apres',
        'description',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public static function ajouterOperation(
        $clientId,
        $type,
        $montant,
        $description = null
    ) {
        $client = Client::findOrFail($clientId);

        $soldeAvant = $client->wallet_balance;

        if (in_array($type, ['depot', 'remboursement'])) {
            $soldeApres = $soldeAvant + $montant;
        } elseif (in_array($type, ['retrait', 'paiement', 'ajustement'])) {
            $soldeApres = $soldeAvant - $montant;
        } else {
            $type = "remboursement"; //remboursement => Paiement credit
            $soldeApres = $soldeAvant;
        }

        $client->wallet_balance = $soldeApres;
        $client->save();

        return self::create([
            'id_setting' => auth()->user()->id_setting,
            'client_id' => $clientId,
            'type' => $type,
            'montant' => $montant,
            'solde_avant' => $soldeAvant,
            'solde_apres' => $soldeApres,
            'description' => $description,
        ]);
    }
}