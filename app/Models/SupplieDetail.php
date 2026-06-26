<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplieDetail extends Model
{
    use HasFactory;
    protected $fillable = [
        "supplie_id",
        "id_prod",
        "id_cat",
        "qte_commander",
        "qte_valider",
        "prix",
        "montant",
        "prix_detail",
        "total_detail",
        "prix_gros",
        "total_gros",
        "date_expiration"
    ];

    public function getProductNom($id){
        $produit = Stock::find($id);
        return $produit->libelle;
    }
}
