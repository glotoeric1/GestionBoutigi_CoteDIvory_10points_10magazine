<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supply extends Model
{
    use HasFactory;

    protected $fillable = [
        "numero_commande",
        "operation",
        "total_ht",
        "id_fournisseur",
        "dates",
        "restant",
        "id_user",
        "statut",
        "fraisTransit",
        "fraisLogistique",
        "montantDonner",
        "id_setting",
    ];

    public function StoreSupply($data)
    {
        return self::create($data);
    }

    public function getAll()
    {
        return Supply::where('id_setting', auth()->user()->id_setting)->get();
    }
    public function getAllLatest()
    {
        return Supply::where('id_setting', auth()->user()->id_setting)->latest()->limit(10)->get();
    }
    public function deleteSupply($id)
    {
        return Supply::find($id)->delete();
    }

    public function updateSupply($id, $data)
    {
        return Supply::find($id)->update($data);
    }

    public function FormatHour($date)
    {
        return date('H:i:s', strtotime($date));
    }

    public function FormatDate($date)
    {
        return date('d/m/Y', strtotime($date));
    }

    public function generateTransactionId()
    {
        $generateNumber = date("y") . date("m") . time() . mt_rand(99, 999) . mt_rand(9999, 99999);
        return $generateNumber;
    }

    public static function generateNumCMD($type)
    {
        $count = Supply::count() + 1;
        return "{$type}-" . date('Y') . '-' . str_pad($count, 6, '0', STR_PAD_LEFT);
    }

    public function ShowFournisseurName($id)
    {
        if ($id == "") {
            return "Non";
        }
        return Fournisseur::find($id)->nom_fournisseur;
    }

    public function ShowUserName($id)
    {
        if ($id == "") {
            return "Non";
        }
        return User::find($id)->name;
    }

    public function getTotalDonner($id){
        $paiementCmds = PaiementCmd::where('id_commande', $id)->get();
        return $paiementCmds->sum('montant');
    }

    public function getSupplyDetail($id)
    {
        // $detailCmds = SupplieDetail::where('supplie_id', $id)->get();
        $paiementCmds = PaiementCmd::where('id_commande', $id)->get();
        return $paiementCmds;
    }

    public function getNbreCmdDetail($id){
        return SupplieDetail::where('supplie_id', $id)->count();
    }

    public function generateUniqueProductId()
    {
        $generateNumber = date("y") . date("m") . time() . mt_rand(99, 999) . mt_rand(9999, 99999);
        return $generateNumber;
    }

}