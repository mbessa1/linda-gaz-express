@extends('layouts.app')

@section('content')
<section class="py-10 min-h-screen rounded-2xl" style="background-color: #0a1628 !important;">
    <div class="container mx-auto px-6 max-w-lg">
        <h3 class="text-3xl font-bold text-center mb-8" style="color:#f0b429 !important;">🛒 Passer une commande</h3>

        <form method="POST" action="{{ route('commande.store') }}" id="commandeForm"
              class="p-6 rounded-2xl shadow-2xl space-y-6 border"
              style="background-color:#0d2044 !important; border-color:rgba(240,180,41,0.4) !important;">
            @csrf

            @if($errors->any())
                <div class="px-4 py-3 rounded-xl text-sm border" style="background:rgba(239,68,68,0.2) !important; border-color:#ef4444 !important; color:#ef4444 !important;">
                    @foreach($errors->all() as $error)
                        <p>❌ {{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <!-- Produit -->
            <div>
                <label class="block font-semibold mb-2" style="color:#f0b429 !important;">Produit</label>
                <select name="gaz_id" required
                        class="w-full border rounded-xl px-4 py-3 focus:outline-none"
                        style="background-color:#0a1628 !important; border-color:#f0b429 !important; color:#f0b429 !important;">
                    <option value="" style="background-color:#0a1628; color:#f0b429;">-- Choisir un produit --</option>
                    @foreach($produits as $produit)
                        <option value="{{ $produit->id }}" style="background-color:#0a1628; color:#f0b429;"
                            {{ isset($produit) && $produit->id == request('produit_id') ? 'selected' : '' }}>
                            {{ $produit->marque }} - {{ $produit->poids }} ({{ number_format($produit->prix, 0, ',', ' ') }} FCFA)
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Quantité -->
            <div>
                <label class="block font-semibold mb-2" style="color:#f0b429 !important;">Quantité</label>
                <input type="number" name="quantite" min="1" value="1" required
                       class="w-full border rounded-xl px-4 py-3 focus:outline-none"
                       style="background-color:#0a1628 !important; border-color:#f0b429 !important; color:#f0b429 !important;">
            </div>

            <!-- Adresse -->
            <div>
                <label class="block font-semibold mb-2" style="color:#f0b429 !important;">Adresse de livraison</label>
                <input type="text" name="adresse_livraison" id="adresse_livraison" required
                       placeholder="Votre adresse de livraison"
                       class="w-full border rounded-xl px-4 py-3 focus:outline-none"
                       style="background-color:#0a1628 !important; border-color:#f0b429 !important; color:#f0b429 !important;">
                <input type="hidden" name="latitude" id="latitude">
                <input type="hidden" name="longitude" id="longitude">
            </div>

            <!-- Carte -->
            <div>
                <label class="block font-semibold mb-2" style="color:#f0b429 !important;">📍 Votre position sur la carte</label>
                <div id="map" class="w-full rounded-xl border overflow-hidden" style="height:300px; border-color:rgba(240,180,41,0.4) !important;"></div>
                <button type="button" onclick="getLocation()"
                        class="mt-3 w-full py-2.5 rounded-xl font-bold transition hover:opacity-80"
                        style="background:rgba(240,180,41,0.2) !important; color:#f0b429 !important; border:1px solid #f0b429 !important;">
                    📍 Détecter ma position GPS
                </button>
            </div>

            <!-- Bouton -->
            <button type="submit"
                    class="w-full py-3 rounded-full font-bold text-lg transition hover:opacity-80 shadow-lg"
                    style="background-color:#f0b429 !important; color:#0a1628 !important;">
                ✅ Valider la commande
            </button>
        </form>
    </div>
</section>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
var map = L.map('map').setView([3.848, 11.502], 13);
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© OpenStreetMap'
}).addTo(map);

var marker;

function setMarker(lat, lng) {
    if (marker) map.removeLayer(marker);
    marker = L.marker([lat, lng]).addTo(map);
    document.getElementById('latitude').value = lat;
    document.getElementById('longitude').value = lng;

    fetch('https://nominatim.openstreetmap.org/reverse?format=json&lat=' + lat + '&lon=' + lng)
        .then(r => r.json())
        .then(data => {
            document.getElementById('adresse_livraison').value = data.display_name || lat + ', ' + lng;
        });
}

map.on('click', function(e) {
    setMarker(e.latlng.lat, e.latlng.lng);
});

function getLocation() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(pos) {
            var lat = pos.coords.latitude;
            var lng = pos.coords.longitude;
            map.setView([lat, lng], 15);
            setMarker(lat, lng);
        });
    }
}

getLocation();
</script>
@endsection
    