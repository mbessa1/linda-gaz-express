<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Nouvelle Livraison</title>
</head>
<body>
    <p>Bonjour {{ $commande->livraison->livreur->name ?? 'Livreur' }},</p>
    <p>Une nouvelle livraison vous a été assignée :</p>
    <ul>
        <li>Commande #{{ $commande->id }}</li>
        <li>Adresse de livraison : {{ $commande->adresse_livraison }}</li>
        <li>Produit : {{ $commande->produit->marque }} ({{ $commande->quantite }})</li>
    </ul>
    <p>Merci de la traiter rapidement.</p>
</body>
</html>
