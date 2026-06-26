<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VenteDetail extends Model
{
    use HasFactory;
    protected $fillable = [
        "vente_id",
        "code_barre",
        "id_prod",
        "options",
        "prix",
        "quantite",
        "montant",
        "valider",
        "categorie",
        "stock_id",
        "client_id",
    ];

    public function ShowProdNameVente($id)
    {
        if ($id == "") {
            return "Non";
        }
        return Stock::find($id)->libelle;
    }

    public function ShowEntrepotName($id)
    {
        if ($id == "") {
            return "Non";
        }
        return Entrepot::find($id)->nom_entrepot;
    }

    public function vente()
    {
        return $this->belongsTo(Vente::class, 'vente_id');
    }
}