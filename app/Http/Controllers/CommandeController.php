<?php

namespace App\Http\Controllers;

use App\Models\Commande;
use App\Models\Produit;
use App\Models\User;
use App\Models\Livraison;
use App\Models\Notification;
use App\Mail\ConfirmationCommande;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Services\NotificationService;

class CommandeController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->middleware('auth');
        $this->notificationService = $notificationService;
    }

    public function create(Request $request)
    {
        $produit = null;

        if ($request->has('produit_id')) {
            $produit = Produit::with('stock')->find($request->produit_id);
        }

        $produits = Produit::with('stock')->get();

        return view('client.commande', compact('produits', 'produit'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'gaz_id'            => 'required|exists:produits,id',
            'quantite'          => 'required|integer|min:1',
            'latitude'          => 'nullable|numeric',
            'longitude'         => 'nullable|numeric',
            'adresse_livraison' => 'required|string|max:255',
        ]);

        $produit = Produit::findOrFail($request->gaz_id);

        $stock = $produit->stock;
        if (!$stock || $stock->quantite < $request->quantite) {
            return back()->withErrors(['quantite' => 'Stock insuffisant pour ce produit']);
        }

        $vendeur = $produit->user;
        if (!$vendeur) {
            return back()->withErrors(['gaz_id' => 'Vendeur introuvable pour ce produit.']);
        }

        $prix_total = $produit->prix * $request->quantite;

        $commande = Commande::create([
            'user_id'           => Auth::id(),
            'gaz_id'            => $produit->id,
            'quantite'          => $request->quantite,
            'prix_total'        => $prix_total,
            'latitude'          => $request->latitude,
            'longitude'         => $request->longitude,
            'adresse_livraison' => $request->adresse_livraison,
            'statut'            => 'en_attente',
        ]);

        $stock->quantite -= $request->quantite;
        $stock->save();

        $livreur = User::where('role', 'livreur')
            ->where('status', 'active')
            ->withCount(['commandes as livraisons_en_cours' => function($q) {
                $q->where('statut', 'en_livraison');
            }])
            ->orderBy('livraisons_en_cours', 'asc')
            ->first();

        if ($livreur) {
            $commande->update(['statut' => 'en_livraison']);

            Livraison::create([
                'commande_id' => $commande->id,
                'livreur_id'  => $livreur->id,
                'etat'        => 'en_cours',
                'localisation'=> $commande->adresse_livraison,
            ]);

            $this->notificationService->notifyLivreur($livreur, $commande);
        }

        // ✅ Notification au client
        Notification::create([
            'user_id' => Auth::id(),
            'titre'   => '✅ Commande confirmée',
            'message' => 'Votre commande de ' . $produit->marque . ' a été passée avec succès !',
            'type'    => 'success',
        ]);

        // ✅ Notification au vendeur
        if ($vendeur) {
            Notification::create([
                'user_id' => $vendeur->id,
                'titre'   => '🛒 Nouvelle commande',
                'message' => 'Une nouvelle commande de ' . $produit->marque . ' a été passée.',
                'type'    => 'info',
            ]);
        }

        // ✅ Notification au livreur
        if ($livreur) {
            Notification::create([
                'user_id' => $livreur->id,
                'titre'   => '🚚 Nouvelle livraison',
                'message' => 'Une livraison vous a été assignée à ' . $request->adresse_livraison,
                'type'    => 'info',
            ]);
        }

        return redirect()->route('paiement', $commande->id)
            ->with('success', 'Commande créée ! Procédez au paiement.');
    }

    public function index()
    {
        $commandes = Commande::with(['produit', 'livraison'])
            ->where('user_id', Auth::id())
            ->get();

        return view('commandes.index', compact('commandes'));
    }

    public function show($id)
    {
        $commande = Commande::findOrFail($id);

        if ($commande->user_id !== Auth::id()) {
            abort(403);
        }

        return view('commandes.show', compact('commande'));
    }

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

    public function paiement(Commande $commande)
    {
        if ($commande->user_id !== Auth::id()) {
            abort(403);
        }

        return view('client.paiement', compact('commande'));
    }

    public function confirmerPaiement(Request $request, Commande $commande)
    {
        $request->validate([
            'telephone' => 'required|string|min:9|max:15',
            'operateur' => 'required|in:mtn,orange',
        ]);

        if ($commande->user_id !== Auth::id()) {
            abort(403);
        }

        $commande->update(['statut' => 'en_attente']);

        // ✅ Notification paiement confirmé
        Notification::create([
            'user_id' => Auth::id(),
            'titre'   => '💸 Paiement confirmé',
            'message' => 'Votre paiement de ' . number_format($commande->prix_total, 0, ',', ' ') . ' FCFA a été reçu !',
            'type'    => 'success',
        ]);

        // ✅ Envoyer email de confirmation
        try {
            $commande->load(['produit', 'client']);
            Mail::to(Auth::user()->email)
                ->send(new ConfirmationCommande($commande));
        } catch (\Exception $e) {
            // Si email échoue, on continue quand même
        }

        return redirect()->route('client.commandes')
            ->with('success', '✅ Paiement reçu ! Un email de confirmation vous a été envoyé.');
    }
}