<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
    {
        use HasFactory, Notifiable;

        protected $fillable = [
            'name', 'email', 'password', 'role', 
            'latitude', 'longitude', 'ville', 'quartier',
            'vendeur_id', 'phone', 'status'
        ];

        protected $hidden = ['password'];

        public function commandes()
        {
            return $this->hasMany(Commande::class);
        }

        public function produits()
        {
            return $this->hasMany(Produit::class, 'user_id');
        }

        public function livreurs()
        {
            return $this->hasMany(User::class, 'vendeur_id'); 
        }

        public function vendeur()
        {
            return $this->belongsTo(User::class, 'vendeur_id'); 
        }
    }


