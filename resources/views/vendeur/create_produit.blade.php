@extends('layouts.app')

@section('content')
<section class="py-10 bg-gray-50">
    <div class="max-w-md mx-auto px-6">
        <h2 class="text-3xl font-bold text-green-600 mb-6 text-center">Ajouter un Produit</h2>

        @if ($errors->any())
            <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-lg">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('vendeur.storeProduit') }}" method="POST" class="bg-white p-6 shadow-lg rounded-2xl space-y-4">
            @csrf

            <div>
                <label class="block text-gray-700 mb-1">Marque</label>
                <input type="text" name="marque" class="w-full border rounded-lg p-2" value="{{ old('marque') }}">
            </div>

            <div>
                <label class="block text-gray-700 mb-1">Poids</label>
                <select name="poids" class="w-full border rounded-lg p-2">
                    <option value="">Sélectionner</option>
                    <option value="6kg" {{ old('poids')=='6kg'?'selected':'' }}>6kg</option>
                    <option value="12.5kg" {{ old('poids')=='12.5kg'?'selected':'' }}>12.5kg</option>
                    <option value="15kg" {{ old('poids')=='15kg'?'selected':'' }}>15kg</option>
                    <option value="35kg" {{ old('poids')=='35kg'?'selected':'' }}>35kg</option>
                </select>
            </div>

            <div>
                <label class="block text-gray-700 mb-1">Prix (FCFA)</label>
                <input type="number" name="prix" class="w-full border rounded-lg p-2" value="{{ old('prix') }}">
            </div>

            <div>
                <label class="block text-gray-700 mb-1">Quantité initiale</label>
                <input type="number" name="quantite" class="w-full border rounded-lg p-2" value="{{ old('quantite',0) }}">
            </div>

            <div class="flex justify-end space-x-2">
                <a href="{{ route('vendeur.stocks') }}" class="px-4 py-2 rounded-lg bg-gray-300 hover:bg-gray-400">Annuler</a>
                <button type="submit" class="px-4 py-2 rounded-lg bg-green-600 text-white hover:bg-green-700">Ajouter</button>
            </div>
        </form>
    </div>
</section>
@endsection
