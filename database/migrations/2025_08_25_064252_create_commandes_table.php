<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('commandes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Client
            $table->foreignId('gaz_id')->constrained('produits')->onDelete('cascade'); // Type de gaz
            $table->unsignedinteger('quantite'); // nombre de bouteilles
            $table->decimal('prix_total', 10, 2);
            $table->enum('statut', ['en_attente', 'en_livraison', 'livree', 'annulee'])->default('en_attente');
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->string('adresse_livraison');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('commandes');
    }
};
