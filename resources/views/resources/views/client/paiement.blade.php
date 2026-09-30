@extends('layouts.app')

@section('content')
<section class="py-10 bg-gray-100">
    <div class="container mx-auto px-6 max-w-md">

        <h2 class="text-2xl font-bold text-center text-gray-800 mb-8">💳 Paiement Mobile Money</h2>

        {{-- Résumé commande --}}
        <div class="bg-white rounded-2xl shadow p-6 mb-6">
            <h3 class="font-bold text-gray-700 mb-4">📦 Résumé de votre commande</h3>
            <div class="flex justify-between text-sm text-gray-600 mb-2">
                <span>Produit</span>
                <span>{{ $commande->produit->marque ?? 'N/A' }} - {{ $commande->produit->poids ?? '' }}</span>
            </div>
            <div class="flex justify-between text-sm text-gray-600 mb-2">
                <span>Quantité</span>
                <span>{{ $commande->quantite }}</span>
            </div>
            <div class="flex justify-between text-sm text-gray-600 mb-2">
                <span>Adresse</span>
                <span>{{ $commande->adresse_livraison }}</span>
            </div>
            <div class="border-t pt-3 mt-3 flex justify-between font-bold text-gray-800">
                <span>Total à payer</span>
                <span class="text-blue-600 text-xl">{{ number_format($commande->prix_total, 0, ',', ' ') }} FCFA</span>
            </div>
        </div>

        {{-- Formulaire paiement --}}
        <div class="bg-white rounded-2xl shadow p-6">
            <h3 class="font-bold text-gray-700 mb-4">📱 Entrez vos informations Mobile Money</h3>

            <form method="POST" action="{{ route('paiement.confirmer', $commande->id) }}">
                @csrf

                @if($errors->any())
                    <div class="bg-blue-100 text-blue-700 px-4 py-3 rounded-xl mb-4 text-sm">
                        @foreach($errors->all() as $error)
                            <p>❌ {{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                {{-- Choix opérateur --}}
                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">Opérateur</label>
                    <div class="grid grid-cols-2 gap-3">
                        <label class="cursor-pointer">
                            <input type="radio" name="operateur" value="mtn" class="hidden peer" required>
                            <div class="border-2 border-gray-200 peer-checked:border-yellow-400 peer-checked:bg-yellow-50 rounded-xl p-4 text-center transition">
                                <div class="text-2xl mb-1">📡</div>
                                <p class="font-bold text-yellow-600">MTN MoMo</p>
                            </div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="operateur" value="orange" class="hidden peer" required>
                            <div class="border-2 border-gray-200 peer-checked:border-orange-400 peer-checked:bg-orange-50 rounded-xl p-4 text-center transition">
                                <div class="text-2xl mb-1">📡</div>
                                <p class="font-bold text-orange-600">Orange Money</p>
                            </div>
                        </label>
                    </div>
                </div>

                {{-- Numéro de téléphone --}}
                <div class="mb-6">
                    <label class="block text-gray-700 font-semibold mb-2">Numéro Mobile Money</label>
                    <div class="flex">
                        <span class="bg-gray-100 border border-r-0 border-gray-300 rounded-l-full px-4 py-2 text-gray-500 text-sm flex items-center">
                            🇨🇲 +237
                        </span>
                        <input type="text" name="telephone" placeholder="6XXXXXXXX" required
                               class="flex-1 border border-gray-300 rounded-r-full px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>

                {{-- Bouton payer --}}
                <button type="submit" id="pay-btn"
                    class="w-full bg-blue-600 text-white font-bold py-3 rounded-full hover:bg-blue-700 transition">
                    💸 Payer {{ number_format($commande->prix_total, 0, ',', ' ') }} FCFA
                </button>
            </form>

            {{-- Animation chargement --}}
            <div id="loading" class="hidden text-center mt-4">
                <div class="inline-block animate-spin text-3xl">⏳</div>
                <p class="text-gray-600 mt-2">Traitement du paiement en cours...</p>
                <p class="text-sm text-gray-400">Veuillez ne pas fermer cette page</p>
            </div>
        </div>

        {{-- Sécurité --}}
        <div class="text-center mt-4 text-xs text-gray-400">
            🔒 Paiement sécurisé · Données chiffrées
        </div>

    </div>
</section>

<script>
document.querySelector('form').addEventListener('submit', function() {
    document.getElementById('pay-btn').classList.add('hidden');
    document.getElementById('loading').classList.remove('hidden');
});
</script>
@endsection
