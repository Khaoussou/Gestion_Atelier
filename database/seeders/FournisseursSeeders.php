<?php

namespace Database\Seeders;

use App\Models\Fournisseur;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FournisseursSeeders extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $fournisseurs = [
            ["nom_complet" => "Mamadou Diallo"],
            ["nom_complet" => "Bassirou Séye"],
            ["nom_complet" => "Nabou Dash"],
            ["nom_complet" => "Elzo Ndao"],
            ["nom_complet" => "Khadija Ndiaye"],
        ];

        Fournisseur::insert($fournisseurs);
    }
}
