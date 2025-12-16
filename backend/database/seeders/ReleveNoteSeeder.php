<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReleveNoteSeeder extends Seeder
{
    public function run(): void
    {
        $releves = [
            // idDemande 1 (Mohammed ALAMI - Info L3) - Validée
            [
                'idDemande' => 1,
                'annee' => '2022-2023',
            ],
            
            // idDemande 6 (Omar ELIDRISSI - Math L2) - En attente
            [
                'idDemande' => 6,
                'annee' => '2023-2024',
            ],
            
            // idDemande 11 (Nadia HAJJI - Master GL M1) - Validée
            [
                'idDemande' => 11,
                'annee' => '2023-2024',
            ],
        ];

        DB::table('relevenote')->insert($releves);
    }
}