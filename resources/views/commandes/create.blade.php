<!-- resources/views/commandes/create.blade.php -->
@extends('layouts.app')

@section('content')
<section class="py-10 bg-gray-100">
    <div class="container mx-auto px-6 max-w-lg">
        <h2 class="text-3xl font-bold text-center text-gray-800 mb-8">Passer une commande</h2>

        <form action="{{ route('commandes.store') }}" method="POST" class="bg-white p-6 rounded-2xl shadow-lg space-y-6">
            @csrf

            <div>
                <label for="gaz_id" class="block text-gray-700 font-semibold mb-2">Type de gaz</label>
                <select name="gaz_id" class="w-full border border-gray-300 rounded-full px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                    @foreach($produits as $produit)
                        <option value="{{ $produit->id }}">{{ $produit->marque }} - {{ number_format($produit->prix, 0, ',', ' ') }} FCFA</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="quantite" class="block text-gray-700 font-semibold mb-2">Quantité</label>
                <input type="number" name="quantite" min="1" required
                       class="w-full border border-gray-300 rounded-full px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
            </div>

            <div>
                <label for="adresse_livraison" class="block text-gray-700 font-semibold mb-2">Adresse de livraison</label>
                <input type="text" name="adresse_livraison" id="adresse_livraison" required
                       class="w-full border border-gray-300 rounded-full px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
            </div>

            <div>
                <label for="coordonnees_gps" class="block text-gray-700 font-semibold mb-2">Coordonnées GPS (optionnel)</label>
                <input type="text" name="coordonnees_gps"
                       class="w-full border border-gray-300 rounded-full px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
            </div>

            <button type="submit"
                    class="w-full bg-green-600 text-white font-bold py-3 rounded-full hover:bg-green-700 transition">
                🛒 Commander
            </button>
        </form>
    </div>
</section>
@endsection

<script>
document.addEventListener("DOMContentLoaded", () => {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(async (pos) => {
            const lat = pos.coords.latitude;
            const lng = pos.coords.longitude;

            // Reverse geocoding avec Nominatim
            const res = await fetch(`https://nominatim.openstreetmap.org/reverse?lat=${lat}&lon=${lng}&format=json`);
            const data = await res.json();
            const address = data.display_name || "";
            document.getElementById("adresse_livraison").value = address;
        });
    }
});
</script>
