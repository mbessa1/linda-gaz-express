<?php

namespace App\Http\Controllers;

use App\Models\Commande;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class PaiementNotchPayController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function initier(Request $request, Commande $commande)
    {
        if ($commande->user_id !== Auth::id()) abort(403);

        $request->validate([
            'telephone' => 'required|string|min:9|max:15',
            'operateur' => 'required|in:mtn,orange',
        ]);

        $canal = $request->operateur === 'mtn' ? 'cm.mtn' : 'cm.orange';

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . env('NOTCHPAY_SECRET_KEY'),
            'X-Grant'       => 'payment',
            'Content-Type'  => 'application/json',
        ])->post('https://api.notchpay.co/payments/initialize', [
            'amount'      => $commande->prix_total,
            'currency'    => 'XAF',
            'email'       => Auth::user()->email,
            'phone'       => '+237' . $request->telephone,
            'reference'   => 'CMD-' . $commande->id . '-' . time(),
            'description' => 'Paiement Gaz Express #' . $commande->id,
            'callback'    => route('paiement.callback'),
            'return_url'  => route('paiement.succes', $commande->id),
            'channel'     => $canal,
        ]);

        $data = $response->json();

        if ($response->successful() && isset($data['transaction']['authorization_url'])) {
            return redirect($data['transaction']['authorization_url']);
        }

        return back()->withErrors(['paiement' => $data['message'] ?? 'Erreur paiement. Reessayez !']);
    }

    public function callback(Request $request)
    {
        $reference = $request->input('reference');

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . env('NOTCHPAY_SECRET_KEY'),
        ])->get('https://api.notchpay.co/payments/' . $reference);

        $data = $response->json();

        if (isset($data['transaction']['status']) && $data['transaction']['status'] === 'complete') {
            $parts = explode('-', $reference);
            $commandeId = $parts[1] ?? null;
            if ($commandeId) {
                $commande = Commande::find($commandeId);
                if ($commande) {
                    $commande->update(['statut' => 'en_attente']);
                    Notification::create([
                        'user_id' => $commande->user_id,
                        'titre'   => 'Paiement confirme',
                        'message' => 'Paiement recu !',
                        'type'    => 'success',
                    ]);
                }
            }
        }

        return response()->json(['status' => 'ok']);
    }

    public function succes(Commande $commande)
    {
        if ($commande->user_id !== Auth::id()) abort(403);
        return redirect()->route('client.commandes')
            ->with('success', 'Paiement reussi !');
    }
}