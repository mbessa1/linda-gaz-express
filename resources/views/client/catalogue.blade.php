@extends('layouts.app')

@section('content')
<section class="py-10">
    <div class="container mx-auto px-6">
        <h2 class="text-3xl font-bold text-center mb-8" style="color:#f0b429;">🫙 Nos Produits</h2>

        <form method="GET" action="{{ route('catalogue') }}" class="mb-8 flex justify-center flex-wrap gap-2">
            <input type="text" name="q" placeholder="🔍 Rechercher une marque..."
                   value="{{ request('q') }}"
                   class="rounded-l-full px-4 py-2 focus:outline-none border"
                   style="background:rgba(10,22,40,0.8);border-color:#f0b429;color:#f0b429;">
            <input type="text" name="ville" placeholder="📍 Ville / Quartier"
                   value="{{ request('ville') }}"
                   class="px-4 py-2 focus:outline-none border"
                   style="background:rgba(10,22,40,0.8);border-color:#f0b429;color:#f0b429;">
            <button class="px-6 py-2 rounded-r-full font-bold transition hover:opacity-80"
                    style="background:#f0b429;color:#0a1628;">
                Rechercher / Filtrer
            </button>
        </form>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8">
            @forelse($produits as $produit)
                @php
                    $quantite = $produit->stock->quantite ?? 0;
                    $enRupture = $quantite <= 0;
                @endphp
                <div class="rounded-2xl transition p-6 flex flex-col items-center hover:scale-105 border"
                     style="background:transparent;border-color:rgba(240,180,41,0.3);">
                    <div class="w-40 h-40 flex items-center justify-center mb-4">
                        <img src="{{ $produit->image ? asset($produit->image) : asset('images/default_gaz.jpg') }}"
                             alt="{{ $produit->marque }}"
                             class="w-36 h-36 object-contain"
                             style="filter:drop-shadow(0 0 20px rgba(240,180,41,0.5));">
                    </div>
                    <h3 class="text-lg font-bold text-center" style="color:#f0b429;">{{ $produit->marque }}</h3>
                    <p class="text-sm mb-1" style="color:rgba(240,180,41,0.6);">{{ $produit->poids }}</p>
                    <p class="text-xl font-bold" style="color:#f0b429;">{{ number_format($produit->prix, 0, ',', ' ') }} FCFA</p>
                    <p class="mt-1 text-sm font-medium {{ $enRupture ? 'text-red-400' : 'text-green-400' }}">
                        {{ $enRupture ? '🔴 Rupture de stock' : '✅ Stock: '.$quantite }}
                    </p>
                    @if($produit->vendeur && ($produit->vendeur->ville || $produit->vendeur->quartier))
                    <p class="text-xs mt-1" style="color:rgba(240,180,41,0.5);">
                        📍 {{ $produit->vendeur->ville ?? '' }} {{ $produit->vendeur->quartier ?? '' }}
                    </p>
                    @endif
                    <div class="mt-4 w-full">
                        @auth
                            @if(Auth::user()->role === 'client')
                                <a href="{{ route('commande.create', ['produit_id' => $produit->id]) }}"
                                   class="block text-center px-6 py-3 rounded-full font-bold transition hover:opacity-80"
                                   style="background:#f0b429;color:#0a1628;">
                                    🛒 Commander
                                </a>
                            @endif
                        @else
                            <a href="{{ route('login') }}"
                               class="block text-center px-6 py-3 rounded-full font-bold transition hover:opacity-80"
                               style="background:#f0b429;color:#0a1628;">
                                S'inscrire & Commander
                            </a>
                        @endauth
                    </div>
                </div>
            @empty
                <div class="col-span-3 text-center p-10 rounded-2xl border" style="color:#f0b429;border-color:rgba(240,180,41,0.3);">
                    Aucun produit trouvé.
                </div>
            @endforelse
        </div>
    </div>
</section>
@endsection