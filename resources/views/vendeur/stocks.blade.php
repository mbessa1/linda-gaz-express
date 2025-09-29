@extends('layouts.app')

@section('content')
<section class="py-10 bg-gray-50">
    <div class="max-w-7xl mx-auto px-6">
        <h2 class="text-3xl font-bold text-green-600 mb-6">Gestion des Stocks</h2>

        @if(session('success'))
            <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        <div class="overflow-x-auto bg-white shadow-lg rounded-2xl">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-green-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Produit</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Quantité</th>
                        <th class="px-6 py-3 text-center text-sm font-semibold text-gray-700">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($stocks as $s)
                        <tr class="hover:bg-green-50 transition">
                            <td class="px-6 py-4">{{ $s->produit->marque }} - {{ $s->produit->poids }}</td>
                            <td class="px-6 py-4">{{ $s->quantite }}</td>
                            <td class="px-6 py-4 text-center">
                                <form method="POST" action="{{ route('vendeur.updateStock') }}" class="flex justify-center space-x-2">
                                    @csrf
                                    <input type="hidden" name="stock_id" value="{{ $s->id }}">
                                    <input type="number" name="quantite" value="{{ $s->quantite }}" class="border rounded-lg w-20 px-2 py-1 text-center">
                                    <button class="bg-green-600 text-white px-4 py-1 rounded-lg hover:bg-green-700 transition">Mettre à jour</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    @if($stocks->isEmpty())
                        <tr>
                            <td colspan="3" class="px-6 py-4 text-center text-gray-500">
                                Aucun stock disponible.
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</section>
@endsection
