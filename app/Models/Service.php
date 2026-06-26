<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        "nom",
        "clientId",
        "titre",
        "descs",
        "qte",
        "montant",
        "reduction",
        "restant",
        "dates",
        "done_by",
        "montantPay",

        "total_ht",
        "tva",
        "total_tva",
        "total_ttc",
        "id_setting",
        "id_boutique"
    ];

    public function CalculateSum($clientId)
    {
        $total = Service::where('clientId', $clientId)
            ->distinct()
            ->sum("montantPay");

        return $total;
    }

    public function StoreService($data)
    {
        $data['id_boutique'] = auth()->user()->id_boutigue ?? session('selected_boutique_id');
        return Service::create($data);
    }

    public function getAll()
    {
        $boutiqueId = auth()->user()->id_boutigue ?? session('selected_boutique_id');
        return Service::where('id_boutique', $boutiqueId)->get();
    }
    public function getAllLatest()
    {
        $boutiqueId = auth()->user()->id_boutigue ?? session('selected_boutique_id');
        return Service::where('id_boutique', $boutiqueId)->latest()->limit(500)->get();
    }
    public function deleteService($id)
    {
        return Service::find($id)->delete();
    }

    public function updateService($id, $data)
    {
        return Service::find($id)->update($data);
    }

    public function generatePayId()
    {
        $generateNumber = date("y") . date("m") . time() . mt_rand(99, 999) . mt_rand(9999, 99999);
        // call the same function if the code exists already
        return $generateNumber;
    }

    public function ShowUserNameAvance($id)
    {
        if ($id == "") {
            return "Non";
        }
        return trim(User::where('id', $id)->pluck("name"), '[""]');
    }

    public function ShowClientNameAvance($id)
    {
        if ($id == "") {
            return "Non";
        }
        return trim(User::where('id', $id)->pluck("name"), '[""]');
    }

    public function FormatDate($date)
    {
        return date('d/m/Y', strtotime($date));
    }

    public function FormatHour($date)
    {
        return date('H:i:s', strtotime($date));
    }

}