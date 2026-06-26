<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use HasFactory;

    protected $fillable = [
        "libelle",
        "id_setting",
    ];

    public function StoreStock($data)
    {
        return Stock::create($data);
    }

    public function getAll()
    {
        return Stock::where('id_setting', auth()->user()->id_setting)->orderBy('libelle', 'ASC')->get();
    }
    public function getAllLatest()
    {
        return Stock::where('id_setting', auth()->user()->id_setting)->latest()->limit(10)->get();
    }
    public function deleteStock($id)
    {
        return Stock::find($id)->delete();
    }

    public function updateStock($id, $data)
    {
        return Stock::find($id)->update($data);
    }

    public function FormatDate($date)
    {
        return date('d/m/Y', strtotime($date));
    }

    public function findBoutique($id)
    {
        if ($id) {
            $boutique = Boutique::find($id);
            if ($boutique) {
                return $boutique->nom_boutique . ' - ' . $boutique->contact;
            }
        } else {
            return null;
        }
    }
}