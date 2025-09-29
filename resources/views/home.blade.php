@extends('layouts.app')

@section('content')
<!-- Hero Section -->
<section class="relative bg-gradient-to-r from-green-600 via-green-500 to-green-500 text-white py-20 px-6 text-center">
    <h1 class="text-5xl font-extrabold mb-4">Bienvenue sur <span class="text-yellow-300">Gaz Express</span></h1>
    <p class="text-lg mb-6">La solution rapide, fiable et sécurisée pour vos besoins en gaz domestique</p>
    <div class="flex justify-center space-x-4">
        @guest
        <a href="{{ route('register') }}" 
            class="bg-yellow-400 text-black px-6 py-3 rounded-full font-semibold shadow-lg hover:scale-105 transition">
            S’inscrire
        </a>
        <a href="{{ route('login') }}" 
            class="bg-white text-blue-700 px-6 py-3 rounded-full font-semibold shadow-lg hover:scale-105 transition">
            Se connecter
        </a>
        @else
        <a href="{{ route('logout') }}" 
            class="bg-white text-blue-700 px-6 py-3 rounded-full font-semibold shadow-lg hover:scale-105 transition">
            Se deconnexion
        </a>
        @endguest
    </div>
</section>

<!-- Section Produits -->
<section class="py-10 bg-gray-100">
    <div class="container mx-auto px-6">
        <h2 class="text-3xl font-bold text-center text-gray-800 mb-8">Nos Produits</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @foreach($produits as $produit)
                <div class="bg-white rounded-2xl shadow-lg hover:shadow-xl transition p-6 flex flex-col items-center">
                    <img src="{{ $produit->image }}" alt="{{ $produit->marque }}" class="w-32 h-32 object-contain mb-4">
                    <h3 class="text-lg font-semibold text-gray-700">{{ $produit->marque }}</h3>
                    <p class="text-gray-500">{{ $produit->poids }}</p>
                    <p class="text-xl font-bold text-green-600">{{ $produit->prix }} FCFA</p>
                    <a href="{{ route('register') }}" class="mt-4 px-6 py-2 bg-green-600 text-white rounded-full hover:bg-green-700 transition">
                        Sinscrire & Commander
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</section>

    <!-- Section Pourquoi Gaz Express -->
<section class="py-16 bg-white">
    <div class="container mx-auto px-6 text-center">
        <h2 class="text-3xl font-bold text-gray-800 mb-12">Pourquoi choisir <span class="text-green-600">Gaz Express</span> ?</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
            <div>
                <div class="w-16 h-16 mx-auto flex items-center justify-center rounded-full bg-green-100 text-green-600 text-3xl mb-4">⚡</div>
                <h3 class="font-semibold text-gray-700">Livraison Rapide</h3>
                <p class="text-gray-500 mt-2">Recevez votre gaz en quelques minutes, où que vous soyez.</p>
            </div>
            <div>
                <div class="w-16 h-16 mx-auto flex items-center justify-center rounded-full bg-green-100 text-green-600 text-3xl mb-4">💰</div>
                <h3 class="font-semibold text-gray-700">Prix Abordables</h3>
                <p class="text-gray-500 mt-2">Des tarifs compétitifs et transparents, sans surprise.</p>
            </div>
            <div>
                <div class="w-16 h-16 mx-auto flex items-center justify-center rounded-full bg-green-100 text-green-600 text-3xl mb-4">✅</div>
                <h3 class="font-semibold text-gray-700">Fiabilité Garantie</h3>
                <p class="text-gray-500 mt-2">Un service sûr et de qualité, 24h/24 et 7j/7.</p>
            </div>
        </div>
    </div>
</section>

   <!-- Section CTA -->
<section class="py-16 bg-green-600 text-white text-center">
    <h2 class="text-3xl font-bold mb-4">Passez votre commande dès maintenant 🚀</h2>
    <p class="mb-6">Profitez d’un service rapide, fiable et abordable en quelques clics.</p>
    <a href="{{ route('register') }}" class="px-8 py-3 bg-white text-green-600 font-semibold rounded-full hover:bg-gray-100 transition">
        S’inscrire
    </a>
</section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-gray-300 py-10 px-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div>
                <h3 class="text-lg font-bold mb-3">Gaz Express</h3>
                <p>Votre partenaire de confiance pour l’achat et la livraison de gaz domestique au Cameroun.</p>
            </div>
            <div>
                <h3 class="text-lg font-bold mb-3">Liens utiles</h3>
                <ul>
                    <li><a href="#" class="hover:text-white">Accueil</a></li>
                    <li><a href="#" class="hover:text-white">Catalogue</a></li>
                    <li><a href="#" class="hover:text-white">Connexion</a></li>
                    <li><a href="#" class="hover:text-white">Inscription</a></li>
                </ul>
            </div>
            <div>
                <h3 class="text-lg font-bold mb-3">Contact</h3>
                <p>Email : support@gazexpress.com</p>
                <p>Tél : +237 6 XX XX XX XX</p>
                <p>Douala, Cameroun</p>
            </div>
        </div>
        <div class="text-center mt-8 text-gray-500">
            © 2024 Gaz Express - Tous droits réservés
        </div>
    </footer>
@endsection
