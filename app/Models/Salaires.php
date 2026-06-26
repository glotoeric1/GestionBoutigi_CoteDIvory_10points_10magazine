<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Salaires extends Model
{
    use HasFactory;

    protected $fillable = [
        "emp_id",
        "montantRecu",
        "montantRestant",
        "years",
        "mois",
        "done_by",
        "bonus",
        "salaire",
        "pay_number",
        "id_setting",
        "id_boutique"
    ];

    public function ShowName($id)
    {
        if ($id == "") {
            return "Non";
        }
        return trim(Employes::where('id', $id)->pluck("nom"), '[""]');
    }

    public function StoreSalaires($data)
    {
        $data['id_boutique'] = auth()->user()->id_boutigue ?? session('selected_boutique_id');
        return Salaires::create($data);
    }

    public function getAll()
    {
        $boutiqueId = auth()->user()->id_boutigue ?? session('selected_boutique_id');
        return Salaires::where('id_boutique', $boutiqueId)->get();
    }
    public function getAllLatest()
    {
        $boutiqueId = auth()->user()->id_boutigue ?? session('selected_boutique_id');
        return Salaires::where('id_boutique', $boutiqueId)->lastes()->limit(10)->get();
    }
    public function deleteSalaires($id)
    {
        return Salaires::find($id)->delete();
    }

    public function updateSalaires($id, $data)
    {
        return Salaires::find($id)->update($data);
    }

    public function generatePayId()
    {
        $generateNumber = date("y") . date("m") . time() . mt_rand(99, 999) . mt_rand(9999, 99999);
        return $generateNumber;
    }

    public function FormatDate($date)
    {
        return date('d/m/Y', strtotime($date));
    }

}