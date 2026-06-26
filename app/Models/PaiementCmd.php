<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaiementCmd extends Model
{
    use HasFactory;
    protected $fillable = [
        "id_commande",
        "numero_commande",
        "montant",
        'date_paiement',
        'commentaire',
    ];

    public function getFrs($id)
    {
        if ($id != null && $id = "") {
            $data = Fournisseur::find($id);
            if ($data) {
                return $data;
            } else {
                return null;
            }
        } else {
            return null;
        }
    }
}
