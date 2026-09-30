<!DOCTYPE html>
<html lang="fr" x-data="{ open: false, showProfile: false }" xmlns:x-data="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Gaz Express</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="//unpkg.com/alpinejs" defer></script>
    <link href="https://fonts.googleapis.com/css2?family=Fjalla+One&family=Nunito+Sans:wght@400;600&display=swap" rel="stylesheet">
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#f0b429">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-title" content="Gaz Express">
    <link rel="apple-touch-icon" href="/images/icon-192.png">
    
    <style>
        /* Base globale */
        html, body {
            background-color: #0a1628 !important;
            color: #ffffff !important;
            font-family: 'Nunito Sans', sans-serif;
            min-height: 100vh;
        }

        body {
            background-image: radial-gradient(ellipse at top, #1a3a6e 0%, #0a1628 50%), radial-gradient(ellipse at bottom, #0d2044 0%, #0a1628 50%);
            background-attachment: fixed;
        }

        /* FORCER LA SUPPRESSION DES FONDS BLANCS (TABLEAUX, CARTES, BLOCS) */
        main, section, article, div, table, thead, tbody, tr, th, td {
            background-color: transparent;
        }

        /* Remplacement automatique des classes Tailwind à fond blanc */
        .bg-white, .bg-gray-50, .bg-gray-100, .bg-slate-50 {
            background-color: #0d2044 !important;
            color: #f0b429 !important;
        }

        /* Styles spécifiques au tableau */
        table {
            background-color: #0d2044 !important;
            border-collapse: collapse;
        }
        
        tr {
            background-color: #0d2044 !important;
            border-bottom: 1px solid rgba(240, 180, 41, 0.2) !important;
        }

        th {
            background-color: #0a1628 !important;
            color: #f0b429 !important;
        }

        td {
            background-color: #0d2044 !important;
            color: #f0b429 !important;
        }

        /* Motifs et éléments graphiques */
        .bg-pattern { position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: -1; overflow: hidden; }
        .bg-pattern span { position: absolute; user-select: none; }
        h1, h2, .brand { font-family: 'Fjalla One', sans-serif; }
        [x-cloak] { display: none !important; }

        #chatbot-btn {
            position: fixed;
            bottom: 24px;
            right: 24px;
            z-index: 9999;
            width: 65px;
            height: 65px;
            border-radius: 50%;
            background: #f0b429;
            border: 3px solid #ffffff;
            box-shadow: 0 0 30px rgba(240,180,41,0.9), 0 4px 20px rgba(0,0,0,0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }
    </style>
</head>
<body class="text-white min-h-screen">

<div class="bg-pattern" id="bg-pattern"></div>

<script>
const emojis = ['🫙','🏠','🏍️','🍳','🌽','🥤','🌳','🐟','🐶','🐱','🐸','🦁','🐘','🦊','🐧','🍎','🍊','🍌','🥭','🍇','🐠','🦋','🐝','🌻','🏘️','🛵','🐄','🐔','🦆','🐰','🍋','🍓','🫐','🥝','🐑','🐐'];
const container = document.getElementById('bg-pattern');
for (let i = 0; i < 80; i++) {
    const span = document.createElement('span');
    span.textContent = emojis[Math.floor(Math.random() * emojis.length)];
    span.style.left = (Math.random() * 100) + '%';
    span.style.top = (Math.random() * 100) + '%';
    span.style.fontSize = (14 + Math.random() * 18) + 'px';
    span.style.opacity = (0.04 + Math.random() * 0.06).toString();
    span.style.transform = 'rotate(' + (Math.random() * 360) + 'deg)';
    container.appendChild(span);
}
</script>

<!-- NAVBAR -->
<nav style="background:linear-gradient(135deg,#0d2044 0%,#1a3a6e 100%);border-bottom:2px solid #f0b429;" class="shadow-lg sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
        @auth
        <a href="@switch(Auth::user()->role) @case('client'){{ route('catalogue') }}@break @case('vendeur'){{ route('vendeur.stats') }}@break @case('livreur'){{ route('livreur.commandes') }}@break @case('admin'){{ route('admin.users') }}@break @endswitch"
           class="brand text-3xl hover:opacity-80 transition flex items-center gap-2" style="color:#f0b429;">
            <span style="font-size:32px;">🫙</span>
            Gaz Express
        </a>
        @else
        <a href="{{ route('home') }}" class="brand text-3xl hover:opacity-80 transition flex items-center gap-2" style="color:#f0b429;">
            <span style="font-size:32px;">🫙</span>
            Gaz Express
        </a>
        @endauth

        <div class="hidden md:flex space-x-6 items-center">
            @guest
                <a href="{{ route('login') }}" class="hover:text-white transition" style="color:#f0b429;">Connexion</a>
                <a href="{{ route('register') }}" class="px-4 py-2 rounded-xl font-bold" style="background:#f0b429;color:#0a1628;">Inscription</a>
            @else
                @if(Auth::user()->role === 'client')
                    <a href="{{ route('catalogue') }}" class="hover:text-white" style="color:#f0b429;">Catalogue</a>
                    <a href="{{ route('client.commandes') }}" class="hover:text-white" style="color:#f0b429;">Mes Commandes</a>
                    <a href="{{ route('apropos') }}" class="hover:text-white" style="color:#f0b429;">À propos</a>
                @elseif(Auth::user()->role === 'vendeur')
                    <a href="{{ route('vendeur.stocks') }}" class="hover:text-white" style="color:#f0b429;">Stocks</a>
                    <a href="{{ route('vendeur.stats') }}" class="hover:text-white" style="color:#f0b429;">Stats</a>
                    <a href="{{ route('vendeur.createProduit') }}" class="hover:text-white" style="color:#f0b429;">Ajouter un produit</a>
                    <a href="{{ route('vendeur.livreurs') }}" class="hover:text-white" style="color:#f0b429;">Gérer livreurs</a>
                @elseif(Auth::user()->role === 'livreur')
                    <a href="{{ route('livreur.commandes') }}" class="hover:text-white" style="color:#f0b429;">Commandes</a>
                @elseif(Auth::user()->role === 'admin')
                    <a href="{{ route('admin.users') }}" class="hover:text-white" style="color:#f0b429;">Utilisateurs</a>
                @endif

                <!-- Notifications -->
                <div class="relative" id="notif-container">
                    <button onclick="toggleNotifications()" class="relative p-2 rounded-full" style="background:rgba(240,180,41,0.15);">
                        <span class="text-2xl">🔔</span>
                        <span id="notif-badge" class="hidden absolute -top-1 -right-1 text-xs font-bold rounded-full w-5 h-5 flex items-center justify-center" style="background:#f0b429;color:#0a1628;">0</span>
                    </button>
                    <div id="notif-panel" class="hidden absolute right-0 mt-2 w-80 rounded-2xl shadow-2xl z-50 overflow-hidden border" style="background:#0d2044;border-color:#f0b429;">
                        <div class="px-4 py-3 flex justify-between items-center" style="background:#f0b429;">
                            <span class="font-bold" style="color:#0a1628;">🔔 Notifications</span>
                            <button onclick="marquerToutesLues()" class="text-xs px-2 py-1 rounded-full font-bold" style="background:#0a1628;color:#f0b429;">Tout lire</button>
                        </div>
                        <div id="notif-list" class="max-h-80 overflow-y-auto">
                            <p class="text-center py-4 text-sm" style="color:#f0b429;opacity:0.6;">Chargement...</p>
                        </div>
                    </div>
                </div>

                <!-- Avatar -->
                <div class="relative" x-data="{ openProfile: false }">
                    <button @click="openProfile = !openProfile" class="w-10 h-10 rounded-full flex items-center justify-center font-bold" style="background:#f0b429;color:#0a1628;">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </button>
                    <div x-show="openProfile" x-cloak @click.away="openProfile = false"
                         class="absolute right-0 mt-2 w-48 rounded-lg shadow-lg py-2 z-50 border" style="background:#0d2044;border-color:#f0b429;">
                        <div class="px-4 py-2 text-sm border-b" style="color:#f0b429;border-color:rgba(240,180,41,0.3);">{{ Auth::user()->name }}</div>
                        @if(Auth::user()->role === 'client')
                            <button @click="showProfile = true" class="block w-full text-left px-4 py-2 text-sm hover:opacity-80" style="color:#f0b429;">Modifier Profil</button>
                        @elseif(Auth::user()->role === 'vendeur')
                            <a href="{{ route('vendeur.editProfile') }}" class="block px-4 py-2 text-sm hover:opacity-80" style="color:#f0b429;">Modifier Profil</a>
                        @elseif(Auth::user()->role === 'livreur')
                            <a href="{{ route('livreur.editPassword') }}" class="block px-4 py-2 text-sm hover:opacity-80" style="color:#f0b429;">Modifier Mot de Passe</a>
                        @endif
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="block w-full text-left px-4 py-2 text-sm" style="color:#ef4444;">Déconnexion</button>
                        </form>
                    </div>
                </div>
            @endguest
        </div>

        <!-- Mobile -->
        <div class="flex items-center gap-3 md:hidden">
            @auth
            <div class="relative" id="notif-container-mobile">
                <button onclick="toggleNotifications()" class="relative p-1">
                    <span class="text-2xl">🔔</span>
                    <span id="notif-badge-mobile" class="hidden absolute -top-1 -right-1 text-xs font-bold rounded-full w-4 h-4 flex items-center justify-center" style="background:#f0b429;color:#0a1628;">0</span>
                </button>
            </div>
            @endauth
            <button @click="open = !open" class="focus:outline-none" style="color:#f0b429;">
                <svg xmlns="http://www.w3.org/2000/svg" :class="{'hidden': open, 'block': !open}" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
                <svg xmlns="http://www.w3.org/2000/svg" :class="{'block': open, 'hidden': !open}" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div x-show="open" x-cloak class="md:hidden px-6 py-4 space-y-3" style="background:#0d2044;">
        @guest
            <a href="{{ route('login') }}" class="block hover:opacity-80" style="color:#f0b429;">Connexion</a>
            <a href="{{ route('register') }}" class="block px-4 py-2 rounded-xl font-bold text-center" style="background:#f0b429;color:#0a1628;">Inscription</a>
        @else
            @if(Auth::user()->role === 'client')
                <a href="{{ route('catalogue') }}" class="block hover:opacity-80" style="color:#f0b429;">Catalogue</a>
                <a href="{{ route('client.commandes') }}" class="block hover:opacity-80" style="color:#f0b429;">Mes Commandes</a>
                <a href="{{ route('apropos') }}" class="block hover:opacity-80" style="color:#f0b429;">À propos</a>
            @elseif(Auth::user()->role === 'vendeur')
                <a href="{{ route('vendeur.stocks') }}" class="block hover:opacity-80" style="color:#f0b429;">Stocks</a>
                <a href="{{ route('vendeur.stats') }}" class="block hover:opacity-80" style="color:#f0b429;">Stats</a>
                <a href="{{ route('vendeur.createProduit') }}" class="block hover:opacity-80" style="color:#f0b429;">Ajouter</a>
                <a href="{{ route('vendeur.livreurs') }}" class="block hover:opacity-80" style="color:#f0b429;">Livreurs</a>
            @elseif(Auth::user()->role === 'livreur')
                <a href="{{ route('livreur.commandes') }}" class="block hover:opacity-80" style="color:#f0b429;">Commandes</a>
            @elseif(Auth::user()->role === 'admin')
                <a href="{{ route('admin.users') }}" class="block hover:opacity-80" style="color:#f0b429;">Utilisateurs</a>
            @endif
            <div class="relative" x-data="{ openProfileMobile: false }">
                <button @click="openProfileMobile = !openProfileMobile" class="w-10 h-10 rounded-full flex items-center justify-center font-bold" style="background:#f0b429;color:#0a1628;">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </button>
                <div x-show="openProfileMobile" x-cloak @click.away="openProfileMobile = false" class="mt-2 w-48 rounded-lg shadow-lg py-2 z-50 border" style="background:#0d2044;border-color:#f0b429;">
                    <div class="px-4 py-2 text-sm border-b" style="color:#f0b429;">{{ Auth::user()->name }}</div>
                    @if(Auth::user()->role === 'client')
                        <button @click="showProfile = true; openProfileMobile = false" class="block w-full text-left px-4 py-2 text-sm" style="color:#f0b429;">Modifier Profil</button>
                    @endif
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="block w-full text-left px-4 py-2 text-sm" style="color:#ef4444;">Déconnexion</button>
                    </form>
                </div>
            </div>
        @endguest
    </div>
</nav>

<main class="max-w-7xl mx-auto px-6 py-10">
    @yield('content')
</main>

<footer class="text-center py-6 mt-10" style="background:linear-gradient(135deg,#0d2044 0%,#1a3a6e 100%);border-top:2px solid #f0b429;">
    <p style="color:#f0b429;">© {{ date('Y') }} Gaz Express – Énergie Verte & Livraison Rapide ⚡</p>
</footer>

@auth
<div x-show="showProfile" x-cloak class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-70 z-50">
    <div class="rounded-xl shadow-lg p-6 w-96 border" style="background:#0d2044;border-color:#f0b429;">
        <h2 class="text-xl font-bold mb-4" style="color:#f0b429;">Mon Profil</h2>
        <form action="{{ route('client.updateProfile') }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label class="block mb-1" style="color:#f0b429;">Nom</label>
                <input type="text" name="name" value="{{ Auth::user()->name }}" class="w-full border rounded-lg p-2" style="background:#0a1628;border-color:#f0b429;color:#fff;">
            </div>
            <div class="mb-4">
                <label class="block mb-1" style="color:#f0b429;">Email</label>
                <input type="email" name="email" value="{{ Auth::user()->email }}" class="w-full border rounded-lg p-2" style="background:#0a1628;border-color:#f0b429;color:#fff;">
            </div>
            <div class="flex justify-end space-x-2">
                <button type="button" @click="showProfile = false" class="px-4 py-2 rounded-lg" style="background:#1a3a6e;color:#f0b429;">Annuler</button>
                <button type="submit" class="px-4 py-2 rounded-lg font-bold" style="background:#f0b429;color:#0a1628;">Enregistrer</button>
            </div>
        </form>
    </div>
</div>
@endauth

<!-- CHATBOT -->
<div id="chat-window" class="hidden flex-col w-80 rounded-2xl shadow-2xl overflow-hidden border"
     style="position:fixed;bottom:100px;right:24px;z-index:9999;background:#0d2044;border-color:#f0b429;box-shadow:0 0 30px rgba(240,180,41,0.5);">
    <div class="px-4 py-3 flex items-center gap-2" style="background:#f0b429;">
        <span style="font-size:24px;">🤖</span>
        <div>
            <p class="font-bold text-sm" style="color:#0a1628;">Assistant Gaz Express</p>
            <p class="text-xs" style="color:#0a1628;opacity:0.7;">En ligne</p>
        </div>
        <button onclick="toggleChat()" class="ml-auto text-lg font-bold" style="color:#0a1628;">✕</button>
    </div>
    <div id="chat-messages" class="p-4 space-y-3 overflow-y-auto" style="height:300px;background:#0a1628;">
        <div class="flex gap-2">
            <span style="font-size:20px;">🤖</span>
            <div class="rounded-xl px-3 py-2 text-sm max-w-xs border" style="background:#0d2044;color:#f0b429;border-color:rgba(240,180,41,0.3);">
                Bonjour ! Je suis votre assistant Gaz Express. Comment puis-je vous aider ? 😊
            </div>
        </div>
    </div>
    <div class="px-4 py-2 flex flex-wrap gap-2 border-t" style="background:#0d2044;border-color:rgba(240,180,41,0.3);">
        <button onclick="sendMessage('Commander')" class="text-xs px-3 py-1 rounded-full hover:opacity-80" style="background:rgba(240,180,41,0.15);color:#f0b429;">Commander</button>
        <button onclick="sendMessage('Prix')" class="text-xs px-3 py-1 rounded-full hover:opacity-80" style="background:rgba(240,180,41,0.15);color:#f0b429;">Prix</button>
        <button onclick="sendMessage('Livraison')" class="text-xs px-3 py-1 rounded-full hover:opacity-80" style="background:rgba(240,180,41,0.15);color:#f0b429;">Livraison</button>
        <button onclick="sendMessage('Contact')" class="text-xs px-3 py-1 rounded-full hover:opacity-80" style="background:rgba(240,180,41,0.15);color:#f0b429;">Contact</button>
    </div>
    <div class="px-4 py-3 border-t flex gap-2" style="background:#0d2044;border-color:rgba(240,180,41,0.3);">
        <input type="text" id="chat-input" placeholder="Posez votre question..."
               class="flex-1 rounded-full px-3 py-2 text-sm focus:outline-none border"
               style="background:#0a1628;color:#f0b429;border-color:#f0b429;"
               onkeypress="if(event.key==='Enter') sendMessage()">
        <button onclick="sendMessage()" class="rounded-full w-9 h-9 flex items-center justify-center hover:opacity-80 font-bold" style="background:#f0b429;color:#0a1628;">➤</button>
    </div>
</div>

<!-- BOUTON CHATBOT -->
<div id="chatbot-btn" onclick="toggleChat()">
    <span style="font-size:35px;">🤖</span>
</div>

<script>
if ('serviceWorker' in navigator) {
    window.addEventListener('load', function() {
        navigator.serviceWorker.register('/sw.js').catch(() => {});
    });
}
</script>

<script>
function updateBadge(count) {
    ['notif-badge', 'notif-badge-mobile'].forEach(id => {
        const badge = document.getElementById(id);
        if (badge) {
            if (count > 0) { badge.textContent = count; badge.classList.remove('hidden'); }
            else { badge.classList.add('hidden'); }
        }
    });
}

function checkNotifications() {
    fetch('/notifications/compter').then(r => r.json()).then(data => updateBadge(data.count)).catch(() => {});
}

function toggleNotifications() {
    const panel = document.getElementById('notif-panel');
    if (panel) {
        panel.classList.toggle('hidden');
        if (!panel.classList.contains('hidden')) loadNotifications();
    }
}

function loadNotifications() {
    fetch('/notifications').then(r => r.json()).then(notifications => {
        const list = document.getElementById('notif-list');
        if (!list) return;
        if (notifications.length === 0) {
            list.innerHTML = '<p class="text-center py-4 text-sm" style="color:#f0b429;opacity:0.6;">Aucune notification</p>';
            return;
        }
        list.innerHTML = notifications.map(n => `
            <div class="px-4 py-3 border-b cursor-pointer hover:opacity-80 ${n.lu ? 'opacity-50' : ''}"
                 style="border-color:rgba(240,180,41,0.2);" onclick="marquerLue(${n.id}, this)">
                <p class="font-semibold text-sm" style="color:#f0b429;">${n.titre}</p>
                <p class="text-xs mt-1" style="color:#f0b429;opacity:0.7;">${n.message}</p>
                <p class="text-xs mt-1" style="color:#f0b429;opacity:0.4;">${new Date(n.created_at).toLocaleString('fr-FR')}</p>
            </div>
        `).join('');
    }).catch(() => {});
}

function marquerLue(id, element) {
    const token = document.querySelector('meta[name="csrf-token"]');
    fetch(`/notifications/${id}/lue`, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': token ? token.content : '', 'Content-Type': 'application/json' }
    }).then(() => { element.classList.add('opacity-50'); checkNotifications(); });
}

function marquerToutesLues() {
    const token = document.querySelector('meta[name="csrf-token"]');
    fetch('/notifications/toutes-lues', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': token ? token.content : '', 'Content-Type': 'application/json' }
    }).then(() => { checkNotifications(); loadNotifications(); });
}

checkNotifications();
setInterval(checkNotifications, 10000);

document.addEventListener('click', function(e) {
    ['notif-container', 'notif-container-mobile'].forEach(id => {
        const container = document.getElementById(id);
        const panel = document.getElementById('notif-panel');
        if (container && panel && !container.contains(e.target)) panel.classList.add('hidden');
    });
});

function toggleChat() {
    const win = document.getElementById('chat-window');
    win.classList.toggle('hidden');
    win.classList.toggle('flex');
}

function sendMessage(text) {
    const input = document.getElementById('chat-input');
    const message = text || input.value.trim();
    if (!message) return;
    addMessage(message, 'user');
    input.value = '';

    const container = document.getElementById('chat-messages');
    const loading = document.createElement('div');
    loading.id = 'loading-msg';
    loading.className = 'flex gap-2';
    loading.innerHTML = '<span style="font-size:20px;">🤖</span><div class="rounded-xl px-3 py-2 text-sm max-w-xs border animate-pulse" style="background:#0d2044;color:#f0b429;border-color:rgba(240,180,41,0.3);">En train de répondre...</div>';
    container.appendChild(loading);
    container.scrollTop = container.scrollHeight;

    const token = document.querySelector('meta[name="csrf-token"]');
    fetch('/chatbot', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': token ? token.content : '', 'Content-Type': 'application/json' },
        body: JSON.stringify({ message: message })
    })
    .then(r => r.json())
    .then(data => {
        const loadingEl = document.getElementById('loading-msg');
        if (loadingEl) loadingEl.remove();
        addMessage(data.reponse, 'bot');
    })
    .catch(() => {
        const loadingEl = document.getElementById('loading-msg');
        if (loadingEl) loadingEl.remove();
        addMessage("Je suis disponible ! Posez votre question.", 'bot');
    });
}

function addMessage(text, sender) {
    const container = document.getElementById('chat-messages');
    const div = document.createElement('div');
    div.className = 'flex gap-2 ' + (sender === 'user' ? 'justify-end' : '');
    div.innerHTML = sender === 'user'
        ? `<div class="rounded-xl px-3 py-2 text-sm max-w-xs font-bold" style="background:#f0b429;color:#0a1628;">${text}</div>`
        : `<span style="font-size:20px;">🤖</span><div class="rounded-xl px-3 py-2 text-sm max-w-xs border" style="background:#0d2044;color:#f0b429;border-color:rgba(240,180,41,0.3);">${text}</div>`;
    container.appendChild(div);
    container.scrollTop = container.scrollHeight;
}
</script>

</body>
</html>