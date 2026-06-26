<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categorie extends Model
{
    use HasFactory;

    protected $fillable = [
        "nom_categorie",
        "id_setting",
    ];

    public function StoreCategorie($data)
    {
        return Categorie::create($data);
    }

    public function getAll()
    {
        return Categorie::where('id_setting', auth()->user()->id_setting)->get();
    }
    public function getAllLatest()
    {
        return Categorie::where('id_setting', auth()->user()->id_setting)->lastes()->limit(10)->get();
    }
    public function deleteCategorie($id)
    {
        return Categorie::find($id)->delete();
    }

    public function updateCategorie($id, $data)
    {
        return Categorie::find($id)->update($data);
    }
}
