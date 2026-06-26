<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Depenses extends Model
{
    use HasFactory;

    protected $fillable = [
        "id_user",
        "titre",
        "descs",
        "montant",
        "dates",
        "done_by",
        "numero",
        "id_setting",
        "id_boutique"
    ];

    public function StoreDepenses($data)
    {
        $data['id_boutique'] = auth()->user()->id_boutigue ?? session('selected_boutique_id');
        return Depenses::create($data);
    }

    public function getAll()
    {
        $boutiqueId = auth()->user()->id_boutigue ?? session('selected_boutique_id');
        return Depenses::where('id_boutique', $boutiqueId)->get();
    }
    public function getAllLatest()
    {
        $boutiqueId = auth()->user()->id_boutigue ?? session('selected_boutique_id');
        return Depenses::where('id_boutique', $boutiqueId)->lastes()->limit(10)->get();
    }
    public function deleteDepenses($id)
    {
        return Depenses::find($id)->delete();
    }

    public function updateDepenses($id, $data)
    {
        return Depenses::find($id)->update($data);
    }

    public function FormatHour($date)
    {
        return date('H:i:s', strtotime($date));
    }

    public function FormatDate($date)
    {
        return date('d/m/Y', strtotime($date));
    }

    public function generateDepenseId()
    {
        $generateNumber = date("y") . date("m") . time() . mt_rand(99, 999) . mt_rand(9999, 99999);
        return $generateNumber;
    }

}