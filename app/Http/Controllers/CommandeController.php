<?php

namespace App\Http\Controllers;

use App\Models\Commande;
use App\Models\Produit;
use App\Models\User;
use App\Models\Livraison;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\NotificationService;

class CommandeController extends Controller
{
    protected $notificationService;

    // Injection du service de notification
    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    // Afficher formulaire commande
    public function create(Request $request)
    {
        $produit = null;

        if ($request->has('produit_id')) {
            $produit = Produit::with('stock')->find($request->produit_id);
        }

        $produits = Produit::with('stock')->get();

        return view('client.commande', compact('produits', 'produit'));
    }

    // Créer une commande
    public function store(Request $request)
{
    $request->validate([
        'gaz_id'   => 'required|exists:produits,id',
        'quantite' => 'required|integer|min:1',
        'latitude' => 'nullable|numeric',
        'longitude'=> 'nullable|numeric',
        'adresse_livraison' => 'required|string|max:255',
    ]);

    $produit = Produit::findOrFail($request->gaz_id);

    // Vérification stock
    $stock = $produit->stock;
    if (!$stock || $stock->quantite < $request->quantite) {
        return back()->withErrors(['quantite' => 'Stock insuffisant pour ce produit']);
    }

    $prix_total = $produit->prix * $request->quantite;

    $commande = Commande::create([
        'user_id'   => Auth::id(),
        'gaz_id'    => $produit->id,
        'quantite'  => $request->quantite,
        'prix_total'=> $prix_total,
        'latitude'  => $request->latitude,
        'longitude' => $request->longitude,
        'adresse_livraison' => $request->adresse_livraison,
        'statut'    => 'en_attente',
    ]);

    // Mettre à jour le stock
    $stock->quantite -= $request->quantite;
    $stock->save();

    // Trouver le vendeur
    $vendeur = $produit->user;

    // Trouver le livreur actif avec le moins de livraisons en cours
    $livreur = User::where('vendeur_id', $vendeur->id)
        ->where('role', 'livreur')
        ->where('status', 'active')
        ->withCount(['commandes as livraisons_en_cours' => function($q){
            $q->where('statut', 'en_livraison');
        }])
        ->orderBy('livraisons_en_cours', 'asc')
        ->first();

    if ($livreur) {
        // Mettre à jour la commande
        $commande->update(['statut' => 'en_livraison']);

        // Créer la livraison
        $livraison = Livraison::create([
            'commande_id' => $commande->id,
            'livreur_id'  => $livreur->id,
            'etat'        => 'en_cours',
            'localisation'=> $commande->adresse_livraison,
        ]);

        // Envoyer notification au livreur
        $this->notificationService->notifyLivreur($livreur, $commande);
    }

    return redirect()->route('client.commandes')
        ->with('success', 'Commande passée avec succès !');
}


    // Liste des commandes du client
    public function index()
    {
        $commandes = Commande::where('user_id', Auth::id())->get();
        return view('commandes.index', compact('commandes'));
    }

    // Afficher une commande
    public function show($id)
    {
        $commande = Commande::findOrFail($id);
        return view('commandes.show', compact('commande'));
    }

    // Mise à jour adresse
    public function updateAddress(Request $request, Commande $commande)
    {
        $request->validate([
            'adresse_livraison' => 'required|string|max:255',
        ]);

        if ($commande->user_id !== Auth::id()) {
            abort(403);
        }

        $commande->update(['adresse_livraison' => $request->adresse_livraison]);

        return response()->json(['success' => true]);
    }
}
