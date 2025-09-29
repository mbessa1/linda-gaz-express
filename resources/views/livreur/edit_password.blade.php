@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto mt-10 bg-white p-6 rounded-lg shadow-md">
    <h2 class="text-xl font-bold mb-4 text-center">Modifier mon mot de passe</h2>

    @if(session('success'))
        <div class="bg-green-200 text-green-800 p-2 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <form method="POST" action="{{ route('livreur.updatePassword') }}">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label for="password" class="block text-gray-700">Nouveau mot de passe</label>
            <input type="password" name="password" id="password"
                class="w-full border rounded p-2 @error('password') border-red-500 @enderror"
                required>
            @error('password')
                <p class="text-red-500 text-sm">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="password_confirmation" class="block text-gray-700">Confirmer le mot de passe</label>
            <input type="password" name="password_confirmation" id="password_confirmation"
                class="w-full border rounded p-2" required>
        </div>

        <button type="submit"
            class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700 transition">
            Mettre à jour
        </button>
    </form>
</div>
@endsection
