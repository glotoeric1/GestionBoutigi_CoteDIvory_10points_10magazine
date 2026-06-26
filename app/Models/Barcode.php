<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barcode extends Model
{
    use HasFactory;
    protected $fillable = [
        "barcode",
        "id_setting",
        "id_boutique"
    ];

    public function StoreBarcode($data)
    {
        $data['id_boutique'] = auth()->user()->id_boutigue ?? session('selected_boutique_id');
        return Barcode::create($data);
    }

    public function getAll()
    {
        $boutiqueId = auth()->user()->id_boutigue ?? session('selected_boutique_id');
        return Barcode::where('id_boutique', $boutiqueId)->get();
    }
    public function getAllLatest()
    {
        $boutiqueId = auth()->user()->id_boutigue ?? session('selected_boutique_id');
        return Barcode::where('id_boutique', $boutiqueId)->latest()->limit(100)->get();
    }
    public function deleteBarcode($id)
    {
        return Barcode::find($id)->delete();
    }

    public function updateBarcode($id, $data)
    {
        return Barcode::find($id)->update($data);
    }

    public function generateBarcode()
    {
        $generateNumber = date("H") . mt_rand(11, 59) . mt_rand(60, 99);
        /*
            $verifyCode = Barcode::where('barcode', $generateNumber)->first();
            // call the same function if the code exists already
            if ($verifyCode) {
                return $this->generateBarcode();
            }
        */
        return $generateNumber;
    }
}