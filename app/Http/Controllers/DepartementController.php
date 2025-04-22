<?php

namespace App\Http\Controllers;

use App\Models\Departement;
use Illuminate\Http\Request;

class DepartementController extends Controller
{
    // Permet d'obtenir tout les departements
    public function allDepartement()
    {
        $departements = Departement::latest()->get();
        return $departements;
    }

    public function createDepartement(Request $request)
    {
        $field = $request->validate([
            'nom_departement' => 'required|string|unique:departements,nom_departement'
        ]);

        $departement = Departement::create([
            'nom_departement' => $field['nom_departement'],
        ]);

        return response()->json([
            'message' => 'Departement Created Successfully',
            'departement' => $departement,
        ]);
    }

    public function EditDepartement($id)
    {
        $departement = Departement::findOrFail($id);
        return $departement;
    }

    public function updateDepartement(Request $request, $id)
    {
        $departement = Departement::findOrFail($id);

        $departement->nom_departement = $request->nom_departement;
        $departement->save();

        return response()->json([
            'message' => 'Departement Update Successfully',
            'departement' => $departement,
        ]);
    }

    public function deleteDepartement($id)
    {
        $departement = Departement::findOrFail($id);
        $departement->delete();

        return response()->json([
            'message' => 'Departement Deleted Successfully',
        ]);
    }

    public function SearchDepartement($key)
    {
        if (empty($key)) {
            return response()->json(['message' => 'Veuillez fournir une clé de recherche.'], 400);
        }

        $departements = Departement::where('nom_departement', 'LIKE', "%{$key}%")
                        ->limit(20)
                        ->get();

        return response()->json($departements);
    }

    
}
