@extends('layouts.app')

@section('content')
<section class="py-10 bg-gray-100">
    <div class="container mx-auto px-6">
        <h2 class="text-3xl font-bold text-center text-gray-800 mb-8">Nos Produits</h2>

        <!-- Barre de recherche -->
        <form method="GET" action="{{ route('catalogue') }}" class="mb-4 flex justify-center flex-wrap gap-2">
            <input type="text" name="q" placeholder="🔍 Rechercher une marque..."
                   value="{{ request('q') }}"
                   class="border border-gray-300 rounded-l-full px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
            
            <input type="text" name="ville" placeholder="📍 Ville / Quartier"
                   value="{{ request('ville') }}"
                   class="border border-gray-300 px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">

            <button class="bg-green-600 text-white px-6 py-2 rounded-r-full hover:bg-green-700 transition">
                Rechercher / Filtrer
            </button>
        </form>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8">
            @forelse($produits as $produit)
                @php
                    $quantite = $produit->stock->quantite ?? 0;
                    $enRupture = $quantite <= 0;
                @endphp

                <div class="rounded-2xl shadow-lg transition p-6 flex flex-col items-center
                            {{ $enRupture ? 'bg-gray-200 text-gray-400 cursor-not-allowed' : 'bg-white hover:shadow-xl' }}">
                    
                    <img src="{{ $produit->image ?? asset('images/default_gaz.jpg') }}" 
                         alt="{{ $produit->marque }}" 
                         class="w-32 h-32 object-contain mb-4">

                    <h3 class="text-lg font-semibold text-center">{{ $produit->marque }}</h3>
                    <p class="text-gray-500">{{ $produit->poids }}</p>
                    <p class="text-xl font-bold text-green-600">{{ number_format($produit->prix, 0, ',', ' ') }} FCFA</p>
                    <p class="mt-1 text-sm">{{ $enRupture ? 'Rupture de stock' : 'Stock: '.$quantite }}</p>
                    <p class="text-sm text-gray-500 mt-1">📍 {{ $produit->vendeur->ville ?? '' }}, {{ $produit->vendeur->quartier ?? '' }}</p>

                    <div class="mt-4 w-full">
                        @auth
                            @if(Auth::user()->role === 'client')
                                @if($enRupture)
                                    <button disabled
                                        class="block w-full text-center px-6 py-2 bg-gray-400 text-white rounded-full cursor-not-allowed">
                                        🛑 Rupture de stock
                                    </button>
                                @else
                                    <a href="{{ route('commande.create', ['produit_id' => $produit->id]) }}"  
                                       class="block text-center px-6 py-2 bg-green-600 text-white rounded-full hover:bg-green-700 transition">
                                        🛒 Commander
                                    </a>
                                @endif
                            @endif
                        @else
                            <a href="{{ route('login') }}" 
                               class="block text-center px-6 py-2 bg-green-600 text-white rounded-full hover:bg-green-700 transition">
                                S'inscrire & Commander
                            </a>
                        @endauth
                    </div>
                </div>
            @empty
                <div class="col-span-3 text-center text-gray-500">
                    Aucun produit trouvé pour le moment.
                </div>
            @endforelse
        </div>
    </div>
</section>
@endsection
