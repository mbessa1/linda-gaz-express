<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produit extends Model {
    protected $fillable = [ 'marque', 'poids', 'prix', 'user_id', 'image' ];

    public function stock() {
        return $this->hasOne( Stock::class );
    }

    public function commandes() {
        return $this->hasMany( Commande::class, 'gaz_id' );
    }

    public function user() {
        return $this->belongsTo( User::class, 'user_id' );
        // produit appartient à un utilisateur ( vendeur )
    }

    public function vendeur() {
        return $this->belongsTo( User::class, 'user_id' );
        // user_id = vendeur du produit
    }

}
