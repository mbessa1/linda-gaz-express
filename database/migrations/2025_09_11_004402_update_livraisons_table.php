<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('livraisons', function (Blueprint $table) {
            // Ajouter les colonnes si elles n'existent pas
            if (!Schema::hasColumn('livraisons', 'commande_id')) {
                $table->unsignedBigInteger('commande_id');
            }
            if (!Schema::hasColumn('livraisons', 'livreur_id')) {
                $table->unsignedBigInteger('livreur_id');
            }
            if (!Schema::hasColumn('livraisons', 'etat')) {
                $table->enum('etat', ['en_cours', 'effectuee'])->default('en_cours');
            }
            if (!Schema::hasColumn('livraisons', 'localisation')) {
                $table->string('localisation')->nullable();
            }

            // Ajouter les clés étrangères
            $table->foreign('commande_id')->references('id')->on('commandes')->onDelete('cascade');
            $table->foreign('livreur_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('livraisons', function (Blueprint $table) {
            $table->dropForeign(['commande_id']);
            $table->dropForeign(['livreur_id']);
            $table->dropColumn(['commande_id', 'livreur_id', 'etat', 'localisation']);
        });
    }
};
