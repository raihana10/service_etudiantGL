<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ConcerneSeeder extends Seeder
{
    public function run(): void
    {
        $notes = [];

        // Modules Info L3 (1 à 25)
        $modulesInfo = range(1, 25);

        // Étudiant 1 (Mohammed ALAMI - Info L3)
        foreach ($modulesInfo as $idContient) {
            $notes[] = [
                'idContient' => $idContient,
                'idEtudiant' => 1,
                'annee' => '2022-2023',
                'note' => rand(12, 18) + (rand(0, 99) / 100),
            ];
        }

        // Étudiant 2 (Info L2) — seulement 8 premiers modules
        foreach (array_slice($modulesInfo, 0, 8) as $idContient) {
            $notes[] = [
                'idContient' => $idContient,
                'idEtudiant' => 2,
                'annee' => '2023-2024',
                'note' => rand(11, 17) + (rand(0, 99) / 100),
            ];
        }

        // Étudiant 3 (Info L1) — seulement 4 premiers modules
        foreach (array_slice($modulesInfo, 0, 4) as $idContient) {
            $notes[] = [
                'idContient' => $idContient,
                'idEtudiant' => 3,
                'annee' => '2024-2025',
                'note' => rand(10, 16) + (rand(0, 99) / 100),
            ];
        }

        // Étudiant 4 (Math L3) — modules 13 à 18
        $modulesMath = [13, 14, 15, 16, 17, 18];
        foreach ($modulesMath as $idContient) {
            $notes[] = [
                'idContient' => $idContient,
                'idEtudiant' => 4,
                'annee' => '2022-2023',
                'note' => rand(13, 19) + (rand(0, 99) / 100),
            ];
        }

        // Étudiant 5 (Math L2) — modules 13 à 16
        foreach (array_slice($modulesMath, 0, 4) as $idContient) {
            $notes[] = [
                'idContient' => $idContient,
                'idEtudiant' => 5,
                'annee' => '2023-2024',
                'note' => rand(12, 18) + (rand(0, 99) / 100),
            ];
        }

        // Étudiant 13 (Physique L2) — modules 19 à 22
        $modulesPhys = [19, 20, 21, 22, 23, 24];
        foreach (array_slice($modulesPhys, 0, 4) as $idContient) {
            $notes[] = [
                'idContient' => $idContient,
                'idEtudiant' => 13,
                'annee' => '2023-2024',
                'note' => rand(11, 16) + (rand(0, 99) / 100),
            ];
        }

        DB::table('concerne')->insert($notes);
    }
}
