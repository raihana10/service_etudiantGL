<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AttestationReussiteSeeder extends Seeder
{
    public function run(): void
    {
        $attestations = [
            // idDemande 3 (Salma DARIF - Math L3) - Validée
            [
                'idDemande' => 3,
                'anneeObtention' => '2023',
                'diplomeConcernee' => 'Licence en Mathématiques',
            ],
            
            // idDemande 8 (Sofia JAAFARI - Master IA M1) - En attente
            [
                'idDemande' => 8,
                'anneeObtention' => '2024',
                'diplomeConcernee' => 'Licence en Informatique',
            ],
            
            // idDemande 9 (Amina FASSI - Économie L3) - Refusée
            [
                'idDemande' => 9,
                'anneeObtention' => '2023',
                'diplomeConcernee' => 'Licence en Économie et Gestion',
            ],
        ];

        DB::table('attestationreussite')->insert($attestations);
    }
}