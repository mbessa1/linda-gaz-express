<!DOCTYPE html>
<html lang="fr" x-data="{ open: false, showProfile: false }" xmlns:x-data="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gaz Express</title>

    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="//unpkg.com/alpinejs" defer></script>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Fjalla+One&family=Nunito+Sans:wght@400;600&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Nunito Sans', sans-serif; }
        h1, h2, .brand { font-family: 'Fjalla One', sans-serif; }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-gradient-to-br from-green-50 via-white to-blue-50 text-gray-800 min-h-screen">

<!-- Navbar -->
<nav class="bg-white shadow-lg sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
        <!-- Logo -->
        @auth
    <a href="
        @switch(Auth::user()->role)
            @case('client') {{ route('catalogue') }} @break
            @case('vendeur') {{ route('vendeur.stats') }} @break
            @case('livreur') {{ route('livreur.commandes') }} @break
            @case('admin') {{ route('admin.users') }} @break
        @endswitch
    " class="brand text-3xl text-green-600 hover:text-yellow-400 transition">
        ♻️ Gaz Express
    </a>
@else
    <a href="{{ route('home') }}" class="brand text-3xl text-green-600 hover:text-yellow-400 transition">
        ♻️ Gaz Express
    </a>
@endauth
        <!-- Menu Desktop -->
        <div class="hidden md:flex space-x-6 items-center">
            @guest
                <a href="{{ route('login') }}" class="hover:text-green-600 transition">Connexion</a>
                <a href="{{ route('register') }}" class="bg-green-600 text-white px-4 py-2 rounded-xl hover:bg-green-500 transition">
                    Inscription
                </a>
            @else
                @if(Auth::user()->role === 'client')
                    <a href="{{ route('catalogue') }}" class="hover:text-green-600 transition">Catalogue</a>
                    <a href="{{ route('client.commandes') }}" class="hover:text-green-600 transition">Mes Commandes</a>
                    <a href="{{ route('apropos') }}" class="hover:text-green-600 transition">À propos</a>
                @elseif(Auth::user()->role === 'vendeur')
                    <a href="{{ route('vendeur.stocks') }}" class="hover:text-green-600 transition">Stocks</a>
                    <a href="{{ route('vendeur.stats') }}" class="hover:text-green-600 transition">Stats</a>
                    <a href="{{ route('vendeur.createProduit') }}" class="hover:text-green-600 transition">Ajouter un produit</a>
                    <a href="{{ route('vendeur.livreurs') }}" class="hover:text-green-600 transition">Gérer livreurs</a>
                @elseif(Auth::user()->role === 'livreur')
                    <a href="{{ route('livreur.commandes') }}" class="hover:text-green-600 transition">Commandes</a>
                @elseif(Auth::user()->role === 'admin')
                    <a href="{{ route('admin.users') }}" class="hover:text-green-600 transition">Utilisateurs</a>
                @endif

                <!-- Avatar + Dropdown -->
                <div class="relative" x-data="{ openProfile: false }">
                    <button @click="openProfile = !openProfile"
                        class="w-10 h-10 rounded-full bg-green-600 text-white flex items-center justify-center font-bold">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </button>

                    <div x-show="openProfile" x-cloak @click.away="openProfile = false"
                         class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-2 z-50">
                        <div class="px-4 py-2 text-sm text-gray-700 border-b">
                            {{ Auth::user()->name }}
                        </div>
                            @if(Auth::user()->role === 'client')
                                <button @click="showProfile = true">Modifier Profil</button>
                            @elseif(Auth::user()->role === 'vendeur')
                                <a href="{{ route('vendeur.editProfile') }}">Modifier Profil</a>
                            @elseif(Auth::user()->role === 'livreur')
                                <a href="{{ route('livreur.editPassword') }}">Modifier Mot de Passe</a>
                            @endif

                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit"
                                class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                Déconnexion
                            </button>
                        </form>
                    </div>
                </div>
            @endguest
        </div>

        <!-- Mobile menu button -->
        <button @click="open = !open" class="md:hidden focus:outline-none">
            <svg xmlns="http://www.w3.org/2000/svg" :class="{'hidden': open, 'block': !open}" class="h-7 w-7 text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
            <svg xmlns="http://www.w3.org/2000/svg" :class="{'block': open, 'hidden': !open}" class="h-7 w-7 text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>

    <!-- Mobile Menu -->
    <div x-show="open" x-cloak class="md:hidden bg-green-50 px-6 py-4 space-y-3">
        @guest
            <a href="{{ route('login') }}" class="block text-gray-700 hover:text-green-600">Connexion</a>
            <a href="{{ route('register') }}" class="block bg-green-600 text-white px-4 py-2 rounded-xl hover:bg-green-500 transition">Inscription</a>
        @else
            @if(Auth::user()->role === 'client')
                <a href="{{ route('catalogue') }}" class="block hover:text-green-600">Catalogue</a>
                <a href="{{ route('client.commandes') }}" class="block hover:text-green-600">Mes Commandes</a>
                <a href="{{ route('apropos') }}" class="block hover:text-green-600">À propos</a>
            @elseif(Auth::user()->role === 'vendeur')
                <a href="{{ route('vendeur.stocks') }}" class="block hover:text-green-600">Stocks</a>
                <a href="{{ route('vendeur.stats') }}" class="block hover:text-green-600">Stats</a>
                <a href="{{ route('vendeur.createProduit') }}" class="block hover:text-green-600">Ajouter un produit</a>
                <a href="{{ route('vendeur.livreurs') }}" class="block hover:text-green-600">Gérer livreurs</a>
            @elseif(Auth::user()->role === 'livreur')
                <a href="{{ route('livreur.commandes') }}" class="block hover:text-green-600">Commandes</a>
            @elseif(Auth::user()->role === 'admin')
                <a href="{{ route('admin.users') }}" class="block hover:text-green-600">Utilisateurs</a>
            @endif

            <!-- Avatar Dropdown Mobile -->
            <div class="relative" x-data="{ openProfileMobile: false }">
                <button @click="openProfileMobile = !openProfileMobile"
                    class="w-10 h-10 rounded-full bg-green-600 text-white flex items-center justify-center font-bold">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </button>
                <div x-show="openProfileMobile" x-cloak @click.away="openProfileMobile = false"
                     class="mt-2 w-48 bg-white rounded-lg shadow-lg py-2 z-50">
                    <div class="px-4 py-2 text-sm text-gray-700 border-b">
                        {{ Auth::user()->name }}
                    </div>
                    @if(Auth::user()->role === 'client')
                        <button @click="showProfile = true; openProfileMobile = false"
                            class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-green-50">
                            Modifier Profil
                        </button>
                    @endif
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit"
                            class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                            Déconnexion
                        </button>
                    </form>
                </div>
            </div>
        @endguest
    </div>
</nav>

<!-- Contenu principal -->
<main class="max-w-7xl mx-auto px-6 py-10">
    @yield('content')
</main>

<!-- Footer -->
<footer class="bg-white text-center py-6 mt-10 shadow-inner">
    <p class="text-gray-600">© {{ date('Y') }} Gaz Express – Énergie Verte & Livraison Rapide ⚡</p>
</footer>

<!-- Modal Profil -->
@auth
<div x-show="showProfile" x-cloak class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
    <div class="bg-white rounded-xl shadow-lg p-6 w-96">
        <h2 class="text-xl font-bold text-green-600 mb-4">Mon Profil</h2>

        <form action="{{ route('client.updateProfile') }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label class="block text-gray-700">Nom</label>
                <input type="text" name="name" value="{{ Auth::user()->name }}" class="w-full border rounded-lg p-2">
            </div>

            <div class="mb-4">
                <label class="block text-gray-700">Email</label>
                <input type="email" name="email" value="{{ Auth::user()->email }}" class="w-full border rounded-lg p-2">
            </div>

            <div class="flex justify-end space-x-2">
                <button type="button" @click="showProfile = false"
                        class="px-4 py-2 rounded-lg bg-gray-300 hover:bg-gray-400">Annuler</button>
                <button type="submit"
                        class="px-4 py-2 rounded-lg bg-green-600 text-white hover:bg-green-500">Enregistrer</button>
            </div>
        </form>
    </div>
</div>
@endauth

</body>
</html>
