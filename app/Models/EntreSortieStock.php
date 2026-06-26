<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EntreSortieStock extends Model
{
    use HasFactory;

    protected $fillable = [
        "produit",
        "id_prod",
        "user_name",
        "qte_en_stock",
        "qte",
        "num_charge",
        'service',
        'operation',
        'stock_id',
        'id_setting',
        "id_boutique"
    ];
    public function StoreEntreSortieStock($data)
    {
        $data['id_boutique'] = auth()->user()->id_boutigue ?? session('selected_boutique_id');
        return EntreSortieStock::updateOrCreate($data);
    }

    public function getAll()
    {
        $boutiqueId = auth()->user()->id_boutigue ?? session('selected_boutique_id');
        return EntreSortieStock::where('id_boutique', $boutiqueId)->get();
    }
    public function getAllLatest()
    {
        $boutiqueId = auth()->user()->id_boutigue ?? session('selected_boutique_id');
        return EntreSortieStock::where('id_boutique', $boutiqueId)->orderBy('id', 'desc')->limit(500)->get();
    }
    public function deleteEntreSortieStock($id)
    {
        return EntreSortieStock::find($id)->delete();
    }

    public function updateEntreSortieStock($id, $data)
    {
        return EntreSortieStock::find($id)->update($data);
    }

    public function FormatDate($date)
    {
        return date('d/m/Y', strtotime($date));
    }

    public function QteEnStock($id_prod)
    {
        if ($id_prod == "") {
            return 0;
        }
        $data = EntreSortieStock::where('id_prod', $id_prod)->latest()->first();
        if ($data) {
            return $data->qte_en_stock;
        }

        return 0;
    }
}