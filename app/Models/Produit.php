<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produit extends Model
{
    use HasFactory;

    protected $fillable = [
        "code_barre",
        "id_categorie",
        "id_fournisseur",
        "quantite",
        "prix_achat",
        "Total_achat",
        "Total_en_gros",
        "Total_benefice_en_gros",
        "Total_en_detail",
        "Total_benefice_en_detail",
        "options_barcode",
        "qte_par_carton",
        "qte_total_en_detail",
        "prix_vente_en_gros",
        "prix_vente_unitaire",
        "id_prod",
        "username",
        "date_expiration",
        "id_setting",
    ];
    public function StoreProduit($data)
    {
        return Produit::create($data);
    }

    public function getAll()
    {
        return Produit::where('id_setting', auth()->user()->id_setting)->get();
    }
    public function getAllLatest()
    {
        return Produit::where('id_setting', auth()->user()->id_setting)->latest()->limit(100)->get();
    }
    public function deleteProduit($id)
    {
        return Produit::find($id)->delete();
    }

    public function updateProduit($id, $data)
    {
        return Produit::find($id)->update($data);
    }

    public function ShowProdName($id){
        $produit = Stock::find($id);
        return $produit->libelle;
    }

    public function FormatDate($date)
    {
        return date('d/m/Y', strtotime($date));
    }





}