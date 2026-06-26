<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\VenteDetail;
use App\Models\ProductBoutigue;

class Vente extends Model
{
    use HasFactory;

    protected $fillable = [
        "num_vente",
        "restant",
        "client_id",
        "total_ht",
        "tva",
        "total_tva",
        "total_ttc",
        "montantDonner",
        "username",
        "reduction",
        "id_setting",
        'id_boutique',
    ];

    public function detail_venteCount($id)
    {
        if ($id == "") {
            return "Non";
        }

        $detail = VenteDetail::where('vente_id', $id)->get()->count();
        return $detail;
    }

    public function detail_ventes($id)
    {
        if ($id == "") {
            return "Non";
        }

        $detail = VenteDetail::where('vente_id', $id)->get();
        return $detail;
    }

    public function StoreVente($data)
    {
        return self::create($data);
    }

    public function getAll()
    {
        $boutiqueId = auth()->user()->id_boutigue ?? session('selected_boutique_id');
        if (auth()->user()->roles == "Vendeur") {
            return Vente::where('username', auth()->user()->id)->latest()->get();
        }
        return Vente::where('id_boutique', $boutiqueId)->latest()->get();
    }

    /*
    public function getAllLatestMois(){
    return Vente::where("created_ad")->latest()->limit(100)->get();
    }*/
    public function getAllLatestN($num)
    {
        $boutiqueId = auth()->user()->id_boutigue ?? session('selected_boutique_id');
        if (auth()->user()->roles == "Vendeur") {
            return Vente::where('username', auth()->user()->id)->latest()->limit($num)->get();
        }
        return Vente::where('id_boutique', $boutiqueId)->latest()->limit($num)->get();
    }
    public function getAllLatest100()
    {
        $boutiqueId = auth()->user()->id_boutigue ?? session('selected_boutique_id');
        if (auth()->user()->roles == "Vendeur") {
            return Vente::where('username', auth()->user()->id)->latest()->limit(100)->get();
        }
        return Vente::where('id_boutique', $boutiqueId)->latest()->limit(100)->get();
    }
    public function getAllLatest()
    {
        $boutiqueId = auth()->user()->id_boutigue ?? session('selected_boutique_id');
        if (auth()->user()->roles == "Vendeur") {
            return Vente::where('username', auth()->user()->id)->latest()->limit(100)->get();
        }
        return Vente::where('id_boutique', $boutiqueId)->latest()->limit(100)->get();
    }
    public function deleteVente_old($id)
    {
        return Vente::find($id)->delete();
    }

    public function getOneProduct($id)
    {
        return Vente::find($id);
    }

    public function updateVente($id, $data)
    {
        return Vente::find($id)->update($data);
    }

    public function generateClientId()
    {
        $generateNumber = date("y") . date("m") . time() . mt_rand(99, 999) . mt_rand(9999, 99999);
        return $generateNumber;
    }

    public static function generateNumVente($type)
    {
        $count = Vente::count() + 1;
        return "{$type}-" . date('Y') . '-' . str_pad($count, 6, '0', STR_PAD_LEFT);
    }

    public function ShowPriceAchat($id)
    {

        if ($id == "") {
            return "Non";
        }
        return trim(Produit::where('id_prod', $id)->pluck("prix_achat"), '[""]');
    }

    public function ShowUserNameVente($id)
    {
        if ($id == "") {
            return "Non";
        }
        return User::find($id)->name;
    }

    public function FormatDate($date)
    {
        return date('d/m/Y', strtotime($date));
    }

    public function CalculerBenefice($id, $type = 'detail')
    {
        $data = Produit::where('id_prod', $id)->first();
        if ($type == "gros") {
            if (!empty($data->prix_vente_en_gros)) {
                return (int) $data->prix_vente_en_gros - (int) $data->prix_achat;
            }
        }
        if (!empty($data->prix_vente_unitaire)) {
            return (int) $data->prix_vente_unitaire - (int) $data->prix_achat;
        }
        return 0;
    }

    public function getClt($id)
    {
        if ($id != null && $id != "") {
            $data = Client::find($id);
            if ($data) {
                return $data;
            } else {
                return null;
            }
        } else {
            return null;
        }
    }

    public function getBoutiqueName($id)
    {
        $boutique = Boutique::find($id);
        return $boutique->nom_boutique;
    }

    public function deleteVente()
    {
        return DB::transaction(function () {

            foreach ($this->items as $item) {

                ProductBoutigue::updateStock(
                    $item->id_prod,
                    $this->id_boutique,
                    $item->quantite,
                    'add'
                );
            }

            $this->items()->delete();

            return $this->delete();
        });
    }

    public function items()
    {
        return $this->hasMany(VenteDetail::class, 'vente_id');
    }

    public function deleteItemAndRestock($venteDetailId)
    {
        return DB::transaction(function () use ($venteDetailId) {

            // 1. Find the item
            //dd($this->id);
            $item = VenteDetail::where('id', $venteDetailId)
                ->where('vente_id', $this->id)
                ->first();
            //dd($item);

            if (!$item) {
                return false; // item not found
            }

            $boutiqueId = $this->id_boutique;

            // 2. Restore stock (reverse sale)
            ProductBoutigue::updateStock(
                $item->id_prod,
                $boutiqueId,
                $item->quantite,
                'add'
            );

            // 3. Delete only this item
            $item->delete();

            // Recalculate sale totals
            $this->recalculateTotals();

            return true;
        });
    }

    public function recalculateTotals()
    {
        $totalHt = $this->items()->sum('montant');

        $reduction = $this->reduction ?? 0;

        $totalHtAfterReduction = max(
            0,
            $totalHt - $reduction
        );

        $tvaRate = $this->tva ?? 0;

        $totalTva = ($totalHtAfterReduction * $tvaRate) / 100;

        $totalTtc = $totalHtAfterReduction + $totalTva;

        $restant = max(
            0,
            $totalTtc - ($this->montantDonner ?? 0)
        );

        $this->update([
            'total_ht' => $totalHtAfterReduction,
            'total_tva' => $totalTva,
            'total_ttc' => $totalTtc,
            'restant' => $restant,
        ]);
    }
}