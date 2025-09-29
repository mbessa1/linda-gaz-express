    @extends('layouts.app')

    @section('content')
    <section class="py-10 bg-gray-100">
        <div class="container mx-auto px-6 max-w-lg">
            <h3 class="text-3xl font-bold text-center text-gray-800 mb-8">Passer une commande</h3>

            <form method="POST" action="{{ route('commande.store') }}" id="commandeForm" 
                class="bg-white p-6 rounded-2xl shadow-lg space-y-6">
                @csrf

                <!-- Produit -->
                @if($produit)
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Produit</label>
                        <input type="text" value="{{ $produit->marque }} - {{ $produit->poids }} ({{ number_format($produit->prix,0,',',' ') }} FCFA)" 
                            class="w-full border border-gray-300 rounded-full px-4 py-2 bg-gray-100 cursor-not-allowed" readonly>
                        <input type="hidden" name="gaz_id" value="{{ $produit->id }}">
                    </div>
                @else
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Produit</label>
                        <select name="gaz_id" class="w-full border border-gray-300 rounded-full px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                            @foreach($produits as $p)
                                <option value="{{ $p->id }}">
                                    {{ $p->marque }} - {{ $p->poids }} ({{ number_format($p->prix, 0, ',', ' ') }} FCFA)
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endif

                <!-- Quantité -->
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Quantité</label>
                    <input type="number" name="quantite" min="1" required
                        class="w-full border border-gray-300 rounded-full px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                </div>

                <!-- Adresse -->
                <div>
                    <label for="adresse_livraison" class="block text-gray-700 font-semibold mb-2">Adresse de livraison</label>
                    <input type="text" name="adresse_livraison" id="adresse_livraison" required
                        class="w-full border border-gray-300 rounded-full px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                </div>

                <!-- Champs cachés pour géolocalisation -->
                <input type="hidden" name="latitude" id="latitude">
                <input type="hidden" name="longitude" id="longitude">

                <!-- Statut géolocalisation -->
                <div id="localisation-status" class="text-sm text-blue-600 font-medium mb-4"></div>

                <!-- Carte pour afficher la position -->
                <div id="map" class="w-full h-64 rounded-2xl shadow-lg mb-4" style="display:none;"></div>

                <!-- Bouton -->
                <button type="submit" 
                        class="w-full bg-green-600 text-white font-bold py-3 rounded-full hover:bg-green-700 transition">
                    🛒 Valider la commande
                </button>
            </form>
        </div>
    </section>

    <!-- Leaflet CSS & JS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <script>
    document.addEventListener("DOMContentLoaded", () => {
        const status = document.getElementById("localisation-status");
        const mapDiv = document.getElementById("map");
        const latInput = document.getElementById("latitude");
        const lngInput = document.getElementById("longitude");
        const adresseInput = document.getElementById("adresse_livraison");

        if (navigator.geolocation) {
            status.innerText = "📍 Récupération de votre position...";
            
            navigator.geolocation.getCurrentPosition(
                async (position) => {
                    const lat = position.coords.latitude;
                    const lng = position.coords.longitude;

                    latInput.value = lat;
                    lngInput.value = lng;

                    // Afficher la carte
                    mapDiv.style.display = "block";
                    const map = L.map('map').setView([lat, lng], 15);
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 19 }).addTo(map);

                    // Reverse geocoding avec Nominatim
                    const response = await fetch(`https://nominatim.openstreetmap.org/reverse?lat=${lat}&lon=${lng}&format=json`);
                    const data = await response.json();
                    const address = data.display_name || "Adresse non trouvée";

                    // Remplir le champ adresse automatiquement
                    adresseInput.value = address;

                    // Marker avec adresse exacte
                    L.marker([lat, lng]).addTo(map)
                    .bindPopup(`📍 ${address}`)
                    .openPopup();

                    status.innerText = "✅ Position détectée : " + address;
                    status.classList.remove("text-blue-600");
                    status.classList.add("text-green-600");
                },
                (error) => {
                    status.innerText = "❌ Impossible de récupérer votre position";
                    status.classList.remove("text-blue-600");
                    status.classList.add("text-red-600");
                    console.error(error);
                }
            );
        } else {
            status.innerText = "⚠️ La géolocalisation n'est pas supportée par ce navigateur.";
            status.classList.add("text-red-600");
        }
    });
    </script>
    @endsection
