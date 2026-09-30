@extends('layouts.app')

@section('content')
<section class="py-10 bg-gray-50">
    <div class="max-w-4xl mx-auto px-6">
        <h2 class="text-3xl font-bold text-blue-600 mb-6">Statistiques des Ventes</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="p-6 bg-white shadow-lg rounded-2xl text-center">
                <h3 class="text-xl font-semibold text-gray-700 mb-2">Total ventes</h3>
                <p class="text-3xl font-bold text-blue-600">{{ $ventes }}</p>
            </div>

            <div class="p-6 bg-white shadow-lg rounded-2xl text-center">
                <h3 class="text-xl font-semibold text-gray-700 mb-2">Revenus générés</h3>
                <p class="text-3xl font-bold text-blue-600">{{ number_format($revenus, 0, ',', ' ') }} FCFA</p>
            </div>
        </div>
    </div>
</section>
@endsection
