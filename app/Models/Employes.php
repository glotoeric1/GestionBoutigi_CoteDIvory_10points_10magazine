<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employes extends Model
{
    use HasFactory;

    protected $fillable = [
        "nom",
        "contact",
        "adresse",
        "post",
        "salaire",
        "dateStart",
        "dateEnd",
        "emergency_name",
        "relationship",
        "contact_joint",
        "id_setting",
        "id_boutique"
    ];

    public function StoreEmployes($data)
    {
        $data['id_boutique'] = auth()->user()->id_boutigue ?? session('selected_boutique_id');
        return Employes::create($data);
    }

    public function getAll()
    {
        $boutiqueId = auth()->user()->id_boutigue ?? session('selected_boutique_id');
        return Employes::where('id_boutique', $boutiqueId)->get();
    }
    public function getAllLatest()
    {
        $boutiqueId = auth()->user()->id_boutigue ?? session('selected_boutique_id');
        return Employes::where('id_boutique', $boutiqueId)->lastes()->limit(10)->get();
    }
    public function deleteEmployes($id)
    {
        return Employes::find($id)->delete();
    }

    public function updateEmployes($id, $data)
    {
        return Employes::find($id)->update($data);
    }

}
