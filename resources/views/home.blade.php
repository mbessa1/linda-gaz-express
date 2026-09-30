@extends('layouts.app')

@section('content')

<div class="min-h-screen">

    <!-- Section Hero -->
    <div class="relative text-white py-24 px-6 text-center rounded-xl mb-12 shadow-2xl overflow-hidden"
         style="background:linear-gradient(135deg,#0d2044 0%,#1a3a6e 50%,#0d2044 100%);border:1px solid rgba(240,180,41,0.4);">

        <div class="relative z-10">
            <div class="flex justify-center items-center gap-4 mb-6">
                <div class="w-16 h-16 rounded-2xl flex items-center justify-center shadow-lg" style="background:rgba(240,180,41,0.2);border:2px solid #f0b429;">
                    <img src="{{ asset('images/logo.svg') }}" alt="Logo" class="w-12 h-12">
                </div>
                <h1 class="text-5xl font-bold" style="color:#f0b429;">
                    BIENVENUE SUR GAZ <span style="color:#ffffff;">EXPRESS</span>
                </h1>
            </div>

            <p class="text-xl mb-10 max-w-2xl mx-auto leading-relaxed" style="color:rgba(240,180,41,0.8);">
                🚀 La solution <strong>rapide</strong>, <strong>fiable</strong> et <strong>sécurisée</strong>
                pour vos besoins en gaz domestique au Cameroun
            </p>

            <div class="flex justify-center gap-8 mb-10 flex-wrap">
                <div class="text-center">
                    <p class="text-3xl font-bold" style="color:#f0b429;">{{ $produits->count() }}+</p>
                    <p class="text-sm" style="color:rgba(240,180,41,0.6);">Produits disponibles</p>
                </div>
                <div class="text-center">
                    <p class="text-3xl font-bold" style="color:#f0b429;">30min</p>
                    <p class="text-sm" style="color:rgba(240,180,41,0.6);">Délai de livraison</p>
                </div>
                <div class="text-center">
                    <p class="text-3xl font-bold" style="color:#f0b429;">6500</p>
                    <p class="text-sm" style="color:rgba(240,180,41,0.6);">FCFA par bouteille</p>
                </div>
            </div>

            <div class="flex justify-center gap-6 flex-wrap">
                <a href="{{ route('register') }}"
                   class="px-10 py-4 rounded-full font-bold text-xl hover:opacity-80 transition shadow-lg"
                   style="background:#f0b429;color:#0a1628;">
                    🎯 S'inscrire gratuitement
                </a>
                <a href="{{ route('login') }}"
                   class="px-10 py-4 rounded-full font-bold text-xl hover:opacity-80 transition shadow-lg border-2"
                   style="border-color:#f0b429;color:#f0b429;background:transparent;">
                    🔐 Se connecter
                </a>
            </div>
        </div>
    </div>

    <!-- Nos Produits -->
    <div class="rounded-xl p-10 mb-8" style="background:transparent;">
        <h2 class="text-4xl font-bold text-center mb-2" style="color:#f0b429;">🫙 Nos Produits</h2>
        <p class="text-center mb-10" style="color:rgba(240,180,41,0.6);">Choisissez parmi nos produits de qualité</p>

        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-6">
            @foreach($produits as $produit)
            <div class="rounded-2xl p-4 flex flex-col items-center gap-3 hover:scale-105 transition border"
                 style="background:transparent;border-color:rgba(240,180,41,0.3);">
                @if($loop->first)
                <span class="text-xs px-3 py-1 rounded-full font-bold" style="background:rgba(240,180,41,0.2);color:#f0b429;">⭐ Populaire</span>
                @endif
                <div class="w-24 h-24 flex items-center justify-center">
                    <img src="{{ $produit->image ? asset($produit->image) : asset('images/default_gaz.jpg') }}"
                         alt="{{ $produit->marque }}"
                         class="w-20 h-20 object-contain"
                         style="filter:drop-shadow(0 0 15px rgba(240,180,41,0.5));">
                </div>
                <p class="font-bold text-lg" style="color:#f0b429;">{{ $produit->marque }}</p>
                <p class="text-sm" style="color:rgba(240,180,41,0.6);">{{ $produit->poids }}</p>
                <p class="font-bold text-xl" style="color:#f0b429;">{{ number_format($produit->prix, 0, ',', ' ') }} FCFA</p>
                <a href="{{ route('catalogue') }}"
                   class="w-full text-center px-4 py-2 rounded-full text-sm font-bold hover:opacity-80 transition"
                   style="background:#f0b429;color:#0a1628;">
                    🛒 Commander
                </a>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Avantages -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">
        <div class="rounded-2xl p-8 text-center border hover:-translate-y-1 transition"
             style="background:transparent;border-color:rgba(240,180,41,0.3);">
            <div class="text-6xl mb-4">🚀</div>
            <h3 class="font-bold text-xl mb-3" style="color:#f0b429;">Livraison rapide</h3>
            <p style="color:rgba(240,180,41,0.6);">Recevez votre gaz en 30 à 60 minutes !</p>
        </div>
        <div class="rounded-2xl p-8 text-center border hover:-translate-y-1 transition"
             style="background:transparent;border-color:rgba(240,180,41,0.3);">
            <div class="text-6xl mb-4">💰</div>
            <h3 class="font-bold text-xl mb-3" style="color:#f0b429;">Meilleur prix</h3>
            <p style="color:rgba(240,180,41,0.6);">Toutes nos bouteilles à 6 500 FCFA !</p>
        </div>
        <div class="rounded-2xl p-8 text-center border hover:-translate-y-1 transition"
             style="background:transparent;border-color:rgba(240,180,41,0.3);">
            <div class="text-6xl mb-4">📍</div>
            <h3 class="font-bold text-xl mb-3" style="color:#f0b429;">Géolocalisation</h3>
            <p style="color:rgba(240,180,41,0.6);">Adresse détectée automatiquement !</p>
        </div>
    </div>

</div>

@endsection