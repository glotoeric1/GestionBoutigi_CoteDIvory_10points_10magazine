<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Entrepot extends Model
{
    use HasFactory;
    protected $fillable = [
        "nom_entrepot",
        "id_setting",
        "id_boutique"
    ];

    //Get the total quantity of products in the entrepot using the id_entrepot with eloquent
    public function produits()
    {
        //Get the total quantity of products in the entrepot using the id_entrepot with eloquent
        $data = Produit::where("id_entrepot", $this->id)->sum("quantite");
        return $data;

    }

    public function findBoutique($id)
    {
        if ($id) {
            $boutique = Boutique::find($id);
            if ($boutique) {
                return $boutique->nom_boutique . ' - ' . $boutique->contact;
            }
        } else {
            return null;
        }
    }
}