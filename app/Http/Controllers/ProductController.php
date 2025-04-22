<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateProductRequest;
use App\Models\Departement;
use App\Models\Product;
use App\Trait\HandleProductImage;
use Illuminate\Http\Request;
// use Image;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ProductController extends Controller
{
    //Permet d'obtenir tout les produits 
    public function allProduct()
    {
        $product = Product::latest()->limit(10)->get();

        return $product;
    }

    //Permet d'obtenir tout les departements et les produits liés a chaque departement
    public function allProductDepartement(Request $request)
    {
        $productDepartement = Product::with('departement')->where('departement_id', $request->departement_id)
        ->latest()->limit(10)->get();

        return $productDepartement;
    }

    public function productBySearch(Request $request)
    {
        $key = $request->key;
        $product = Product::where('product_name', 'LIKE', "%{$key}%")->orWhere('product_code', 'LIKE', "%{$key}%")
        ->orWhere('brand', 'LIKE', "%{$key}%")->latest()->limit(10)->get();

        return $product;
    }

    //les produits similaires au produit selectionné
    public function similarProduct($id)
    {
        // $departement_id = $request->departement_id;
        // $product_id = $request->id;

        // if (empty($departement_id) || empty($product_id)) {
        //     return response()->json(['message' => 'id du departement et id du produit sont requis.'], 400);
        // }
        $product = Product::findOrFail($id);
        $departement_id = $product->departement->id;

        // Récupérer les produits similaires dans le même département, en stock, sauf le produit sélectionné
        $products = Product::with('departement')
                    ->where('departement_id', $departement_id)
                    ->where('id', '!=', $id)
                    // ->where('quantity', '>', 0)  Vérifie que le stock est supérieur à 0
                    ->orderBy('id', 'desc')
                    ->limit(4)
                    ->get();

        // Si moins de 10 résultats, compléter avec d'autres produits en stock (autres départements)
        if ($products->count() < 4) {
            $remaining = 4 - $products->count();

            $additionalProducts = Product::with('departement')
                ->where('id', '!=', $id) // exclure le produit sélectionné
                ->where('departement_id', '!=', $departement_id) // éviter de reprendre le même département
                ->orderBy('id', 'desc')
                ->limit($remaining)
                ->get();

            // Fusionner les collections
            $products = $products->merge($additionalProducts);
        }

        return response()->json($products);
    }


    public function productByRemark(Request $request)
    {
        $remark = $request->remark;
        $products = Product::with('departement')->where('remark', $remark)->latest()->limit(10)->get();

        return $products;
    }

    //Utilisation du Trait créé
    use HandleProductImage;

    public function CreateProduct(CreateProductRequest $request)
    {
        try {
            // 1. Vérification du département
            $departement = Departement::find($request->departement_id);
            if (!$departement) {
                return response()->json([
                    'message' => 'Le département sélectionné est introuvable.',
                ], 404);
            }

            $imageUrl = $this->handleProductImage($request->product_image);

            // 4. Création du produit
            $product = Product::create([
                'product_name'      => $request->product_name,
                'product_image'     => $imageUrl,
                'price'             => $request->price,
                'special_price'     => $request->special_price,
                'brand'             => $request->brand,
                'departement_id'    => $departement->id,
                'remark'            => $request->remark,
                // 'product_code'      => $uniqueCode,
                'star'              => $request->star ?? 0, // Valeur par défaut
            ]);

            return response()->json([
                'message' => 'Produit créé avec succès.',
                'product' => $product,
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erreur lors de la création du produit.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function EditProduct($id)
    {
        $product = Product::findOrFail($id);

        return $product;
    }

    public function UpdateProduct(Request $request, $id)
    {
        try {
            $product = Product::findOrFail($id);

            // Si departement_id est fourni, on le vérifie.
            if ($request->has('departement_id')) {
                $departement = Departement::find($request->departement_id);
                if (!$departement) {
                    return response()->json([
                        'message' => 'Le département sélectionné est introuvable.',
                    ], 404);
                }
                $product->departement_id = $departement->id;
            }

            // Gestion de l'image
            // $manager = new ImageManager(new Driver());
            if ($request->has('product_image')) {
               $imageUrl = $this->handleProductImage($request->product_image);

               $product->product_image = $imageUrl;
            }

            // Mise à jour des autres champs si fournis
            if ($request->has('product_name')) $product->product_name = $request->product_name;
            if ($request->has('price')) $product->price = $request->price;
            if ($request->has('special_price')) $product->special_price = $request->special_price;
            if ($request->has('brand')) $product->brand = $request->brand;
            if ($request->has('remark')) $product->remark = $request->remark;
            if ($request->has('star')) $product->star = $request->star;

            $product->save();

            return response()->json([
                'message' => 'Le produit a été mis à jour avec succès.',
                'product' => $product,
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erreur lors de la mise à jour du produit.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function DeleteProduct($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return response()->json([
            'message' => 'Le Produit a été Supprimé avec Succès',
        ]);
    }

}
