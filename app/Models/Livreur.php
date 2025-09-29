<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Livreur extends Authenticatable {
    protected $fillable = [
        'name', 'email', 'password', 'phone', 'status'
    ];

    protected $hidden = [
        'password'
    ];
}
