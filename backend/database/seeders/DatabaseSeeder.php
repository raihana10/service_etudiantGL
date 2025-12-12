<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            AdministrateurSeeder::class,
            FiliereSeeder::class,
            ModuleSeeder::class,
            ContientSeeder::class,
            EtudiantSeeder::class,
            ConcerneSeeder::class,
            DemandeSeeder::class,
            AttestationReussiteSeeder::class,
            AttestationScolariteSeeder::class,
            ReleveNoteSeeder::class,
            ConventionStageSeeder::class,
            ReclamationSeeder::class,
        ]);
    }
}