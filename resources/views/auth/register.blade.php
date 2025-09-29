@extends('layouts.app')

@section('content')
<div class="flex justify-center items-center min-h-screen bg-gradient-to-br from-skyBlue via-white to-forestGreen/20 px-4">
    <div class="w-full max-w-md bg-white shadow-2xl rounded-2xl p-8 animate-fadeIn">
        
        <h3 class="text-2xl font-bold text-center text-forestGreen mb-6">📝 Créer un compte</h3>
        
        <form method="POST" action="{{ route('register') }}" class="space-y-4">
            @csrf

            <!-- Nom -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nom</label>
                <input type="text" name="name" required
                       class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-solarYellow focus:outline-none transition">
            </div>

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

            <!-- Confirmation -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Confirmer mot de passe</label>
                <input type="password" name="password_confirmation" required
                       class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-solarYellow focus:outline-none transition">
            </div>

            <!-- Rôle -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Vous êtes</label>
                <select name="role" id="role" required
                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-solarYellow focus:outline-none transition">
                    <option value="client">Client</option>
                    <option value="vendeur">Vendeur</option>
                </select>
            </div>

            <!-- Champs localisation pour vendeur -->
            <div id="location-fields" class="hidden mt-4">
                <p class="text-sm text-gray-600 mb-2">
                    📍 Votre position sera utilisée pour afficher vos produits aux clients proches.
                </p>

                <!-- Champs cachés envoyés au serveur -->
                <input type="" name="latitude" id="latitude">
                <input type="" name="longitude" id="longitude">
                <input type="" name="ville" id="ville">
                <input type="" name="quartier" id="quartier">

                <!-- Input recherche adresse -->
                <input type="text" id="search-address" class="w-full border rounded-full px-4 py-2 mb-2" placeholder="Tapez votre ville ou quartier">

                <!-- Affichage adresse complète -->
                <input type="text" id="adresse_complete" class="w-full border rounded-full px-4 py-2 mb-2" placeholder="Adresse complète : pays, ville, quartier" readonly>

                <div id="localisation-status" class="text-sm text-blue-600 font-medium mb-2"></div>
                <div id="map" class="w-full h-64 rounded-2xl shadow-lg mb-4"></div>
            </div>

            <!-- Bouton -->
            <button type="submit"
                class="w-full py-3 bg-forestGreen text-black font-semibold rounded-lg shadow-md hover:bg-green-700 transition duration-300 transform hover:scale-105">
                inscrire
            </button>
        </form>

        <!-- Lien vers Connexion -->
        <p class="mt-6 text-center text-sm text-gray-600">
            Déjà un compte ? 
            <a href="{{ route('login') }}" class="text-skyBlue font-semibold hover:underline"> Se connecter</a>
        </p>
    </div>
</div>

<!-- Leaflet CSS & JS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
document.addEventListener("DOMContentLoaded", () => {
    const roleSelect = document.getElementById("role");
    const locationFields = document.getElementById("location-fields");
    const status = document.getElementById("localisation-status");
    const mapDiv = document.getElementById("map");
    const latInput = document.getElementById("latitude");
    const lngInput = document.getElementById("longitude");
    const villeInput = document.getElementById("ville");
    const quartierInput = document.getElementById("quartier");
    const adresseInput = document.getElementById("adresse_complete");
    const searchInput = document.getElementById("search-address");
    let map, marker;

    function updateAddressData(display) {
        const adresse = `${display.country || ''}, ${display.city || display.town || ''}, ${display.suburb || display.village || ''}`;
        adresseInput.value = adresse;
        villeInput.value = display.city || display.town || '';
        quartierInput.value = display.suburb || display.village || '';
        return adresse;
    }

    roleSelect.addEventListener("change", () => {
        if (roleSelect.value === "vendeur") {
            locationFields.classList.remove("hidden");
            mapDiv.style.display = "block";

            if (!map) {
                map = L.map('map').setView([0, 0], 2);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 19 }).addTo(map);
                marker = L.marker([0, 0], { draggable: true }).addTo(map);

                marker.on('dragend', async function(e) {
                    const pos = e.target.getLatLng();
                    latInput.value = pos.lat;
                    lngInput.value = pos.lng;

                    const response = await fetch(`https://nominatim.openstreetmap.org/reverse?lat=${pos.lat}&lon=${pos.lng}&format=json`);
                    const data = await response.json();
                    const adresse = updateAddressData(data.address);

                    status.innerText = "📍 Position ajustée : " + adresse;
                    status.classList.replace("text-blue-600", "text-green-600");
                });
            }

            // Auto-géolocalisation
            if (navigator.geolocation) {
                status.innerText = "📍 Récupération de votre position...";
                navigator.geolocation.getCurrentPosition(
                    async (position) => {
                        const lat = position.coords.latitude;
                        const lng = position.coords.longitude;

                        latInput.value = lat;
                        lngInput.value = lng;

                        map.setView([lat, lng], 15);
                        marker.setLatLng([lat, lng]);

                        const response = await fetch(`https://nominatim.openstreetmap.org/reverse?lat=${lat}&lon=${lng}&format=json`);
                        const data = await response.json();
                        const adresse = updateAddressData(data.address);

                        status.innerText = "✅ Position détectée : " + adresse;
                        status.classList.replace("text-blue-600", "text-green-600");
                    },
                    () => {
                        status.innerText = "❌ Impossible de récupérer votre position automatiquement. Utilisez la recherche ou déplacez le marqueur.";
                        status.classList.replace("text-blue-600", "text-red-600");
                    }
                );
            }

            // Recherche automatique avec suggestions
            let timeout = null;
            searchInput.addEventListener("input", () => {
                clearTimeout(timeout);
                timeout = setTimeout(async () => {
                    const query = searchInput.value;
                    if (query.length < 3) return;

                    const response = await fetch(`https://nominatim.openstreetmap.org/search?format=json&addressdetails=1&q=${encodeURIComponent(query)}`);
                    const results = await response.json();
                    if (results.length === 0) return;

                    const place = results[0];
                    const lat = parseFloat(place.lat);
                    const lon = parseFloat(place.lon);

                    map.setView([lat, lon], 15);
                    marker.setLatLng([lat, lon]);

                    latInput.value = lat;
                    lngInput.value = lon;

                    const adresse = updateAddressData(place.address);

                    status.innerText = "📍 Position choisie : " + adresse;
                    status.classList.replace("text-blue-600", "text-green-600");
                }, 500);
            });

        } else {
            locationFields.classList.add("hidden");
            latInput.value = '';
            lngInput.value = '';
            villeInput.value = '';
            quartierInput.value = '';
            adresseInput.value = '';
        }
    });
});
</script>

@endsection
