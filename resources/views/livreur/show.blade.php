@extends('layouts.app')

@section('content')
<h1 class="text-2xl font-bold mb-4">Suivi de la Livraison</h1>

<div class="mb-4">
    <p><strong>Produit :</strong> {{ $livraison->commande->produit->marque }} ({{ $livraison->commande->produit->poids }})</p>
    <p><strong>Client :</strong> {{ $livraison->commande->client->name }}</p>
    <p><strong>Adresse :</strong> {{ $livraison->commande->adresse_livraison }}</p>
    <p><strong>Quantité :</strong> {{ $livraison->commande->quantite }}</p>
    <p><strong>État :</strong> {{ $livraison->etat }}</p>
</div>

<div id="map" class="h-96 w-full rounded-lg mb-4"></div>

<!-- Bouton pour confirmer livraison -->
<form action="{{ route('livreur.commandes.confirmer', $livraison->id) }}" method="POST">
    @csrf
    <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-500">
        Confirmer Livraison
    </button>
</form>

<script>
function initMap() {
    // Position initiale du client
    const initialPos = { 
        lat: {{ $livraison->commande->latitude ?? 0 }}, 
        lng: {{ $livraison->commande->longitude ?? 0 }} 
    };

    const map = new google.maps.Map(document.getElementById('map'), {
        zoom: 14,
        center: initialPos
    });

    const marker = new google.maps.Marker({
        position: initialPos,
        map: map,
        title: "Client"
    });

    // Rafraîchissement toutes les 5 secondes
    setInterval(() => {
        fetch("{{ url('/livreur/livraison/'.$livraison->id.'/position') }}")
            .then(res => res.json())
            .then(data => {
                const newPos = {
                    lat: parseFloat(data.latitude),
                    lng: parseFloat(data.longitude)
                };
                marker.setPosition(newPos);
                map.setCenter(newPos);
            })
            .catch(err => console.error('Erreur fetch position:', err));
    }, 5000);
}
</script>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCoB8Ji8J0a10YLVtD3IsgOCV4w7E0-OX0&callback=initMap" async defer></script>
@endsection
