@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-6" x-data="{ detailId: null }">

    <h1 class="text-2xl font-bold mb-4">Mes Livraisons</h1>

    @if(session('success'))
        <div class="bg-blue-100 text-red-800 p-3 rounded mb-4">{{ session('success') }}</div>
    @endif

    <!-- Liste des livraisons -->
    <template x-for="livraison in {{ $livraisons->toJson() }}" :key="livraison.id">
        <div class="bg-white shadow rounded-lg p-4 mb-3 flex flex-col md:flex-row md:items-center md:justify-between">
            <div class="mb-2 md:mb-0">
                <p class="font-bold" x-text="livraison.commande.produit.marque + ' (' + livraison.commande.produit.poids + ')'"></p>
                <p class="text-gray-600 text-sm" x-text="livraison.commande.client.name"></p>
                <p class="text-gray-600 text-sm" x-text="livraison.commande.adresse_livraison"></p>
            </div>
            <div class="flex items-center space-x-2">
                <span 
                    class="px-2 py-1 rounded text-sm font-semibold"
                    :class="livraison.etat === 'effectuee' ? 'bg-blue-100 text-blue-700' : 'bg-yellow-100 text-yellow-700'"
                    x-text="livraison.etat === 'effectuee' ? 'Livrée' : 'En cours'">
                </span>
                <button
                    x-show="livraison.etat === 'en_cours'"
                    @click="detailId = livraison.id; $nextTick(() => initMap(livraison.id, livraison.commande.latitude, livraison.commande.longitude))"
                    class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-500 text-sm">
                    Détails / Confirmer
                </button>
            </div>
        </div>
    </template>

    <!-- Modal Détail Livraison -->
    <div x-show="detailId" x-cloak class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-xl shadow-lg p-6 w-full max-w-lg relative" @click.away="detailId = null">
            <button @click="detailId = null" class="absolute top-3 right-3 text-gray-500 hover:text-gray-800">&times;</button>

            <template x-if="detailId">
                <div x-data="">
                    @foreach($livraisons as $livraison)
                        <div x-show="detailId === {{ $livraison->id }}" class="space-y-2">
                            <h2 class="text-xl font-bold text-blue-600">{{ $livraison->commande->produit->marque }} ({{ $livraison->commande->produit->poids }})</h2>
                            <p><strong>Client :</strong> {{ $livraison->commande->client->name ?? 'N/A' }}</p>
                            <p><strong>Adresse :</strong> {{ $livraison->commande->adresse_livraison }}</p>
                            <p><strong>Quantité :</strong> {{ $livraison->commande->quantite }}</p>
                            <p><strong>État :</strong> <span x-text="'{{ $livraison->etat }}' === 'en_cours' ? 'En cours' : 'Livrée'"></span></p>

                            <!-- Carte dynamique -->
                            <div id="map-{{ $livraison->id }}" class="h-64 w-full rounded-lg mt-2"></div>

                            <!-- Formulaire confirmer livraison -->
                            <form action="{{ route('livreur.commandes.confirmer', $livraison->id) }}" method="POST" class="mt-4">
                                @csrf
                                <button type="submit" class="w-full px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-500">
                                    Confirmer Livraison
                                </button>
                            </form>
                        </div>
                    @endforeach
                </div>
            </template>
        </div>
    </div>

</div>

<!-- Script Google Maps + suivi client -->
<script>
function initMap(livraisonId, lat, lng) {
    const mapElement = document.getElementById('map-' + livraisonId);
    if (!mapElement) return;

    const initialPos = { lat: parseFloat(lat) || 0, lng: parseFloat(lng) || 0 };
    const map = new google.maps.Map(mapElement, { zoom: 14, center: initialPos });
    const marker = new google.maps.Marker({ position: initialPos, map: map, title: "Client" });

    // Rafraîchissement toutes les 5 secondes
    setInterval(() => {
        fetch(`/livreur/livraison/${livraisonId}/position`)
            .then(res => res.json())
            .then(data => {
                const newPos = { lat: parseFloat(data.latitude) || 0, lng: parseFloat(data.longitude) || 0 };
                marker.setPosition(newPos);
                map.setCenter(newPos);
            })
            .catch(err => console.error('Erreur fetch position:', err));
    }, 5000);
}
</script>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCoB8Ji8J0a10YLVtD3IsgOCV4w7E0-OX0&callback=initMap" async defer></script>
@endsection
