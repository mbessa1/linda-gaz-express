<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produit;

class HomeController extends Controller
{
    public function index()
{
    $produits = Produit::take(8)->get(); // afficher 4 produits
    return view('home', compact('produits'));
}
}
