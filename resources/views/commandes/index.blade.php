<!-- resources/views/commandes/index.blade.php -->
@extends('layouts.app')

@section('content')
<section class="py-10 bg-gray-50">
    <div class="max-w-7xl mx-auto px-6">
        <h2 class="text-3xl font-bold text-green-600 mb-6">Mes Commandes</h2>

        @if($commandes->isEmpty())
            <div class="p-6 bg-white shadow-lg rounded-2xl text-center text-gray-500">
                Vous n'avez encore aucune commande.
            </div>
        @else
            <div class="overflow-x-auto bg-white shadow-lg rounded-2xl">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-green-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Produit</th>
                            <th class="px-6 py-3 text-center text-sm font-semibold text-gray-700">Quantité</th>
                            <th class="px-6 py-3 text-center text-sm font-semibold text-gray-700">Total</th>
                            <th class="px-6 py-3 text-center text-sm font-semibold text-gray-700">Statut</th>
                            <th class="px-6 py-3 text-center text-sm font-semibold text-gray-700">Adresse</th>
                            <th class="px-6 py-3 text-center text-sm font-semibold text-gray-700">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($commandes as $c)
                            <tr class="hover:bg-green-50 transition">
                                <td class="px-6 py-4">{{ $c->produit->marque }} ({{ $c->produit->poids }})</td>
                                <td class="px-6 py-4 text-center">{{ $c->quantite }}</td>
                                <td class="px-6 py-4 text-center font-semibold text-green-600">{{ number_format($c->prix_total, 0, ',', ' ') }} FCFA</td>
                                <td class="px-6 py-4 text-center">
                                    @if($c->statut === 'livree')
                                        <span class="px-2 py-1 rounded-full bg-green-100 text-green-700 text-sm font-semibold">Livrée</span>
                                    @elseif($c->statut === 'en_attente')
                                        <span class="px-2 py-1 rounded-full bg-yellow-100 text-yellow-700 text-sm font-semibold">En attente</span>
                                    @elseif($c->statut === 'annulee')
                                        <span class="px-2 py-1 rounded-full bg-red-100 text-red-700 text-sm font-semibold">Annulée</span>
                                    @else
                                        <span class="px-2 py-1 rounded-full bg-gray-100 text-gray-700 text-sm font-semibold">{{ ucfirst($c->statut) }}</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center">
                                    {{ $c->adresse_livraison ?? 'Non renseignée' }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <button @click="showAddressModal({{ $c->id }}, '{{ $c->adresse_livraison }}')"
                                            class="px-3 py-1 bg-green-600 text-white rounded-full text-sm hover:bg-green-700 transition">
                                        Mettre à jour
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</section>

<!-- Modal pour mise à jour de l'adresse -->
<div x-data="{ showModal: false, commandeId: null, adresse: '' }">
    <div x-show="showModal" x-cloak class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
        <div class="bg-white rounded-xl shadow-lg p-6 w-96">
            <h3 class="text-xl font-bold text-green-600 mb-4">Mettre à jour l'adresse</h3>
            <input type="text" x-model="adresse" placeholder="Ville, quartier, rue..." 
                   class="w-full border border-gray-300 rounded-full px-4 py-2 mb-4">

            <div class="flex justify-end space-x-2">
                <button @click="showModal=false" class="px-4 py-2 rounded-lg bg-gray-300 hover:bg-gray-400">Annuler</button>
                <button @click="saveAddress()" class="px-4 py-2 rounded-lg bg-green-600 text-white hover:bg-green-700">Enregistrer</button>
            </div>
        </div>
    </div>
</div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
function updatePosition(id, lat, lng) {
    const modal = document.querySelector('[x-data]');
    modal.__x.$data.showMap = true;
    modal.__x.$data.commandeId = id;
    modal.__x.$data.lat = lat;
    modal.__x.$data.lng = lng;

    setTimeout(() => {
        const map = L.map('mapUpdate').setView([lat || 0, lng || 0], lat && lng ? 15 : 2);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 19 }).addTo(map);
        const marker = L.marker([lat || 0, lng || 0], { draggable: true }).addTo(map);
        marker.on('dragend', function(e) {
            modal.__x.$data.lat = e.target.getLatLng().lat;
            modal.__x.$data.lng = e.target.getLatLng().lng;
        });
    }, 100);
}

function savePosition() {
    const modal = document.querySelector('[x-data]');
    const data = {
        latitude: modal.__x.$data.lat,
        longitude: modal.__x.$data.lng
    };
    fetch(`/commandes/${modal.__x.$data.commandeId}/update-position`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        body: JSON.stringify(data)
    }).then(res => location.reload());
}
</script>

<script>
function showAddressModal(id, adresse) {
    const modal = document.querySelector('[x-data]');
    modal.__x.$data.commandeId = id;
    modal.__x.$data.adresse = adresse ?? '';
    modal.__x.$data.showModal = true;
}

function saveAddress() {
    const modal = document.querySelector('[x-data]');
    fetch(`/commandes/${modal.__x.$data.commandeId}/update-address`, {
        method: 'POST',
        headers: { 
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ adresse_livraison: modal.__x.$data.adresse })
    }).then(res => location.reload());
}
</script>

@endsection
