<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AttestationScolariteSeeder extends Seeder
{
    public function run(): void
    {
        $attestations = [
            // idDemande 2 (Fatima BENNANI - Info L2) - Validée
            [
                'idDemande' => 2,
                'nbrExemplaire' => 2,
            ],
            
            // idDemande 5 (Youssef CHAKIR - Info L1) - En attente
            [
                'idDemande' => 5,
                'nbrExemplaire' => 1,
            ],
            
            // idDemande 12 (Imane LAHLOU - Master Cyber M1) - En attente
            [
                'idDemande' => 12,
                'nbrExemplaire' => 3,
            ],
        ];

        DB::table('attestationscolarite')->insert($attestations);
    }
}