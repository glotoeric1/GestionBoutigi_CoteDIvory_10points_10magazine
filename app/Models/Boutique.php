<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Boutique extends Model
{
    use HasFactory;

    protected $fillable = [
        "gerant_boutique",
        "nom_boutique",
        "adresse",
        "contact",
        "contact_gerant",
        "type",
        "id_setting",
        "logo"
    ];

    public function StoreBoutique($data)
    {
        return Boutique::create($data);
    }

    public function getAll()
    {
        return Boutique::latest()->get();
    }
    public function getAllLatest()
    {
        return Boutique::lastes()->limit(200)->get();
    }
    public function deleteBoutique($id)
    {
        return Boutique::find($id)->delete();
    }

    public function updateBoutique($id, $data)
    {
        return Boutique::find($id)->update($data);
    }

    //Get Entreprise name based on id

    public function getEntrepriseName($id)
    {
        if (empty($id)) {
            return "No Entreprise";
        }
        return settings::find($id)->first();
    }


}