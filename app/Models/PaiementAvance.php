<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaiementAvance extends Model
{
    use HasFactory;

    protected $fillable = [
        "nom",
        "contact",
        "clientId",
        "titre",
        "descs",
        "qte",
        "montant",
        "total",
        "restant",
        "dates",
        "done_by",
        "montantPay",
        "id_prod",
        "total_ht",
        "tva",
        "total_tva",
        "total_ttc",
        "id_setting",
        "id_boutique"
    ];

    public function ShowNameAvance($id)
    {
        if ($id == "") {
            return "Non";
        }
        return Stock::find($id)->libelle;
    }

    public function CalculateSum($clientId)
    {
        $total = PaiementAvance::where('clientId', $clientId)
            ->distinct()
            ->sum("montantPay");

        return $total;
    }

    public function StorePaiementAvance($data)
    {
        $data['id_boutique'] = auth()->user()->id_boutigue ?? session('selected_boutique_id');
        return PaiementAvance::create($data);
    }

    public function getAll()
    {
        $boutiqueId = auth()->user()->id_boutigue ?? session('selected_boutique_id');
        return PaiementAvance::where('id_boutique', $boutiqueId)->get();
    }
    public function getAllLatest()
    {
        $boutiqueId = auth()->user()->id_boutigue ?? session('selected_boutique_id');
        return PaiementAvance::where('id_boutique', $boutiqueId)->lastes()->limit(10)->get();
    }
    public function deletePaiementAvance($id)
    {
        return PaiementAvance::find($id)->delete();
    }

    public function updatePaiementAvance($id, $data)
    {
        return PaiementAvance::find($id)->update($data);
    }

    public function generatePayId()
    {
        $generateNumber = date("y") . date("m") . time() . mt_rand(99, 999) . mt_rand(9999, 99999);
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

    public function ShowPriceAchat($id)
    {
        if ($id == "") {
            return "Non";
        }
        return trim(ProductBoutigue::where('id', $id)->pluck("prix_achat"), '[""]');
    }

    public function ShowUserNameVente($id)
    {
        if ($id == "") {
            return "Non";
        }
        return User::find($id)->name;
    }

    public function CalculerBenefice($id)
    {
        $data = ProductBoutigue::where('id_prod', $id)->first();
        if (!empty($data->prix_vente_unitaire)) {
            return (int) $data->prix_vente_unitaire - (int) $data->prix_achat;
        }
        return 0;
    }

}