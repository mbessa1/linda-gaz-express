<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use App\Models\Commande;
use App\Models\Produit;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use App\Mail\LivreurPasswordMail;

class VendeurController extends Controller
{
    // Afficher le formulaire de création produit
    public function createProduit()
    {
        return view('vendeur.create_produit');
    }

    // Enregistrer un produit et créer son stock initial
    public function storeProduit(Request $request)
    {
        $request->validate([
            'marque' => 'required|string|max:255',
            'poids'  => 'required|in:6kg,12.5kg,35kg,15kg',
            'prix'   => 'required|numeric|min:0',
            'quantite' => 'required|integer|min:0',
        ]);

        $produit = Produit::create([
            'marque' => $request->marque,
            'poids'  => $request->poids,
            'prix'   => $request->prix,
            'user_id' => auth()->id(),
        ]);

        // Créer le stock initial
        $produit->stock()->create([
            'quantite' => $request->quantite
        ]);

        return redirect()->route('vendeur.stocks')->with('success', 'Produit ajouté avec succès !');
    }

    // Voir tous les stocks
    public function stocks()
    {
        $stocks = Stock::with('produit')->get();
        return view('vendeur.stocks', compact('stocks'));
    }

    // Mettre à jour un stock
    public function updateStock(Request $request)
    {
        $request->validate([
            'stock_id' => 'required|exists:stocks,id',
            'quantite' => 'required|integer|min:0'
        ]);

        $stock = Stock::findOrFail($request->stock_id);
        $stock->quantite = $request->quantite;
        $stock->save();

        return back()->with('success', 'Stock mis à jour avec succès.');
    }

    // Statistiques ventes et revenus
    public function stats()
    {
        $ventes = Commande::where('statut', 'livree')->count();
        $revenus = Commande::where('statut', 'livree')->sum('prix_total');

        return view('vendeur.stats', compact('ventes', 'revenus'));
    }

    // Créer un livreur avec mot de passe aléatoire
    public function createLivreur(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'country_code' => 'required|string',
            'phone' => 'required|string|max:20',
        ]);

        // Numéro complet avec indicatif
        $fullPhone = $request->country_code . preg_replace('/[^0-9]/', '', $request->phone);

        $password = Str::random(8);

        $livreur = User::create([
            'name'       => $request->name,
            'email'      => $request->email,
            'password'   => Hash::make($password),
            'phone'      => $fullPhone,
            'role'       => 'livreur',
            'vendeur_id' => auth()->id(),
            'status'     => 'active',
        ]);

        // Envoi du mot de passe par email
        Mail::to($livreur->email)->send(new LivreurPasswordMail($livreur, $password));

        return back()->with('success', 'Livreur ajouté et mot de passe envoyé par email.');
    }

    // Voir la liste des livreurs
    public function livreurs()
    {
        $livreurs = User::where('role', 'livreur')
                        ->where('vendeur_id', auth()->id())
                        ->get();

        return view('vendeur.livreurs', compact('livreurs'));
    }

    // Bloquer un livreur
    public function blockLivreur($id)
    {
        $livreur = User::where('role', 'livreur')
                       ->where('vendeur_id', auth()->id())
                       ->findOrFail($id);

        $livreur->update(['status' => 'blocked']);

        return back()->with('success', 'Livreur bloqué avec succès.');
    }

    // Débloquer un livreur
    public function unblockLivreur($id)
    {
        $livreur = User::where('role', 'livreur')
                       ->where('vendeur_id', auth()->id())
                       ->findOrFail($id);

        $livreur->update(['status' => 'active']);

        return back()->with('success', 'Livreur débloqué avec succès.');
    }

    // Afficher profil vendeur
    public function editProfile()
    {
        $user = auth()->user();
        return view('vendeur.profile', compact('user'));
    }

    // Mettre à jour profil vendeur
    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . auth()->id(),
        ]);

        $user = auth()->user();
        $user->update($request->only('name', 'email'));

        return redirect()->back()->with('success', 'Profil mis à jour avec succès !');
    }
}
