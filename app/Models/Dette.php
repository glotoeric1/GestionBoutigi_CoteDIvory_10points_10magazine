<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dette extends Model
{
    use HasFactory;

    protected $fillable = [
        "nom",
        "contact",
        "montantDonner",
        "restant",
        "comments",
        "dateApayer",
        "id_prod",
        "total_ht",
        "tva",
        "total_tva",
        "total_ttc",

        "code_barre",
        "nom_produit",
        "prix",
        'cart',
        "quantite",
        "montant",

        "clientId",

        "username",
        "categorie",
        "id_setting",
        "id_boutique"
    ];
    public function StoreDette($data)
    {
        $data['id_boutique'] = auth()->user()->id_boutigue ?? session('selected_boutique_id');
        return Dette::create($data);
    }

    public function getAll()
    {
        $boutiqueId = auth()->user()->id_boutigue ?? session('selected_boutique_id');
        return Dette::where('id_boutique', $boutiqueId)->latest('id')->get();
    }
    public function getAllLatest()
    {
        $boutiqueId = auth()->user()->id_boutigue ?? session('selected_boutique_id');
        return Dette::where('id_boutique', $boutiqueId)->latest()->limit(10)->get();
    }
    public function deleteDette($id)
    {
        return Dette::find($id)->delete();
    }

    public function updateDette($id, $data)
    {
        return Dette::find($id)->update($data);
    }

    public function generateClientId()
    {
        $generateNumber = date("y") . date("m") . time() . mt_rand(99, 999) . mt_rand(9999, 99999);
        return $generateNumber;
    }

    public function ShowProdNameDette($id)
    {

        if ($id == "") {
            return "Non";
        }
        return Stock::find($id)->libelle;
    }

    public function ShowProdNameVente($id)
    {
        if ($id == "") {
            return "Non";
        }
        return trim(ProductBoutigue::where('id_prod', $id)->pluck("nom_produit"), '[""]');
    }

    public function ShowUserNameDette($id)
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

    public function ShowPriceAchat($id)
    {
        if ($id == "") {
            return "Non";
        }
        return trim(ProductBoutigue::where('id_prod', $id)->pluck("prix_achat"), '[""]');
    }

    public function ShowUserNameVente($id)
    {
        if ($id == "") {
            return "Non";
        }
        return trim(User::where('id', $id)->pluck("name"), '[""]');
    }

    public function getOneProduct($id)
    {
        return Dette::find($id);
    }

    public function CalculerBenefice($id, $type = 'detail')
    {
        $data = ProductBoutigue::where('id_prod', $id)->first();
        if ($type == "gros") {
            if (!empty($data->prix_vente_en_gros)) {
                return (int) $data->prix_vente_en_gros - (int) $data->prix_achat;
            }
        }
        if (!empty($data->prix_vente_unitaire)) {
            return (int) $data->prix_vente_unitaire - (int) $data->prix_achat;
        }
        return 0;
    }
}