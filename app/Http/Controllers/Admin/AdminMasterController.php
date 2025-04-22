<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;

class AdminMasterController extends Controller
{
    public function assignRole(Request $request, $id)
    {
            // Valider les données reçues
        $request->validate([
            'role' => 'required|string|exists:roles,name',
        ]);

        // Récupérer l'utilisateur
        $user = User::findOrFail($id);

        // Trouver le rôle
        $role = Role::where('name', $request->role)->first();

        // Assigner le rôle
        $user->role_id = $role->id;
        $user->save(); // ⚠️ Ici, on met à jour un user existant

        return response()->json([
            'message' => 'Role updated successfully',
            'user' => $user->load('role') // optionnel : inclure les infos du rôle
        ]);
    }

    public function storeRole(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:roles,name',
        ]);

        $role = Role::create(['name' => strtolower($request->name)]);

        return response()->json([
            'message' => 'Role created successfully',
            'role' => $role,
        ]);
    }

    public function updateRole(Request $request, $id)
    {
        $role = Role::findOrFail($id);

        $role->update([
            'name' => strtolower($request->name),
        ]);

        return response()->json([
            'message' => 'Role Updated Successfully',
            'role' => $role
        ]);
    }

    public function deleteRole($id)
    {
        $role = Role::findOrFail($id);

        $role->delete();

        return response()->json([
            'message' => 'Role deleted Successfully'
        ]);
    }

    //Modification du code produit
    public function UpdateCodeProd($id)
    {
        $product = Product::findOrFail($id);

        $words = explode(" ", strtoupper($product->product_name));
            $codePart = substr($words[0] ?? 'XX', 0, 2) . substr($words[1] ?? '-', 0, 2);
            do {
                $uniqueCode = $codePart . str_pad(mt_rand(0, 1000), 2, '0', STR_PAD_LEFT);
            } while (Product::where('product_code', $uniqueCode)->exists());
        $product->product_code = $uniqueCode;

        $product->save();

        return response()->json([
            'message' => 'Le Code du Produit a été Mise à Jour avec Succès',
            'produit' => $product,
        ]);
    }

}
