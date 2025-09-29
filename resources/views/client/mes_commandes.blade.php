<!-- resources/views/commandes/index.blade.php -->
@extends('layouts.app')

@section('content')
<section class="py-10 bg-gray-50">
    <div class="max-w-7xl mx-auto px-6">
        <h2 class="text-3xl font-bold text-green-600 mb-6">Mes Commandes</h2>

        @if($commandes->isEmpty())
            <div class="p-6 bg-white shadow-lg rounded-2xl text-center text-gray-500">
                Vous n'avez encore passé aucune commande.
            </div>
        @else
            <div class="overflow-x-auto bg-white shadow-lg rounded-2xl">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-green-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Produit</th>
                            <th class="px-6 py-3 text-center text-sm font-semibold text-gray-700">Quantité</th>
                            <th class="px-6 py-3 text-center text-sm font-semibold text-gray-700">Total</th>
                            <th class="px-6 py-3 text-center text-sm font-semibold text-gray-700">Statut</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($commandes as $c)
                            <tr class="hover:bg-green-50 transition">
                                <td class="px-6 py-4">{{ $c->produit->marque }} ({{ $c->produit->poids }})</td>
                                <td class="px-6 py-4 text-center">{{ $c->quantite }}</td>
                                <td class="px-6 py-4 text-center font-semibold text-green-600">{{ number_format($c->prix_total, 0, ',', ' ') }} FCFA</td>
                                <td class="px-6 py-4 text-center">
                                    @if($c->statut === 'livree')
                                        <span class="px-2 py-1 rounded-full bg-green-100 text-green-700 text-sm font-semibold">Livrée</span>
                                    @elseif($c->statut === 'en_attente')
                                        <span class="px-2 py-1 rounded-full bg-yellow-100 text-yellow-700 text-sm font-semibold">En attente</span>
                                    @elseif($c->statut === 'annulee')
                                        <span class="px-2 py-1 rounded-full bg-red-100 text-red-700 text-sm font-semibold">Annulée</span>
                                    @else
                                        <span class="px-2 py-1 rounded-full bg-gray-100 text-gray-700 text-sm font-semibold">{{ ucfirst($c->statut) }}</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</section>
@endsection
