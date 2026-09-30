<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; background: #f5f5f5; margin: 0; padding: 0; }
        .container { max-width: 600px; margin: 30px auto; background: white; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.1); }
        .header { background: #2563eb; color: white; padding: 30px; text-align: center; }
        .header h1 { margin: 0; font-size: 28px; }
        .body { padding: 30px; }
        .info-box { background: #fff5f5; border-left: 4px solid #2563eb; padding: 15px; border-radius: 8px; margin: 20px 0; }
        .info-row { display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid #f0f0f0; }
        .label { color: #666; font-size: 14px; }
        .value { font-weight: bold; color: #333; }
        .total { font-size: 24px; color: #2563eb; font-weight: bold; }
        .footer { background: #f9f9f9; padding: 20px; text-align: center; color: #999; font-size: 12px; }
        .btn { display: inline-block; background: #2563eb; color: white; padding: 12px 30px; border-radius: 50px; text-decoration: none; font-weight: bold; margin: 20px 0; }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>🫙 Gaz Express</h1>
            <p style="margin:5px 0; opacity:0.9;">Confirmation de commande</p>
        </div>

        <!-- Body -->
        <div class="body">
            <h2 style="color:#333;">Bonjour {{ $commande->client->name }} ! 👋</h2>
            <p style="color:#666; font-size:16px;">
                Votre commande a été <strong style="color:#16a34a;">confirmée et payée avec succès</strong> ! 
                Un livreur vous sera assigné dans les plus brefs délais.
            </p>

            <!-- Détails commande -->
            <div class="info-box">
                <h3 style="margin:0 0 15px 0; color:#2563eb;">📦 Détails de votre commande</h3>
                <div class="info-row">
                    <span class="label">Produit</span>
                    <span class="value">{{ $commande->produit->marque ?? 'N/A' }} - {{ $commande->produit->poids ?? '' }}</span>
                </div>
                <div class="info-row">
                    <span class="label">Quantité</span>
                    <span class="value">{{ $commande->quantite }} bouteille(s)</span>
                </div>
                <div class="info-row">
                    <span class="label">Adresse de livraison</span>
                    <span class="value">{{ $commande->adresse_livraison }}</span>
                </div>
                <div class="info-row">
                    <span class="label">Statut</span>
                    <span class="value" style="color:#16a34a;">✅ Payée</span>
                </div>
                <div class="info-row" style="border:none;">
                    <span class="label">Total payé</span>
                    <span class="total">{{ number_format($commande->prix_total, 0, ',', ' ') }} FCFA</span>
                </div>
            </div>

            <!-- Délai -->
            <div style="background:#f0fdf4; border-radius:8px; padding:15px; text-align:center; margin:20px 0;">
                <p style="margin:0; color:#16a34a; font-size:18px; font-weight:bold;">
                    🚀 Délai de livraison estimé : <strong>30 à 60 minutes</strong>
                </p>
            </div>

            <p style="color:#666;">
                Si vous avez des questions, contactez-nous via notre application ou répondez à cet email.
            </p>

            <a href="http://localhost:8080" class="btn">
                Voir mes commandes →
            </a>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>© {{ date('Y') }} Gaz Express – Énergie Verte & Livraison Rapide ⚡</p>
            <p>Yaoundé, Cameroun</p>
        </div>
    </div>
</body>
</html>