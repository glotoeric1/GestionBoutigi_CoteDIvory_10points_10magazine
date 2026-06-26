<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompteBancaire extends Model
{
    use HasFactory;

    protected $fillable = [
        "numero",
        "bank",
        "type",
        "titulaire",
        "id_setting",
        "id_boutique"
    ];

    public function StoreCompteBancaire($data)
    {
        $data['id_boutique'] = auth()->user()->id_boutigue ?? session('selected_boutique_id');
        return CompteBancaire::create($data);
    }

    public function getAll()
    {
        $boutiqueId = auth()->user()->id_boutigue ?? session('selected_boutique_id');
        return CompteBancaire::where('id_boutique', $boutiqueId)->get();
    }
    public function getAllLatest()
    {
        $boutiqueId = auth()->user()->id_boutigue ?? session('selected_boutique_id');
        return CompteBancaire::where('id_boutique', $boutiqueId)->lastes()->limit(10)->get();
    }
    public function deleteCompteBancaire($id)
    {
        return CompteBancaire::find($id)->delete();
    }

    public function updateCompteBancaire($id, $data)
    {
        return CompteBancaire::find($id)->update($data);
    }
}