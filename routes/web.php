<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\VendeurController;
use App\Http\Controllers\LivreurController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CommandeController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ChatbotController;

// Page d'accueil
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/home', [HomeController::class, 'index']);

// Catalogue
Route::get('/catalogue', [ClientController::class, 'catalogue'])->name('catalogue');

Route::get('/apropos', function () { return view('apropos'); })->name('apropos');

// Chatbot IA ✅
Route::post('/chatbot', [ChatbotController::class, 'repondre'])->name('chatbot');

// Authentification
Route::middleware(['guest'])->group(function () {
    Route::get('/login', [AuthController::class, 'loginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.store');
    Route::get('/register', [AuthController::class, 'registerForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.store');
});

// Déconnexion
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// ----------------- CLIENT -----------------
Route::middleware(['auth', 'role:client'])->group(function () {
    Route::get('/commande', [CommandeController::class, 'create'])->name('commande.create');
    Route::post('/commande', [CommandeController::class, 'store'])->name('commande.store');
    Route::get('/mes-commandes', [CommandeController::class, 'index'])->name('client.commandes');
    Route::put('/client/profile', [ClientController::class, 'updateProfile'])->name('client.updateProfile');
    Route::post('/commandes/{commande}/update-address', [CommandeController::class, 'updateAddress'])->name('commandes.updateAddress');
    Route::get('/paiement/{commande}', [CommandeController::class, 'paiement'])->name('paiement');
    Route::post('/paiement/{commande}/confirmer', [CommandeController::class, 'confirmerPaiement'])->name('paiement.confirmer');
});

// ----------------- NOTIFICATIONS -----------------
Route::middleware(['auth'])->group(function () {
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/lue', [NotificationController::class, 'marquerLue'])->name('notifications.lue');
    Route::post('/notifications/toutes-lues', [NotificationController::class, 'marquerToutesLues'])->name('notifications.toutes-lues');
    Route::get('/notifications/compter', [NotificationController::class, 'compter'])->name('notifications.compter');
});

// ----------------- VENDEUR -----------------
Route::middleware(['auth', 'role:vendeur'])->prefix('vendeur')->name('vendeur.')->group(function () {
    Route::get('/stocks', [VendeurController::class, 'stocks'])->name('stocks');
    Route::post('/stocks', [VendeurController::class, 'updateStock'])->name('updateStock');
    Route::get('/stats', [VendeurController::class, 'stats'])->name('stats');
    Route::get('/vendeur/produit/create', [VendeurController::class, 'createProduit'])->name('createProduit');
    Route::post('/vendeur/produit/store', [VendeurController::class, 'storeProduit'])->name('storeProduit');
    Route::get('profile', [VendeurController::class, 'editProfile'])->name('editProfile');
    Route::put('profile', [VendeurController::class, 'updateProfile'])->name('updateProfile');
    Route::get('/livreurs', [VendeurController::class, 'livreurs'])->name('livreurs');
    Route::post('/livreurs', [VendeurController::class, 'createLivreur'])->name('storeLivreur');
    Route::post('/vendeur/livreurs/{id}/block', [VendeurController::class, 'blockLivreur'])->name('blockLivreur');
    Route::post('/vendeur/livreurs/{id}/unblock', [VendeurController::class, 'unblockLivreur'])->name('unblockLivreur');
});

// ----------------- LIVREUR -----------------
Route::middleware(['auth', 'role:livreur'])->prefix('livreur')->group(function () {
    Route::get('commandes', [LivreurController::class, 'index'])->name('livreur.commandes');
    Route::get('commandes/{id}', [LivreurController::class, 'show'])->name('livreur.commandes.show');
    Route::post('commandes/{id}/livree', [LivreurController::class, 'confirmer'])->name('livreur.commandes.confirmer');
    Route::get('profile', [LivreurController::class, 'editPassword'])->name('livreur.editPassword');
    Route::put('profile', [LivreurController::class, 'updatePassword'])->name('livreur.updatePassword');
    Route::get('livraison/{id}/position', [LivreurController::class, 'getClientPosition']);
});

// ----------------- ADMIN -----------------
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/users', [AdminController::class, 'index'])->name('admin.users');
    Route::post('/user/{id}/delete', [AdminController::class, 'delete'])->name('admin.deleteUser');
});
// NotchPay
use App\Http\Controllers\PaiementNotchPayController;
Route::post('/paiement/callback', [PaiementNotchPayController::class, 'callback'])->name('paiement.callback');
Route::middleware(['auth', 'role:client'])->group(function () {
    Route::post('/paiement/{commande}/notchpay', [PaiementNotchPayController::class, 'initier'])->name('paiement.initier');
    Route::get('/paiement/{commande}/succes', [PaiementNotchPayController::class, 'succes'])->name('paiement.succes');
});
