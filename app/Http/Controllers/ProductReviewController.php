<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductReview;
use Illuminate\Http\Request;

class ProductReviewController extends Controller
{
    //Toutes reviewer par produits 
    public function ReviewList(Request $request)
    {
        $product_id = $request->id;

        $reviewers = ProductReview::with('product')->where('product_id', $product_id)->latest()->get();

        return $reviewers;
    }

    public function StoreReview(Request $request)
    {
        $infos = $request->validate([
            'reviewer_name' => 'required|string|min:3',
            'reviewer_photo' => 'nullable|string|max:2048', // Ajoutez des validations pour les autres champs si nécessaire
            'reviewer_rating' => 'required|numeric|min:1|max:5',
            'reviewer_comments' => 'nullable|string|max:1000',
        ]);
    
        // Récupération du produit en tant qu'objet unique
        $product = Product::find($request->id);
    
        // Vérifiez si le produit existe
        if (!$product) {
            return response()->json(['message' => 'Product not found.'], 404);
        }
    
        // Création de la revue
        $reviewer = ProductReview::create([
            'product_id' => $product->id, // Utilisation de l'objet produit
            'product_name' => $product->product_name, // Supposons que cette colonne existe
            'reviewer_name' => $infos['reviewer_name'], // Correction de l'accès à $infos
            'reviewer_photo' => $request->reviewer_photo, // Assurez-vous que ce champ est traité correctement
            'reviewer_rating' => $request->reviewer_rating,
            'reviewer_comments' => $request->reviewer_comments,
        ]);
    
        return response()->json([
            'message' => 'Review created successfully.',
            'review' => $reviewer,
        ]);
    }

}
