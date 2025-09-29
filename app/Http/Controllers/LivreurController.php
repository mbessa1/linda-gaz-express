<?php

namespace App\Http\Controllers;

use App\Models\Commande;
use App\Models\Livraison;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\NotificationService;
use Illuminate\Support\Facades\Hash;


class LivreurController extends Controller
{
    protected $notificationService;

    // Injection du service de notification
    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    // Liste des livraisons du livreur
    public function index()
    {
        $livraisons = Livraison::with(['commande.client', 'commande.produit'])
            ->where('livreur_id', Auth::id())
            ->get()
            ->sortBy(function($livraison) {
                return $livraison->commande->adresse_livraison ?? '';
            });

        return view('livreur.commandes', compact('livraisons'));
    }

    // Voir le détail d’une livraison
    public function show($id)
    {
        $livraison = Livraison::with(['commande.client', 'commande.produit'])
            ->where('livreur_id', Auth::id())
            ->findOrFail($id);

        return view('livreur.show', compact('livraison'));
    }

    // Marquer une livraison comme effectuée
    public function confirmer($id, Request $request)
    {
        $livraison = Livraison::where('livreur_id', Auth::id())->findOrFail($id);
        $livraison->etat = 'effectuee';
        $livraison->save();

        $commande = $livraison->commande;
        $commande->statut = 'livree';
        $commande->save();

        // ⚡ Envoyer notification au client pour confirmer la livraison
        $this->notificationService->notifyClientLivraisonEffectuee($commande);

        return redirect()->route('livreur.commandes')
            ->with('success', 'Livraison confirmée !');
    }


    public function getClientPosition($id)
{
    $livraison = Livraison::with('commande')->findOrFail($id);
    
    return response()->json([
        'latitude'  => $livraison->commande->latitude ?? 0,
        'longitude' => $livraison->commande->longitude ?? 0,
    ]);
}


public function editPassword() {
    return view('livreur.edit_password'); // Blade pour changer le mot de passe
}

public function updatePassword(Request $request) {
    $request->validate([
        'password' => 'required|min:6|confirmed',
    ]);

    $user = auth()->user();
    $user->password = Hash::make($request->password);
    $user->save();

    return back()->with('success', 'Mot de passe mis à jour avec succès !');
}


}
