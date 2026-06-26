<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    use HasFactory;

    protected $fillable = [
        "numero_de_compte",
        "operation",
        "montant",
        "montant_retrait",
        "montant_depot",
        "montant_remise",
        "done_by",
        "dates",
        "descs",
        "id_user",
        "numero",
        "id_setting",
        "id_boutique"
    ];

    public function StoreBank($data)
    {
        $data['id_boutique'] = auth()->user()->id_boutigue ?? session('selected_boutique_id');
        return Bank::create($data);
    }

    public function getAll()
    {
        $boutiqueId = auth()->user()->id_boutigue ?? session('selected_boutique_id');
        return Bank::where('id_boutique', $boutiqueId)->get();
    }
    public function getAllLatest()
    {
        $boutiqueId = auth()->user()->id_boutigue ?? session('selected_boutique_id');
        return Bank::where('id_boutique', $boutiqueId)->latest()->limit(200)->get();
    }
    public function deleteBank($id)
    {
        return Bank::find($id)->delete();
    }

    public function updateBank($id, $data)
    {
        return Bank::find($id)->update($data);
    }

    public function FormatHour($date)
    {
        return date('H:i:s', strtotime($date));
    }

    public function FormatDate($date)
    {
        return date('d/m/Y', strtotime($date));
    }

    public function ShowName($numero_de_compte)
    {
        if ($numero_de_compte == "") {
            return "Non";
        }
        return trim(CompteBancaire::where('numero', $numero_de_compte)->pluck("titulaire"), '[""]');
    }

    public function ShowBank($numero_de_compte)
    {
        if ($numero_de_compte == "") {
            return "Non";
        }
        return trim(CompteBancaire::where('numero', $numero_de_compte)->pluck("bank"), '[""]');
    }

    public function ShowType($numero_de_compte)
    {
        if ($numero_de_compte == "") {
            return "Non";
        }
        return trim(CompteBancaire::where('numero', $numero_de_compte)->pluck("type"), '[""]');
    }

}