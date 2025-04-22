<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductCart;
use Illuminate\Http\Request;

class ProductCartController extends Controller
{
    public function allProduct()
    {
        $products = Product::all();

        return $products;
    }

    public function AddToCard(Request $request, $id)
    {
        //Verification de l'authentification de l'utilisateur
        $user = $request->user();
        if(!$user){
            return response()->json(['message'=>'L`\'utilisateur ne pas authentifié'], 401);
        }

        //Recuperation du produit
        $product = Product::find($id);
        if(!$product){
            return response()->json(['message'=>'Le produit n\'existe pas'], 404);
        }

        //Validation des données entrants
        $request->validate([
            'quantity' => 'required|integer|min:1',
            'product_weight' => 'nullable|string|max:255',
        ]);

        //calcule du prix
        $price = $product->special_price !== "Na" ? $product->special_price : $product->price;
        $total_price = $price * $request->quantity;

        $cartItem = ProductCart::create([
            'user_id' => $user->id,
            'email' => $user->email,
            'product_id' => $product->id,
            'product_name' => $product->product_name,
            'product_code' => $product->product_code,
            'image' => $product->product_image,
            'product_weight' => $request->product_weight ?? 'N/A',
            'quantity' => $request->quantity,
            'unite_price' => $price,
            'total_price' => $total_price,
        ]);

        return response()->json([
            'message' => 'Le produit a été ajouté au panier avec Succès',
            'CartItem' => $cartItem,
        ]);
    }

    //Le nombre de produit contenu dans le panier
    public function CartCount(Request $request)
    {
        $user = $request->user();
        //Verification de l'authentification de l'utilisateur
        if(!$user){
            return response()->json(['message'=>'L`\'utilisateur ne pas authentifié'], 401);
        }

        $result = $user->product_carts()->count();
        
        // $result = ProductCart::count();

        return $result;
    }

    //La liste du panier par utilisateur
    public function ListCart(Request $request)
    {
        $user = $request->user();
        //Verification de l'authentification de l'utilisateur
        if(!$user){
            return response()->json(['message'=>'L`\'utilisateur ne pas authentifié'], 401);
        }

        $productcart = $user->product_carts()->with('product')->get();
        if($productcart->isEmpty()){
            return response()->json([
                'message'=>'Votre panier est vide'
            ], 200);
        }

        return response()->json([
            'message' => 'Voici la Liste de votre panier',
            'cart_items' => $productcart,
        ], 200);
    }

    public function RemoveCartList(Request $request)
    {
        $cart_id = $request->id;

        $cart_Item = ProductCart::where('id', $cart_id)->delete();

        return response()->json([
            'message' => 'Vous venez de supprimer un produit de votre carte',
            'cart_Item' => $cart_Item,
        ]);
    }

    public function CarteItemPlus(Request $request)
    {
        
        
        $produit = ProductCart::find($request->id);

        if(!$produit){
            return response([
                'message' => 'Ce produit n\'existe pas'
            ], 201);
        }

        $produit->quantity += 1;
        $produit->total_price = $produit->unite_price * $produit->quantity;

        $produit->save();

        return response([
            'message' => 'La quantité a bien été mise à jour',
            'cartItem' => $produit,
        ]);

    }

    public function CarteItemMinus(Request $request)
    {
        
        
        $produit = ProductCart::find($request->id);

        if(!$produit){
            return response([
                'message' => 'Ce produit n\'existe pas'
            ], 201);
        }

        $produit->quantity = $produit->quantity -1;
        $produit->total_price = $produit->unite_price * $produit->quantity;

        $produit->save();

        return response([
            'message' => 'La quantité a bien été mise à jour',
            'cartItem' => $produit,
        ]);

    }

}
