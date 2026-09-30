<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Produit;

class ProduitSeeder extends Seeder
{
    public function run(): void
    {
        $produits = [
            ['marque' => 'Total Energies', 'poids' => '12kg', 'prix' => 6500, 'image' => '/images/TOTAL.jpg'],
            ['marque' => 'Tradex', 'poids' => '12kg', 'prix' => 6500, 'image' => '/images/TRADEX.jpg'],
            ['marque' => 'StarGas', 'poids' => '12kg', 'prix' => 6500, 'image' => '/images/gaze.jpg'],
            ['marque' => 'Neptune Oil', 'poids' => '12kg', 'prix' => 6500, 'image' => '/images/NEPTUNE.jpeg'],
            ['marque' => 'Bocom', 'poids' => '12kg', 'prix' => 6500, 'image' => '/images/BOCOM.jpg'],
            ['marque' => 'Ola Energy', 'poids' => '12kg', 'prix' => 6500, 'image' => '/images/OLAGAZ.jpg'],
        ];

        foreach ($produits as $p) {
            Produit::create($p);
        }
    }
}
