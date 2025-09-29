<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\VendeurController;
use App\Http\Controllers\LivreurController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CommandeController;
use App\Http\Controllers\HomeController;

// Page d'accueil
Route::get('/', [HomeController::class, 'index'])->name('home');
//Catalogue
Route::get('/catalogue', [ClientController::class, 'catalogue'])->name('catalogue');

Route::get('/apropos', function () { return view('apropos'); })->name('apropos');

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
    Route::post('/commandes/{commande}/update-address', [CommandeController::class, 'updateAddress'])->name('commandes.updateAddress')->middleware('auth');

});

// ----------------- VENDEUR -----------------
Route::middleware(['auth', 'role:vendeur'])->prefix('vendeur')->name('vendeur.')->group(function () {
    Route::get('/stocks', [VendeurController::class, 'stocks'])->name('stocks');
    Route::post('/stocks', [VendeurController::class, 'updateStock'])->name('updateStock');
    Route::get('/stats', [VendeurController::class, 'stats'])->name('stats');
    Route::get('/vendeur/produit/create', [VendeurController::class, 'createProduit'])->name('createProduit');
    Route::post('/vendeur/produit/store', [VendeurController::class, 'storeProduit'])->name('storeProduit');
    Route::get('profile', [VendeurController::class, 'editProfile'])->name('editProfile'); // page edit
    Route::put('profile', [VendeurController::class, 'updateProfile'])->name('updateProfile'); // action update
    Route::get('/livreurs', [VendeurController::class, 'livreurs'])->name('livreurs'); // page liste + formulaire
    Route::post('/livreurs', [VendeurController::class, 'createLivreur'])->name('storeLivreur'); // création
    Route::post('/vendeur/livreurs/{id}/block', [VendeurController::class, 'blockLivreur'])->name('blockLivreur');
    Route::post('/vendeur/livreurs/{id}/unblock', [VendeurController::class, 'unblockLivreur'])->name('unblockLivreur');


});

// ----------------- LIVREUR -----------------
Route::middleware(['auth', 'role:livreur'])->prefix('livreur')->group(function() {
    Route::get('commandes', [\App\Http\Controllers\LivreurController::class, 'index'])->name('livreur.commandes');
    Route::get('commandes/{id}', [\App\Http\Controllers\LivreurController::class, 'show'])->name('livreur.commandes.show');
    Route::post('commandes/{id}/livree', [\App\Http\Controllers\LivreurController::class, 'confirmer'])->name('livreur.commandes.confirmer');
    Route::get('profile', [LivreurController::class, 'editPassword'])->name('livreur.editPassword');
    Route::put('profile', [LivreurController::class, 'updatePassword'])->name('livreur.updatePassword');
});


// ----------------- ADMIN -----------------
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/users', [AdminController::class, 'index'])->name('admin.users');
    Route::post('/user/{id}/delete', [AdminController::class, 'delete'])->name('admin.deleteUser');
});


Route::get('/send-test-sms', function() {
    $twilio = new \Twilio\Rest\Client(env('TWILIO_SID'), env('TWILIO_AUTH_TOKEN'));
    $message = $twilio->messages->create(
        '+2376XXXXXXX', // ton numéro de test
        [
            'from' => env('TWILIO_PHONE_FROM'),
            'body' => 'Message de test'
        ]
    );
    return $message->sid;
});


Route::get('/test-twilio-env', function() {
    dd(
        env('TWILIO_SID'),
        env('TWILIO_AUTH_TOKEN'),
        env('TWILIO_PHONE_FROM'),
        env('TWILIO_WHATSAPP_FROM')
    );
});

Route::get('/livreur/livraison/{id}/position', [\App\Http\Controllers\LivreurController::class, 'getClientPosition'])
    ->middleware(['auth', 'role:livreur']);

