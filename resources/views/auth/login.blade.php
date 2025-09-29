@extends('layouts.app')

@section('content')
<div class="flex justify-center items-center min-h-screen bg-gradient-to-br from-skyBlue via-white to-forestGreen/20 px-4">
    <div class="w-full max-w-md bg-white shadow-2xl rounded-2xl p-8">
        
        <h3 class="text-2xl font-bold text-center text-forestGreen mb-6">🔐 Connexion</h3>
        
        <form method="POST" action="{{ route('login') }}" class="space-y-4">
            @csrf
            
            <!-- Email -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input type="email" name="email" required
                       class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-solarYellow focus:outline-none transition">
            </div>
            
            <!-- Mot de passe -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Mot de passe</label>
                <input type="password" name="password" required
                       class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-solarYellow focus:outline-none transition">
            </div>
            
            <!-- Bouton -->
            <button type="submit"
                class="w-full py-3 bg-forestGreen text-black font-semibold rounded-lg shadow-md hover:bg-green-700 transition duration-300 transform hover:scale-105">
                Se connecter
            </button>
        </form>

        <!-- Lien inscription -->
        <p class="mt-6 text-center text-sm text-gray-600">
            Pas encore inscrit ? 
            <a href="{{ route('register') }}" class="text-skyBlue font-semibold hover:underline">Créer un compte</a>
        </p>
    </div>
</div>
@endsection
