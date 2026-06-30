<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Exception;

class ProductBoutigue extends Model
{
    use HasFactory;

    protected $fillable = [
        "code_barre",
        "id_categorie",
        "id_fournisseur",
        "quantite",
        "prix_achat",
        "prix_vente_en_gros",
        "prix_vente_unitaire",
        "id_prod",
        "username",
        "date_expiration",
        "id_setting",
        "id_boutique",
        "stock_id",
    ];


    public function getMagasin($id)
    {
        return Entrepot::find($id);
    }

    public function get_prodQte($id)
    {
        $product_boutique = ProductBoutigue::where('id_prod', $id)->first();
        return $product_boutique->quantite;
    }

    public function ShowProdName($id)
    {
        if ($id == "") {
            return "Non";
        }
        return Stock::find($id)->libelle;
    }

    //Add a method to "Add" or "Substract" from the ProductBoutigue using prodId, boutiqueId,

    public static function updateStock(
        int $prodId,
        int $boutiqueId,
        float $quantity,
        string $operation = 'add'
    ): self {

        if ($quantity <= 0) {
            throw new Exception("La quantité doit être supérieure à zéro.");
        }

        dd('Product Id : ' . $prodId, ' Boutigi : ' . $boutiqueId);
        $product = self::where('id_prod', $prodId)
            ->where('id_boutique', $boutiqueId)
            ->lockForUpdate()
            ->first();

        if (!$product) {
            throw new Exception(
                "Produit #{$prodId} introuvable dans la boutique #{$boutiqueId}."
            );
        }

        switch ($operation) {

            case 'add':

                $product->increment('quantite', $quantity);
                break;

            case 'subtract':

                if ($product->quantite < $quantity) {
                    throw new Exception(
                        "Stock insuffisant. Disponible : {$product->quantite}, Demandé : {$quantity}"
                    );
                }

                $product->decrement('quantite', $quantity);
                break;

            default:

                throw new Exception(
                    "Opération invalide. Utilisez 'add' ou 'subtract'."
                );
        }

        return $product->fresh();
    }

}