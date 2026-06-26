<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoryTransfer extends Model
{
    use HasFactory;

    //Add the filliable 
    const STATUS_COMPLETED = 'Completed';
    const STATUS_CANCELLED = 'Cancelled';
    protected $fillable = [
        "id_prod",
        "quantite",
        "qte",
        "prix_achat",
        "username",
        'id_setting',
        'id_boutique',
        'entrepot_id',
    ];


    //Add the relationship 

    //Generate the relation for each foreign key 

    public function StoreHistoryTransfer($data)
    {
        return HistoryTransfer::create($data);
    }

    public function getAll()
    {
        return HistoryTransfer::where('id_setting', auth()->user()->id_setting)->get();
    }
    public function getAllLatest()
    {
        return HistoryTransfer::where('id_setting', auth()->user()->id_setting)->lastes()->limit(10)->get();
    }
    public function deleteHistoryTransfer($id)
    {
        return HistoryTransfer::find($id)->delete();
    }

    public function updateHistoryTransfer($id, $data)
    {
        return HistoryTransfer::find($id)->update($data);
    }

    public function ShowUserName($id)
    {
        if ($id == "") {
            return "Non";
        }
        return User::find($id)->name;
    }

    public function showBoutique($id)
    {
        if ($id == "") {
            return "Non";
        }
        return Boutique::find($id);
    }

    public function showEntrepot($id)
    {
        if ($id == "") {
            return "Non";
        }
        return Entrepot::find($id);
    }

    public function ShowProdName($id){
        $produit = Stock::find($id);
        return $produit->libelle;
    }




}