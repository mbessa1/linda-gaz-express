@extends('layouts.app')

@section('content')
<!-- Forcer la suppression de tout fond blanc dans cette vue -->
<style>
    main, section, div, table, tr, td, th {
        background-color: transparent !important;
    }
    .bg-white, .bg-slate-50, .bg-gray-50, .bg-gray-100 {
        background-color: #0d2044 !important;
    }
</style>

<div class="w-full min-h-screen py-6 px-4 rounded-2xl" style="background-color: #0a1628 !important;">
    <div class="max-w-7xl mx-auto">
        
        <!-- Conteneur de la carte -->
        <div class="p-6 rounded-2xl border shadow-2xl" style="background-color: #0d2044 !important; border-color: rgba(240,180,41,0.4) !important;">
            
            <h2 class="text-3xl font-bold mb-6" style="color:#f0b429 !important;">Mes Commandes</h2>

            @if($commandes->isEmpty())
                <div class="p-6 rounded-2xl text-center border" style="background-color:#0a1628 !important; border-color:rgba(240,180,41,0.3) !important; color:rgba(240,180,41,0.7) !important;">
                    Vous n'avez encore passé aucune commande.
                </div>
            @else
                <div class="overflow-x-auto rounded-xl border" style="border-color:rgba(240,180,41,0.2) !important;">
                    <table class="min-w-full" style="background-color:#0d2044 !important;">
                        <thead>
                            <tr style="border-bottom:1px solid rgba(240,180,41,0.3) !important; background-color:#0a1628 !important;">
                                <th class="px-6 py-4 text-left text-sm font-semibold" style="color:#f0b429 !important; background-color:#0a1628 !important;">Produit</th>
                                <th class="px-6 py-4 text-center text-sm font-semibold" style="color:#f0b429 !important; background-color:#0a1628 !important;">Quantité</th>
                                <th class="px-6 py-4 text-center text-sm font-semibold" style="color:#f0b429 !important; background-color:#0a1628 !important;">Total</th>
                                <th class="px-6 py-4 text-center text-sm font-semibold" style="color:#f0b429 !important; background-color:#0a1628 !important;">Statut</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold" style="color:#f0b429 !important; background-color:#0a1628 !important;">Adresse</th>
                                <th class="px-6 py-4 text-center text-sm font-semibold" style="color:#f0b429 !important; background-color:#0a1628 !important;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($commandes as $c)
                                <tr style="border-bottom:1px solid rgba(240,180,41,0.15) !important; background-color:#0d2044 !important;">
                                    <td class="px-6 py-4 font-medium" style="color:#f0b429 !important; background-color:#0d2044 !important;">
                                        {{ $c->produit->marque ?? 'N/A' }} ({{ $c->produit->poids ?? '' }})
                                    </td>
                                    <td class="px-6 py-4 text-center" style="color:rgba(240,180,41,0.8) !important; background-color:#0d2044 !important;">
                                        {{ $c->quantite }}
                                    </td>
                                    <td class="px-6 py-4 text-center font-bold" style="color:#f0b429 !important; background-color:#0d2044 !important;">
                                        {{ number_format($c->prix_total, 0, ',', ' ') }} FCFA
                                    </td>
                                    <td class="px-6 py-4 text-center" style="background-color:#0d2044 !important;">
                                        @if($c->statut === 'livree' || $c->statut === 'livrée')
                                            <span class="px-3 py-1 rounded-full text-xs font-bold" style="background:rgba(22,163,74,0.2) !important;color:#22c55e !important;border:1px solid #22c55e !important;">✅ Livrée</span>
                                        @elseif($c->statut === 'en_attente')
                                            <span class="px-3 py-1 rounded-full text-xs font-bold" style="background:rgba(234,179,8,0.2) !important;color:#eab308 !important;border:1px solid #eab308 !important;">⏳ En attente</span>
                                        @elseif($c->statut === 'en_livraison')
                                            <span class="px-3 py-1 rounded-full text-xs font-bold" style="background:rgba(59,130,246,0.2) !important;color:#60a5fa !important;border:1px solid #60a5fa !important;">🚚 En livraison</span>
                                        @elseif($c->statut === 'annulee' || $c->statut === 'annulée')
                                            <span class="px-3 py-1 rounded-full text-xs font-bold" style="background:rgba(239,68,68,0.2) !important;color:#ef4444 !important;border:1px solid #ef4444 !important;">❌ Annulée</span>
                                        @else
                                            <span class="px-3 py-1 rounded-full text-xs font-bold" style="background:rgba(240,180,41,0.2) !important;color:#f0b429 !important;border:1px solid #f0b429 !important;">{{ ucfirst($c->statut) }}</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm" style="color:rgba(255,255,255,0.8) !important; background-color:#0d2044 !important;">
                                        {{ $c->adresse ?? 'Non spécifiée' }}
                                    </td>
                                    <td class="px-6 py-4 text-center" style="background-color:#0d2044 !important;">
                                        <button class="px-4 py-2 rounded-xl text-xs font-bold" style="background-color:#f0b429 !important; color:#0a1628 !important;">
                                            Mettre à jour
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif

        </div>
    </div>
</div>
@endsection