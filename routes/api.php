<?php

use App\Http\Controllers\Admin\AdminMasterController;
use App\Http\Controllers\Admin\VisitorController;
use App\Http\Controllers\CartOrderController;
use App\Http\Controllers\DepartementController;
use App\Http\Controllers\ProductCartController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductDetailController;
use App\Http\Controllers\ProductReviewController;
use App\Http\Controllers\User\AuthController;
use App\Http\Middleware\IsAdmin;
use App\Http\Middleware\IsSupperAdmin;
use App\Http\Middleware\IsUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

//Public Routes

//Authencation Routes
Route::post('/register', [AuthController::class, 'StoreUser']);
Route::post('/login', [AuthController::class, 'login']);

//Departement Routes
Route::get('/departements', [DepartementController::class, 'allDepartement']);
Route::get('/search/departement/{key}', [DepartementController::class, 'SearchDepartement']);
Route::get('/departements', [DepartementController::class, 'allDepartement']);

//Product Routes
Route::get('/products', [ProductController::class, 'allProduct']);
Route::get('/products/{departement_id}', [ProductController::class, 'allProductDepartement']);
//Toutes les produits par recherche
Route::get('/search/{key}', [ProductController::class, 'productBySearch']);
//Toutes les produits par Remark
Route::get('/productbyRemark/{remark}', [ProductController::class, 'productByRemark']);
//Toutes les produits similaires
Route::get('/produitsimilaire/{product_id}', [ProductController::class, 'similarProduct']);
//les Details d'un produit
Route::get('/productdetail/{id}', [ProductDetailController::class, 'ProductDetail']);

//Routes des traitements des Products dans la Carte
//Ajout des produits dans la carte
Route::post('/addtocard/{id}', [ProductCartController::class, 'AddToCard'])->middleware('auth:sanctum'); 
Route::get('/cartcount', [ProductCartController::class, 'CartCount'])->middleware('auth:sanctum');


//Private Routes 

Route::middleware('auth:sanctum')->group(function () {

    Route::middleware('role:user,admin,admin_master')->group(function () {
        // Route commune à tous les utilisateurs authentifiés
        Route::post('/logout', [AuthController::class, 'logout']);
        
    });

    Route::middleware('role:admin,admin_master')->group(function () {
        Route::get('edit/departement/{departement_id}', [DepartementController::class, 'EditDepartement']);
        Route::post('/update/departement/{departement_id}', [DepartementController::class, 'updateDepartement']);
        Route::post('/create/departement', [DepartementController::class, 'createDepartement']);
        Route::delete('/delete/departement/{departement_id}', [DepartementController::class, 'deleteDepartement']);
        //creation des produits
        Route::post('/create/product', [ProductController::class, 'CreateProduct']);
        Route::get('/edit/product/{productId}', [ProductController::class, 'EditProduct']);
        Route::put('/update/product/{productId}', [ProductController::class, 'UpdateProduct']);
        Route::delete('/delete/product/{productId}', [ProductController::class, 'DeleteProduct']);
    });

    Route::middleware('role:admin_master')->group(function () {
        Route::post('/create-role', [AdminMasterController::class, 'storeRole']); // créer un rôle
        Route::post('/update/role/{roleId}', [AdminMasterController::class, 'updateRole']); // mettre un rôle à jour
        Route::post('/assign-role/{userId}', [AdminMasterController::class, 'assignRole']); // assigner un rôle
        Route::delete('/delete-role/{userId}', [AdminMasterController::class, 'deleteRole']); // assigner un rôle
        Route::put('/update/code/product/{productId}', [AdminMasterController::class, 'UpdateCodeProd']);
    });

});

// Route::middleware(IsUser::class)->group(function() {
    
//     Route::get('/departements', [DepartementController::class, 'allDepartement']);

//     Route::middleware(IsAdmin::class)->group(function() {
        
//         Route::get('/products/{departement_id}', [ProductController::class, 'allProductDepartement']);

//         Route::middleware(IsSupperAdmin::class)->group(function() {
//             Route::get('/products', [ProductController::class, 'allProduct']);
//             Route::post('user/{id}/assign-role', [AdminMasterController::class, 'assignRole']);
//         });
//     });
    
// });





//Toutes reviewer par produits 
Route::get('/reviewlist/{id}', [ProductReviewController::class, 'ReviewList']);
//Toutes reviewer par produits 
Route::post('/storereview/{id}', [ProductReviewController::class, 'StoreReview']);


//Liste panier par utilisateur
Route::get('/panieruser', [ProductCartController::class, 'ListCart'])->middleware('auth:sanctum');
//Supprimer un produit du panier
Route::delete('/supprimpanier/{id}', [ProductCartController::class, 'RemoveCartList']);
//Augmenter la quantité d'un produit du panier
Route::get('/updatequantiy/{id}', [ProductCartController::class, 'CarteItemPlus']);
//Augmenter la quantité d'un produit du panier
Route::get('/dimuniquantiy/{id}', [ProductCartController::class, 'CarteItemMinus']);

//Paiement d'un produit
Route::post('/payerproduit', [CartOrderController::class, 'cartOrder'])->middleware('auth:sanctum');
Route::get('/visitor', [VisitorController::class, 'GetAllVisitors']);