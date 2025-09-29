<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Livraison extends Model {
    protected $fillable = [ 'commande_id', 'livreur_id', 'etat', 'localisation' ];

    public function commande() {
        return $this->belongsTo( Commande::class, 'commande_id' );
    }

    public function livreur() {
        return $this->belongsTo( User::class, 'livreur_id' );
    }

}

