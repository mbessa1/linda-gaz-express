@extends('layouts.app')

@section('content')
<section class="py-10">
<div class="container mx-auto px-6 max-w-md">
<h2 class="text-3xl font-bold text-center mb-8" style="color:#f0b429;">💳 Paiement Mobile Money</h2>

<!-- Résumé commande -->
<div class="p-6 mb-6 rounded-2xl border" style="background:rgba(13,32,68,0.8);border-color:rgba(240,180,41,0.4);">
<h3 class="text-xl font-bold mb-4" style="color:#f0b429;">📦 Résumé de votre commande</h3>
<div class="flex justify-between mb-2 text-base" style="color:rgba(240,180,41,0.8);"><span>Produit</span><span style="color:#f0b429;">{{ $commande->produit->marque ?? 'N/A' }} - {{ $commande->produit->poids ?? '' }}</span></div>
<div class="flex justify-between mb-2 text-base" style="color:rgba(240,180,41,0.8);"><span>Quantité</span><span style="color:#f0b429;">{{ $commande->quantite }}</span></div>
<div class="flex justify-between mb-2 text-base" style="color:rgba(240,180,41,0.8);"><span>Adresse</span><span style="color:#f0b429;">{{ $commande->adresse_livraison }}</span></div>
<div class="border-t pt-3 mt-3 flex justify-between font-bold" style="border-color:rgba(240,180,41,0.3);">
<span class="text-xl" style="color:#f0b429;">Total</span>
<span class="text-2xl" style="color:#f0b429;">{{ number_format($commande->prix_total, 0, ',', ' ') }} FCFA</span>
</div>
</div>

<!-- Formulaire paiement -->
<div class="p-6 rounded-2xl border" id="payment-form" style="background:rgba(13,32,68,0.8);border-color:rgba(240,180,41,0.4);">
<h3 class="text-xl font-bold mb-4" style="color:#f0b429;">📱 Payer via Mobile Money</h3>

<form method="POST" action="{{ route('paiement.confirmer', $commande->id) }}" id="real-form">
@csrf
<input type="hidden" name="telephone" id="hidden-telephone">
<input type="hidden" name="operateur" id="hidden-operateur">
</form>

<!-- Étape 1 - Choix opérateur -->
<div id="step1">
<label class="block text-lg font-semibold mb-3" style="color:#f0b429;">Choisissez votre opérateur</label>
<div class="grid grid-cols-2 gap-3 mb-6">
    <button onclick="choixOperateur('mtn')" id="btn-mtn"
        class="border-2 rounded-xl p-4 text-center transition hover:opacity-80"
        style="border-color:rgba(240,180,41,0.3);background:rgba(10,22,40,0.5);">
        <div class="text-4xl mb-2">📡</div>
        <p class="text-lg font-bold" style="color:#f0b429;">MTN MoMo</p>
        <p class="text-xs mt-1" style="color:rgba(240,180,41,0.5);">*126#</p>
    </button>
    <button onclick="choixOperateur('orange')" id="btn-orange"
        class="border-2 rounded-xl p-4 text-center transition hover:opacity-80"
        style="border-color:rgba(240,180,41,0.3);background:rgba(10,22,40,0.5);">
        <div class="text-4xl mb-2">🟠</div>
        <p class="text-lg font-bold text-orange-400">Orange Money</p>
        <p class="text-xs mt-1" style="color:rgba(240,180,41,0.5);">#150#</p>
    </button>
</div>

<div id="phone-section" class="hidden">
    <label class="block text-lg font-semibold mb-2" style="color:#f0b429;">Numéro Mobile Money</label>
    <div class="flex mb-4">
        <span class="px-4 py-3 rounded-l-full text-lg flex items-center border border-r-0" style="background:rgba(240,180,41,0.2);border-color:#f0b429;color:#f0b429;">🇨🇲 +237</span>
        <input type="text" id="telephone-input" placeholder="6XXXXXXXX" maxlength="9"
               class="flex-1 border rounded-r-full px-4 py-3 text-lg focus:outline-none"
               style="background:#0a1628;border-color:#f0b429;color:#f0b429;"
               oninput="this.value=this.value.replace(/[^0-9]/g,'')">
    </div>
    <button onclick="initierPaiement()"
        class="w-full py-4 rounded-full font-bold text-xl transition hover:opacity-80"
        style="background:#f0b429;color:#0a1628;">
        💸 Payer {{ number_format($commande->prix_total, 0, ',', ' ') }} FCFA
    </button>
</div>
</div>

<!-- Étape 2 - Traitement -->
<div id="step2" class="hidden text-center py-8">
    <div id="loading-spinner" class="mb-6">
        <div class="text-6xl mb-4" id="spinner-icon">📲</div>
        <div class="text-xl font-bold mb-2" style="color:#f0b429;" id="loading-text">Connexion au serveur...</div>
        <div class="w-full rounded-full h-3 mt-4" style="background:rgba(240,180,41,0.2);">
            <div id="progress-bar" class="h-3 rounded-full transition-all duration-500" style="width:0%;background:#f0b429;"></div>
        </div>
        <p class="text-sm mt-2" style="color:rgba(240,180,41,0.5);" id="progress-text">0%</p>
    </div>

    <!-- Notification push simulée -->
    <div id="push-notif" class="hidden rounded-2xl p-4 mb-4 text-left border" style="background:rgba(10,22,40,0.9);border-color:#f0b429;">
        <div class="flex items-center gap-3 mb-2">
            <span class="text-2xl" id="notif-icon">📲</span>
            <div>
                <p class="font-bold text-sm" style="color:#f0b429;" id="notif-title">MTN Mobile Money</p>
                <p class="text-xs" style="color:rgba(240,180,41,0.5);">Maintenant</p>
            </div>
        </div>
        <p class="text-sm" style="color:rgba(240,180,41,0.8);" id="notif-message"></p>
    </div>

    <!-- Demande de PIN -->
    <div id="pin-section" class="hidden">
        <p class="text-lg font-bold mb-3" style="color:#f0b429;">🔐 Entrez votre code PIN</p>
        <div class="flex justify-center gap-2 mb-4" id="pin-display">
            <div class="w-10 h-10 rounded-lg border-2 flex items-center justify-center text-xl font-bold" style="border-color:#f0b429;color:#f0b429;" id="pin1">_</div>
            <div class="w-10 h-10 rounded-lg border-2 flex items-center justify-center text-xl font-bold" style="border-color:#f0b429;color:#f0b429;" id="pin2">_</div>
            <div class="w-10 h-10 rounded-lg border-2 flex items-center justify-center text-xl font-bold" style="border-color:#f0b429;color:#f0b429;" id="pin3">_</div>
            <div class="w-10 h-10 rounded-lg border-2 flex items-center justify-center text-xl font-bold" style="border-color:#f0b429;color:#f0b429;" id="pin4">_</div>
        </div>
        <div class="grid grid-cols-3 gap-2 max-w-xs mx-auto mb-4">
            @foreach([1,2,3,4,5,6,7,8,9,'*',0,'#'] as $key)
            <button onclick="pinInput('{{ $key }}')"
                class="py-3 rounded-xl font-bold text-lg transition hover:opacity-80"
                style="background:rgba(240,180,41,0.15);color:#f0b429;border:1px solid rgba(240,180,41,0.3);">
                {{ $key }}
            </button>
            @endforeach
        </div>
        <button onclick="confirmerPin()"
            class="w-full py-3 rounded-full font-bold text-lg transition hover:opacity-80"
            style="background:#f0b429;color:#0a1628;">
            ✅ Confirmer
        </button>
    </div>
</div>

<!-- Étape 3 - Succès -->
<div id="step3" class="hidden text-center py-8">
    <div class="text-7xl mb-4">✅</div>
    <h3 class="text-2xl font-bold mb-2" style="color:#22c55e;">Paiement Réussi !</h3>
    <p class="text-lg mb-4" style="color:rgba(240,180,41,0.8);">Votre paiement a été traité avec succès</p>

    <div class="rounded-2xl p-4 mb-6 text-left border" style="background:rgba(10,22,40,0.9);border-color:#22c55e;">
        <p class="text-sm font-bold mb-1" style="color:#22c55e;">📋 Reçu de transaction</p>
        <div class="flex justify-between text-sm mb-1" style="color:rgba(240,180,41,0.8);">
            <span>Référence</span>
            <span style="color:#f0b429;" id="ref-number"></span>
        </div>
        <div class="flex justify-between text-sm mb-1" style="color:rgba(240,180,41,0.8);">
            <span>Montant débité</span>
            <span style="color:#f0b429;">{{ number_format($commande->prix_total, 0, ',', ' ') }} FCFA</span>
        </div>
        <div class="flex justify-between text-sm mb-1" style="color:rgba(240,180,41,0.8);">
            <span>Opérateur</span>
            <span style="color:#f0b429;" id="receipt-operateur"></span>
        </div>
        <div class="flex justify-between text-sm mb-1" style="color:rgba(240,180,41,0.8);">
            <span>Numéro</span>
            <span style="color:#f0b429;" id="receipt-phone"></span>
        </div>
        <div class="flex justify-between text-sm" style="color:rgba(240,180,41,0.8);">
            <span>Date</span>
            <span style="color:#f0b429;" id="receipt-date"></span>
        </div>
    </div>

    <div id="submit-form-btn">
        <button onclick="soumettreFormulaire()"
            class="w-full py-4 rounded-full font-bold text-xl transition hover:opacity-80"
            style="background:#22c55e;color:#fff;">
            🎉 Voir mes commandes
        </button>
    </div>
</div>

<!-- Étape 4 - Échec -->
<div id="step4" class="hidden text-center py-8">
    <div class="text-7xl mb-4">❌</div>
    <h3 class="text-2xl font-bold mb-2" style="color:#ef4444;">Paiement Échoué</h3>
    <p class="text-lg mb-6" style="color:rgba(240,180,41,0.8);" id="error-message">Solde insuffisant</p>
    <button onclick="recommencer()"
        class="w-full py-4 rounded-full font-bold text-xl transition hover:opacity-80"
        style="background:#f0b429;color:#0a1628;">
        🔄 Réessayer
    </button>
</div>

</div>

<div class="text-center mt-4" style="color:rgba(240,180,41,0.5);">🔒 Paiement sécurisé · MTN & Orange Money Cameroun</div>
</div>
</div>
</section>

<script>
let operateurChoisi = '';
let telephone = '';
let pinCode = '';
let pinIndex = 0;

function choixOperateur(op) {
    operateurChoisi = op;
    document.getElementById('btn-mtn').style.borderColor = op === 'mtn' ? '#f0b429' : 'rgba(240,180,41,0.3)';
    document.getElementById('btn-orange').style.borderColor = op === 'orange' ? '#f0b429' : 'rgba(240,180,41,0.3)';
    document.getElementById('phone-section').classList.remove('hidden');
}

function initierPaiement() {
    telephone = document.getElementById('telephone-input').value;
    if (telephone.length < 9) {
        alert('Veuillez entrer un numéro valide à 9 chiffres');
        return;
    }

    document.getElementById('step1').classList.add('hidden');
    document.getElementById('step2').classList.remove('hidden');

    // Simulation étape 1 - Connexion
    updateProgress(10, 'Connexion au serveur ' + (operateurChoisi === 'mtn' ? 'MTN' : 'Orange') + '...', '📡');

    setTimeout(() => {
        updateProgress(30, 'Vérification du compte...', '🔍');
    }, 1500);

    setTimeout(() => {
        updateProgress(50, 'Initialisation du paiement...', '💳');
    }, 3000);

    setTimeout(() => {
        updateProgress(70, 'Notification envoyée sur votre téléphone...', '📲');
        showPushNotif();
    }, 4500);

    setTimeout(() => {
        updateProgress(85, 'En attente de votre confirmation PIN...', '🔐');
        document.getElementById('pin-section').classList.remove('hidden');
        document.getElementById('loading-spinner').classList.add('hidden');
    }, 6000);
}

function updateProgress(percent, text, icon) {
    document.getElementById('progress-bar').style.width = percent + '%';
    document.getElementById('progress-text').textContent = percent + '%';
    document.getElementById('loading-text').textContent = text;
    document.getElementById('spinner-icon').textContent = icon;
}

function showPushNotif() {
    const notif = document.getElementById('push-notif');
    const opName = operateurChoisi === 'mtn' ? 'MTN Mobile Money' : 'Orange Money';
    const opIcon = operateurChoisi === 'mtn' ? '📡' : '🟠';
    const montant = '{{ number_format($commande->prix_total, 0, ",", " ") }} FCFA';

    document.getElementById('notif-icon').textContent = opIcon;
    document.getElementById('notif-title').textContent = opName;
    document.getElementById('notif-message').textContent =
        'Demande de paiement reçue : ' + montant + ' pour Gaz Express. Entrez votre PIN pour confirmer.';

    notif.classList.remove('hidden');
}

function pinInput(key) {
    if (key === '*') {
        pinCode = pinCode.slice(0, -1);
        pinIndex = Math.max(0, pinIndex - 1);
    } else if (key === '#') {
        confirmerPin();
        return;
    } else if (pinIndex < 4) {
        pinCode += key;
        pinIndex++;
    }

    for (let i = 1; i <= 4; i++) {
        document.getElementById('pin' + i).textContent = i <= pinIndex ? '●' : '_';
    }
}

function confirmerPin() {
    if (pinCode.length < 4) {
        alert('Veuillez entrer 4 chiffres');
        return;
    }

    document.getElementById('pin-section').classList.add('hidden');
    document.getElementById('push-notif').classList.add('hidden');
    document.getElementById('loading-spinner').classList.remove('hidden');

    updateProgress(90, 'Traitement du paiement...', '⚙️');

    setTimeout(() => {
        updateProgress(100, 'Paiement confirmé !', '✅');
    }, 1500);

    setTimeout(() => {
        // 90% de chance de succès
        const success = Math.random() > 0.1;
        if (success) {
            afficherSucces();
        } else {
            afficherEchec('Solde insuffisant. Veuillez recharger votre compte.');
        }
    }, 3000);
}

function afficherSucces() {
    document.getElementById('step2').classList.add('hidden');
    document.getElementById('step3').classList.remove('hidden');

    const ref = 'TXN' + Date.now().toString().slice(-8);
    const opName = operateurChoisi === 'mtn' ? 'MTN MoMo' : 'Orange Money';
    const now = new Date().toLocaleString('fr-FR');

    document.getElementById('ref-number').textContent = ref;
    document.getElementById('receipt-operateur').textContent = opName;
    document.getElementById('receipt-phone').textContent = '+237' + telephone;
    document.getElementById('receipt-date').textContent = now;

    document.getElementById('hidden-telephone').value = telephone;
    document.getElementById('hidden-operateur').value = operateurChoisi;
}

function afficherEchec(message) {
    document.getElementById('step2').classList.add('hidden');
    document.getElementById('step4').classList.remove('hidden');
    document.getElementById('error-message').textContent = message;
}

function recommencer() {
    document.getElementById('step4').classList.add('hidden');
    document.getElementById('step1').classList.remove('hidden');
    pinCode = '';
    pinIndex = 0;
    for (let i = 1; i <= 4; i++) {
        document.getElementById('pin' + i).textContent = '_';
    }
}

function soumettreFormulaire() {
    document.getElementById('real-form').submit();
}
</script>

@endsection