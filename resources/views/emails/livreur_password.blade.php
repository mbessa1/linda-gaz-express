<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Compte Livreur - Gaz Express</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px;">
    <div style="max-width: 600px; margin: auto; background-color: #ffffff; border-radius: 8px; padding: 20px; box-shadow: 0 0 10px rgba(0,0,0,0.1);">
        <h2 style="color: #22c55e;">Bienvenue chez Gaz Express !</h2>
        <p>Bonjour {{ $livreur->name }},</p>
        <p>Votre compte Livreur a été créé avec succès.</p>
        <p><strong>Vos identifiants :</strong></p>
        <ul>
            <li>Email : {{ $livreur->email }}</li>
            <li>Mot de passe : {{ $password }}</li>
        </ul>
        <p>Nous vous conseillons de changer votre mot de passe après votre première connexion.</p>
        <p>Merci et bonne route !</p>
        <p style="margin-top: 20px; font-size: 12px; color: #555;">© {{ date('Y') }} Gaz Express</p>
    </div>
</body>
</html>
