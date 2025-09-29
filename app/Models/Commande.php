<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commande extends Model {
    use HasFactory;

    protected $fillable = [
        'user_id',
        'gaz_id',
        'quantite',
        'prix_total',
        'statut',
        'latitude',
        'longitude',
        'adresse_livraison',
    ];

    public function client() {
        return $this->belongsTo( User::class, 'user_id' );
    }

    public function produit() {
        return $this->belongsTo( Produit::class, 'gaz_id' );
    }

    public function getAdresseGpsAttribute() {
        if ( $this->latitude && $this->longitude ) {
            return "{$this->latitude}, {$this->longitude}";
        }
        return null;
    }

    public function livraison() {
        return $this->hasOne( Livraison::class, 'commande_id' );
    }

}
