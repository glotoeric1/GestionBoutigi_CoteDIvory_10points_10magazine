<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fournisseur extends Model
{
    use HasFactory;

    protected $fillable = [
        "nom_fournisseur",
        "adresse_fournisseur",
        "contact_fournisseur",
        "email_fournisseur",
        "id_setting",
    ];
    public function StoreFournisseur($data)
    {
        return Fournisseur::create($data);
    }

    public function getAll()
    {
        return Fournisseur::where('id_setting', auth()->user()->id_setting)->get();
    }
    public function getAllLatest()
    {
        return Fournisseur::where('id_setting', auth()->user()->id_setting)->lastes()->limit(10)->get();
    }
    public function deleteFournisseur($id)
    {
        return Fournisseur::find($id)->delete();
    }

    public function updateFournisseur($id, $data)
    {
        return Fournisseur::find($id)->update($data);
    }

    public function getSupply($id)
    {
        $detailCmds = Supply::where('id_fournisseur', $id)->get();
        // $paiementCmds = PaiementCmd::where('id_commande', $id)->get();
        return $detailCmds;
    }

    public function getTotalDonner($id){
        $supply = Supply::where('id_fournisseur', $id)->first();
        $paiementCmds = PaiementCmd::where('id_commande', $id)->get();
        return $paiementCmds->sum('montant');
    }

}
