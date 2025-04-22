<?php

namespace App\Http\Controllers;

use App\Models\CartOrder;
use App\Models\ProductCart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CartOrderController extends Controller
{
    public function cartOrder(Request $request)
    {
        //Verification de l'authentification de l'utilisateur
        $user = $request->user();
        if(!$user){
            return response()->json(['message'=>'Vous devez vous authentifier'], 401);

        }
        
        $request->validate([
            'payment_method' => 'required|string|max:255', 
            'delivery_address' => 'required|string|max:255', 
            'city' => 'required|string|max:255', 
            'delivery_charge' => 'nullable|numeric|min:0',
            'payment_status' => 'required|boolean', // Ajout pour capturer le statut du paiement 
        ]);
        $payment_method = $request->input('payment_method');
        $delivery_address = $request->input('delivery_address');
        $city = $request->input('city');
        $delivery_charge = $request->input('delivery_charge', 0);
        // $order_status = $request->input('order_status');
        // Statut du paiement fourni par le client ou un système de paiement
        $paymentStatus = $request->input('payment_status');

        date_default_timezone_set("Africa/Porto-Novo");

        $order_date = date("d/m/Y");
        $order_time = date("H:i:s");

        // Récupération des articles du panier
        $cartItems = ProductCart::where('user_id', $user->id)->get();

        if($cartItems->isEmpty()){
            return response()->json([
                'message' => 'Votre panier est vide',
            ], 400);
        }

        $total_amount = 0;

        DB::beginTransaction();

        try {
            foreach($cartItems as $item)
            {
                $paiement = CartOrder::create([
                    'product_id' => $item->product_id,
                    'product_name' => $item->product_name,
                    'product_code' => $item->product_code,
                    'product_weight' => $item->product_weight,
                    'quantity' => $item->quantity,
                    'unit_price' => $item->unite_price,
                    'total_price' => $item->total_price,
                    'user_id' => $user->id,
                    'email' => $user->email,
                    'last_name' => $user->last_name,
                    'first_name' => $user->first_name,
                    'payment_method' => $payment_method,
                    'delivery_address' => $delivery_address,
                    'city' => $city,
                    'delivery_charge' => $delivery_charge,
                    'order_date' => $order_date,
                    'order_time' => $order_time,
                    'order_status' => $paymentStatus? 'En cours' : 'En attente',
                ]);

                $total_amount += $item->total_price;
                $item->delete(); // Suppression de l'article du panier

            }

            $total_amount += $delivery_charge;

            DB::commit();

            return response()->json([
                'message' => $paymentStatus? 'Commande passée avec Succès.' : 'Commande créée mais paiement en attente.',
                'Montant_A_Payer' => $total_amount,
                'Paiement' => $paiement,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Une erreur s\'est produite lors de la commande.', 'error' => $e->getMessage()], 500);
        }
    }
}
