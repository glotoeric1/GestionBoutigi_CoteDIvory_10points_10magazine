<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VenteIndirect extends Model
{
    use HasFactory;
    protected $fillable = [
        "nom",
        "contact",
        "clientId",
        "produit",
        "descs",
        "qte",
        "montant",

        "prix_init",
        "dates",
        "done_by",
        "montantPay",

        "total_ht",
        "tva",
        "total_tva",
        "total_ttc",
        'id_setting',
        "id_boutique"

    ];


    public function ShowNameVenteIndirect($id)
    {
        if ($id == "") {
            return "Non";
        }
        return trim(Produit::where('id', $id)->where('id_setting', auth()->user()->id_setting)->pluck("nom_produit"), '[""]');
    }

    public function CalculateSum($clientId)
    {
        $total = VenteIndirect::where('clientId', $clientId)
            ->where('id_setting', auth()->user()->id_setting)
            ->distinct()
            ->sum("montantPay");

        return $total;
    }

    public function StoreVenteIndirect($data)
    {
        $data['id_boutique'] = auth()->user()->id_boutigue ?? session('selected_boutique_id');
        return VenteIndirect::create($data);
    }

    public function getAll()
    {
        $boutiqueId = auth()->user()->id_boutigue ?? session('selected_boutique_id');
        return VenteIndirect::where('id_setting', auth()->user()->id_setting)
            ->where("id_boutique", $boutiqueId)
            ->get();
    }
    public function getAllLatest()
    {
        $boutiqueId = auth()->user()->id_boutigue ?? session('selected_boutique_id');
        return VenteIndirect::where('id_setting', auth()->user()->id_setting)
            ->where("id_boutique", $boutiqueId)
            ->latest()->limit(10)->get();
    }
    public function deleteVenteIndirect($id)
    {
        return VenteIndirect::find($id)->delete();
    }

    public function updateVenteIndirect($id, $data)
    {
        return VenteIndirect::find($id)->update($data);
    }

    public function generatePayId()
    {
        $count = VenteIndirect::count() + 1;
        return "VI-" . date('Y') . '-' . str_pad($count, 6, '0', STR_PAD_LEFT);
        // $generateNumber = date("y") . date("m") . time() . mt_rand(99, 999) . mt_rand(9999, 99999);
        // return $generateNumber;
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

    public function ShowPriceAchat($id)
    {
        if ($id == "") {
            return "Non";
        }
        return trim(Produit::where('id', $id)->pluck("prix_achat"), '[""]');
    }

    public function CalculerBenefice($id)
    {
        $data = VenteIndirect::where('id', $id)->first();
        return (int) $data->montant - (int) $data->prix_init;
    }

}