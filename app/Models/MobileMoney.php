<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MobileMoney extends Model
{
    use HasFactory;
    protected $fillable = [
        "contact",
        "types",
        "service",
        "montant",
        "descs",
        "id_setting",
        "id_boutique"
    ];

    public function StoreMobileMoney($data)
    {
        $data['id_boutique'] = auth()->user()->id_boutigue ?? session('selected_boutique_id');
        return MobileMoney::create($data);
    }

    public function getAll()
    {
        $boutiqueId = auth()->user()->id_boutigue ?? session('selected_boutique_id');
        return MobileMoney::where('id_boutique', $boutiqueId)->get();
    }
    public function getAllLatest()
    {
        $boutiqueId = auth()->user()->id_boutigue ?? session('selected_boutique_id');
        return MobileMoney::where('id_boutique', $boutiqueId)->latest()->limit(10)->get();
    }
    public function deleteMobileMoney($id)
    {
        return MobileMoney::find($id)->delete();
    }

    public function updateMobileMoney($id, $data)
    {
        return MobileMoney::find($id)->update($data);
    }
}