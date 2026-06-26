<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use Illuminate\Http\Request;
use App\Cart;
use App\Models\Entrepot;
use App\Models\ProductBoutigue;

class CarteController extends Controller
{

    public function addToCart(Request $request)
    {
        $prod = ProductBoutigue::where('id_prod', $request->id_prod)->first();
        if ($request->types == "VENTES") {
            $request->validate([
                "quantite" => ["required"],
                "total" => ["required"]
            ]);

            //dd($request->id_prod);

            //$prod = ProductBoutigue::where('id_prod', $request->id_prod)->first();
            //dd($prod . ' ' . $request->id_prod);

            if ($request->options == "1" || $request->options == "2") {
                if ($request->quantite > $prod->quantite) {
                    return back()->with("error", "La quantite saisie est supérieur au quantite du stock  ‘‘[{$prod->quantite}]‘‘");
                }
            }

            $cart = session()->get('cart', []);
            if (isset($cart[$request->product_id])) {
                $cart[$request->product_id] = [
                    "id" => $cart[$request->product_id]['id'],
                    "prod" => $cart[$request->product_id]['prod'],
                    "produit" => $cart[$request->product_id]['produit'],
                    'qte' => $cart[$request->product_id]['qte'] + $request->quantite,
                    "prix" => $cart[$request->product_id]['prix'],
                    "nom_stock" => $cart[$request->product_id]['nom_stock'],
                    "stock_id" => $cart[$request->product_id]['stock_id'],
                    "id_categorie" => $cart[$request->product_id]['id_categorie'],
                    "total" => ($cart[$request->product_id]['qte'] + $request->quantite) * $cart[$request->product_id]['prix'],
                    "options" => $cart[$request->product_id]['options'],
                    "username" => auth()->user()->id,
                    "categorie" => $cart[$request->product_id]['categorie'],
                    "id_setting" => auth()->user()->id_setting,
                ];
            } else {

                $cart[$request->product_id] = [
                    "id" => $request->product_id,
                    "prod" => $request->id_prod,
                    "produit" => $request->nom_produit,
                    "qte" => $request->quantite,
                    "prix" => $request->prix,
                    "nom_stock" => Entrepot::find($request->stock_id)->nom_entrepot,
                    "stock_id" => $request->stock_id,
                    "id_categorie" => $request->id_categorie,
                    "total" => $request->total,
                    "options" => $request->options,
                    "username" => auth()->user()->id,
                    "categorie" => $request->categorie,
                    "id_setting" => auth()->user()->id_setting,
                ];
            }

            session()->put('cart', $cart);
        } else if ($request->types == "DETTES") {
            // dd($request->all());
            $request->validate([
                "quantite" => ["required"],
                "total" => ["required"]
            ]);

            //$prod = ProductBoutigue::find($request->id_prod);
            if ($request->options == "1" || $request->options == "2") {
                if ($request->quantite > $prod->quantite) {
                    return back()->with("error", "Vous n'avez que " . $prod->quantite . " quantité en stock de {$request->nom_produit}");
                }
            }

            $cart_dette = session()->get('dettes', []);
            if (isset($cart_dette[$request->product_id])) {
                // dd($cart_dette[$request->product_id]['qte']+$request->quantite);
                $cart_dette[$request->product_id] = [
                    "id" => $cart_dette[$request->product_id]['id'],
                    "prod" => $cart_dette[$request->product_id]['prod'],
                    "produit" => $cart_dette[$request->product_id]['produit'],
                    'qte' => $cart_dette[$request->product_id]['qte'] + $request->quantite,
                    "prix" => $cart_dette[$request->product_id]['prix'],
                    "nom_stock" => $cart_dette[$request->product_id]['nom_stock'],
                    "stock_id" => $cart_dette[$request->product_id]['stock_id'],
                    "id_categorie" => $cart_dette[$request->product_id]['id_categorie'],
                    "total" => ($cart_dette[$request->product_id]['qte'] + $request->quantite) * $cart_dette[$request->product_id]['prix'],
                    "options" => $cart_dette[$request->product_id]['options'],
                    "username" => auth()->user()->id,
                    "categorie" => $cart_dette[$request->product_id]['categorie'],
                    "id_setting" => auth()->user()->id_setting,
                ];
            } else {
                $cart_dette[$request->product_id] = [
                    "id" => $request->product_id,
                    "prod" => $request->id_prod,
                    "produit" => $request->nom_produit,
                    "qte" => $request->quantite,
                    "prix" => $request->prix,
                    "nom_stock" => Entrepot::find($request->stock_id)->nom_entrepot,
                    "stock_id" => $request->stock_id,
                    "id_categorie" => $request->id_categorie,
                    "total" => $request->total,
                    "options" => $request->options,
                    "username" => auth()->user()->id,
                    "categorie" => $request->categorie,
                    "id_setting" => auth()->user()->id_setting,
                ];
            }
            // dd($cart_dette);
            session()->put('dettes', $cart_dette);
        } else if ($request->types == "COMMANDES") {
            $request->validate([
                "product" => ["required"],
                "id_cat" => ["required"],
                "prix" => ["required"],
                "quantite" => ["required"],
                "total" => ["required"],
            ]);

            $cart_commande = session()->get('commande', []);
            if (isset($cart_commande[$request->product])) {
                $cart_commande[$request->product]['qte'] + $request->quantite;
            } else {
                $cart_commande[$request->product] = [
                    "id" => $request->product,
                    "produit" => $this->getProdName($request->product),
                    "qte" => $request->quantite,
                    "prix" => $request->prix,
                    "total" => $request->total,
                    "categorie" => $request->id_cat,
                    "total_detail" => $request->total_detail,
                    "total_gros" => $request->total_gros,
                    "prix_detail" => $request->prix_detail,
                    "prix_gros" => $request->prix_gros,
                ];
            }
            session()->put('commande', $cart_commande);
        }
        return back()->with("succes", "[{$request->nom_produit}] est ajouté au panier avec succès!");
    }

    public function updateCart(Request $request)
    {
        if ($request->types == "VENTES") {
            if ($request->id && $request->quantite) {

                if ($request->quantite < 1) {
                    return back()->with("error", 'Impossible acheter quantité ' . $request->quantite);
                }

                //Check product in stock
                $prod = ProductBoutigue::find($request->id);
                if ($request->options == "1" || $request->options == "2" && $request->quantite > $prod->quantite) {
                    return back()->with("error", "Vous avez " . $prod->quantite . " quantité en stock ");
                }

                $cart = session()->get('cart');
                $cart[$request->id]["qte"] = $request->quantite;
                session()->put('cart', $cart);
                session()->flash('info', 'Panier mis à jour avec succès');
                return back();
            }
            return back()->with("error", "La mise a jour n'a pas ete effectué!");
        } elseif ($request->types == "DETTES") {
            if ($request->id && $request->quantite) {

                if ($request->quantite < 1) {
                    return back()->with("error", 'Impossible acheter quantité ' . $request->quantite);
                }

                //Check product in stock
                $prod = ProductBoutigue::find($request->id);

                if ($request->options == "1" || $request->options == "2" && $request->quantite > $prod->quantite) {
                    return back()->with("error", "Vous avez " . $prod->quantite . " quantité en stock ");
                }

                $cart_dette = session()->get('dettes');
                $cart_dette[$request->id]["qte"] = $request->quantite;
                session()->put('dettes', $cart_dette);
                session()->flash('info', 'Panier mis à jour avec succès');
                return back();
            }
        } elseif ($request->types == "COMMANDES") {
            if ($request->id && $request->quantite) {

                $cart_commande = session()->get('commande');
                $cart_commande[$request->id]["qte"] = $request->quantite;
                session()->put('commande', $cart_commande);
                session()->flash('info', 'Panier mis à jour avec succès');
                return back();
            }
        }
        return back()->with("error", 'La mise a jour ne pas ete effectue!');
    }

    public function removeCart($id, $types)
    {
        if ($types == "VENTES") {
            if ($id) {
                $cart = session()->get('cart');
                session()->forget('cart');

                if (isset($cart[$id])) {
                    unset($cart[$id]);
                    session()->put('cart', $cart);
                }
                session()->flash('info', "Article a été retirer au liste du panier avec succès");
            }
            return back();
        } elseif ($types == "DETTES") {
            if ($id) {
                $cart_dette = session()->get('dettes');
                session()->forget('dettes');

                if (isset($cart_dette[$id])) {
                    unset($cart_dette[$id]);
                    session()->put('dettes', $cart_dette);
                }
                session()->flash('info', "Article a été retirer au liste du panier avec succès");
            }
            return back();
        } elseif ($types == "COMMANDES") {
            if ($id) {
                $cart_commande = session()->get('commande');
                session()->forget('commande');

                if (isset($cart_commande[$id])) {
                    unset($cart_commande[$id]);
                    session()->put('commande', $cart_commande);
                }
                session()->flash('info', "Article a été retirer au liste du panier avec succès");
            }
            return back();
        }
    }

    public function getProdName($idprod)
    {
        return Stock::find($idprod)->libelle;
    }
}