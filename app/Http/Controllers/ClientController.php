<?php

namespace App\Http\Controllers;

use App\Models\Produit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ClientController extends Controller
{
    // Afficher catalogue
    public function catalogue(Request $request)
    {
        $query = Produit::with('stock', 'vendeur');

        if ($request->ville) {
            $ville = $request->ville;

            $vendeurs = \App\Models\User::where('ville', 'like', "%$ville%")
                            ->orWhere('quartier', 'like', "%$ville%")
                            ->pluck('id');

            $query->whereIn('user_id', $vendeurs);
        }

        $produits = $query->get();

        return view('client.catalogue', compact('produits'));
}


    // Recherche par marque
    public function search(Request $request)
    {
        $produits = Produit::where('marque','LIKE','%'.$request->q.'%')->get();
        return view('client.catalogue', compact('produits'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'password' => 'nullable|min:6',
        ]);

        $user->name  = $request->name;
        $user->email = $request->email;

        if ($request->password) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return back()->with('success', 'Profil mis à jour avec succès ✅');
    }

    public function index(Request $request)
    {
        $query = Produit::with('stock', 'vendeur');

        // Filtrer par position si le client a choisi une ville/quartier
        if ($request->has('ville') && $request->ville) {
            $ville = $request->ville;

            // On récupère les utilisateurs (vendeurs) dans cette ville
            $vendeurs = \App\Models\User::where('ville', 'like', "%$ville%")->pluck('id');

            $query->whereIn('user_id', $vendeurs);
        }

        $produits = $query->get();

        return view('produits.index', compact('produits'));
    }

    // // Page d'accueil
    // public function home()
    // {
    //     return view('home');
    // }
}
