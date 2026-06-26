<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MiniService extends Model
{
    use HasFactory;
    protected $fillable = [
        "nom_service",
        "montant",
        "id_setting",
        "id_boutique"
    ];

    public function StoreMiniService($data)
    {
        $data['id_boutique'] = auth()->user()->id_boutigue ?? session('selected_boutique_id');
        return MiniService::create($data);
    }

    public function getAll()
    {
        $boutiqueId = auth()->user()->id_boutigue ?? session('selected_boutique_id');
        return MiniService::where('id_boutique', $boutiqueId)->get();
    }
    public function getAllLatest()
    {
        $boutiqueId = auth()->user()->id_boutigue ?? session('selected_boutique_id');
        return MiniService::where('id_boutique', $boutiqueId)->latest()->limit(10)->get();
    }
    public function deleteMiniService($id)
    {
        return MiniService::find($id)->delete();
    }

    public function updateMiniService($id, $data)
    {
        return MiniService::find($id)->update($data);
    }

}