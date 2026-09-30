@extends('layouts.app')

@section('content')
<h1 class="text-2xl font-bold mb-4">Gérer les livreurs</h1>

@if(session('success'))
    <div class="bg-blue-100 text-red-800 p-2 rounded mb-4">{{ session('success') }}</div>
@endif

@if($errors->any())
    <div class="bg-blue-100 text-red-800 p-2 rounded mb-4">
        <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<!-- Formulaire création -->
<div class="mb-6 p-4 border rounded-lg bg-white shadow-sm">
    <h2 class="font-bold mb-2">Ajouter un livreur</h2>
    <form action="{{ route('vendeur.storeLivreur') }}" method="POST" class="space-y-2">
        @csrf
        <div>
            <input type="text" name="name" placeholder="Nom" class="w-full border p-2 rounded" required>
        </div>
        <div>
            <input type="email" name="email" placeholder="Email" class="w-full border p-2 rounded" required>
        </div>
        <div class="flex space-x-2">
            <select name="country_code" id="country_code" class="border p-2 rounded" required>
                <option value="+237" data-example="698123456">Cameroon (+237)</option>
                <option value="+33" data-example="612345678">France (+33)</option>
                <option value="+1" data-example="5551234567">USA (+1)</option>
                <option value="+44" data-example="7123456789">UK (+44)</option>
                <option value="+229" data-example="90123456">Benin (+229)</option>
                <option value="+225" data-example="01234567">Côte d'Ivoire (+225)</option>
                <option value="+243" data-example="812345678">RDC (+243)</option>
            </select>
            <input type="text" name="phone" id="phone" placeholder="Numéro" class="flex-1 border p-2 rounded" required>
        </div>
        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-500">Ajouter</button>
    </form>
</div>

<!-- Liste des livreurs -->
<div class="p-4 border rounded-lg bg-white shadow-sm">
    <h2 class="font-bold mb-2">Liste des livreurs</h2>
    <table class="w-full table-auto border-collapse border">
        <thead>
            <tr class="bg-blue-50">
                <th class="border px-2 py-1">Nom</th>
                <th class="border px-2 py-1">Email</th>
                <th class="border px-2 py-1">Téléphone</th>
                <th class="border px-2 py-1">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($livreurs as $livreur)
                <tr>
                    <td class="border px-2 py-1">{{ $livreur->name }}</td>
                    <td class="border px-2 py-1">{{ $livreur->email }}</td>
                    <td class="border px-2 py-1">{{ $livreur->phone }}</td>
                    <td class="border px-2 py-1">
                        @if($livreur->status === 'active')
                            <form action="{{ route('vendeur.blockLivreur', $livreur->id) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="px-3 py-1 bg-blue-600 text-white rounded">Bloquer</button>
                            </form>
                        @else
                            <form action="{{ route('vendeur.unblockLivreur', $livreur->id) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="px-3 py-1 bg-blue-600 text-white rounded">Débloquer</button>
                            </form>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Script JS pour placeholder dynamique -->
<script>
    const countrySelect = document.getElementById('country_code');
    const phoneInput = document.getElementById('phone');

    function updatePlaceholder() {
        const selectedOption = countrySelect.options[countrySelect.selectedIndex];
        const example = selectedOption.getAttribute('data-example');
        phoneInput.placeholder = example;
    }

    countrySelect.addEventListener('change', updatePlaceholder);
    window.addEventListener('DOMContentLoaded', updatePlaceholder);
</script>
@endsection
