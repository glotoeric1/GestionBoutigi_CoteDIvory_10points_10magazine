<?php

namespace App\Models;

use App\Models\Client;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientMouvement extends Model
{
    use HasFactory;
    protected $fillable = [
        'num_mouvement',
        'client_id',
        'type_mouvement',
        'total',
        'montant_payer',
        'montant_credit',
        'montant_restant',
        'invoice_id',
        'id_setting',
    ];

    public function getClt($id)
    {
        if ($id != null && $id != "") {
            $data = Client::find($id);
            if ($data) {
                return $data;
            }
            return null;
        }
        return null;
    }

    public static function numMouvement()
    {
        $count = ClientMouvement::count() + 1;
        return 'D-' . str_pad($count, 4, '0', STR_PAD_LEFT);
    }
}