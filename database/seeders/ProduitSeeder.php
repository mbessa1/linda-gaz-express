<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Produit;

class ProduitSeeder extends Seeder
{
    public function run(): void
    {
        $produits = [
            ['marque' => 'Total Energies', 'poids' => '12.5kg', 'prix' => 6500],
            ['marque' => 'Tradex', 'poids' => '6kg', 'prix' => 3500],
            ['marque' => 'StarGas', 'poids' => '12.5kg', 'prix' => 6000],
            ['marque' => 'Neptune Oil', 'poids' => '15kg', 'prix' => 7000],
            ['marque' => 'Bocom', 'poids' => '6kg', 'prix' => 3200],
            ['marque' => 'Ola Energy', 'poids' => '12.5kg', 'prix' => 6400],
        ];

        foreach ($produits as $p) {
            Produit::create($p);
        }
    }
}
