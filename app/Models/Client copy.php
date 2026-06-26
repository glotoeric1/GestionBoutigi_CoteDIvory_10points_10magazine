<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;
    protected $fillable = [
        "nom",
        "contact",
        "adresse",
        "email",
        "solde",
        "types",
        "id_setting",
    ];
    public function StoreClient($data)
    {
        $data['id_boutique'] = auth()->user()->id_boutigue ?? session('selected_boutique_id');
        return Client::create($data);
    }
    public function getAll()
    {
        return Client::latest()->get(); //get()
    }

    public function getAllLatest()
    {
        $boutiqueId = auth()->user()->id_boutigue ?? session('selected_boutique_id');
        return Client::latest()->limit(10)->get();
    }

    public function deleteClient($id)
    {
        return Client::find($id)->delete();
    }

    public function updateClient($id, $data)
    {
        return Client::find($id)->update($data);
    }

    public function getSoldeByCustomer($id){
        $historiques = ClientMouvement::where('client_id', $id)->latest()->get();
        $depot = $historiques->where('type_mouvement', 'depot')->sum('montant');
        $retrait = $historiques->where('type_mouvement', 'retrait')->sum('montant');
        $solde = $depot - $retrait;
        return $solde;
    }

}