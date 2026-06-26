<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
    use HasFactory;

    protected $fillable = [
        "nom_type",
        "categorie",
        "id_setting",
    ];

    public function StoreType($data)
    {
        return Type::create($data);
    }

    public function getAll()
    {
        return Type::all();
    }
    public function getAllLatest()
    {
        return Type::lastes()->limit(10)->get();
    }
    public function deleteType($id)
    {
        return Type::find($id)->delete();
    }

    public function updateType($id, $data)
    {
        return Type::find($id)->update($data);
    }
}
