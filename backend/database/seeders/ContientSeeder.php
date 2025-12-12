<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ContientSeeder extends Seeder
{
    public function run(): void
    {
        // Filière Informatique Licence (idF=1) - Modules INFO
        $informatiqueLicence = [
            ['idF' => 1, 'idM' => 1],  // Programmation C
            ['idF' => 1, 'idM' => 2],  // Algorithmes
            ['idF' => 1, 'idM' => 3],  // Bases de Données
            ['idF' => 1, 'idM' => 4],  // POO
            ['idF' => 1, 'idM' => 5],  // Développement Web
            ['idF' => 1, 'idM' => 6],  // Génie Logiciel
            ['idF' => 1, 'idM' => 7],  // Réseaux
            ['idF' => 1, 'idM' => 8],  // Systèmes d'Exploitation
            ['idF' => 1, 'idM' => 13], // Analyse Mathématique
            ['idF' => 1, 'idM' => 14], // Algèbre Linéaire
            ['idF' => 1, 'idM' => 25], // Anglais
            ['idF' => 1, 'idM' => 26], // Communication
        ];

        // Filière Mathématiques (idF=2)
        $mathematiques = [
            ['idF' => 2, 'idM' => 13], // Analyse
            ['idF' => 2, 'idM' => 14], // Algèbre
            ['idF' => 2, 'idM' => 15], // Probabilités
            ['idF' => 2, 'idM' => 16], // Analyse Numérique
            ['idF' => 2, 'idM' => 1],  // Programmation C
            ['idF' => 2, 'idM' => 25], // Anglais
        ];

        // Filière Physique (idF=3)
        $physique = [
            ['idF' => 3, 'idM' => 17], // Mécanique
            ['idF' => 3, 'idM' => 18], // Électromagnétisme
            ['idF' => 3, 'idM' => 19], // Thermodynamique
            ['idF' => 3, 'idM' => 20], // Optique
            ['idF' => 3, 'idM' => 13], // Analyse Mathématique
            ['idF' => 3, 'idM' => 25], // Anglais
        ];

        // Filière Économie et Gestion (idF=6)
        $economie = [
            ['idF' => 6, 'idM' => 21], // Microéconomie
            ['idF' => 6, 'idM' => 22], // Macroéconomie
            ['idF' => 6, 'idM' => 23], // Comptabilité
            ['idF' => 6, 'idM' => 24], // Finance
            ['idF' => 6, 'idM' => 15], // Statistiques
            ['idF' => 6, 'idM' => 25], // Anglais
            ['idF' => 6, 'idM' => 26], // Communication
        ];

        // Master Génie Logiciel (idF=7)
        $genieLogi = [
            ['idF' => 7, 'idM' => 6],  // Génie Logiciel
            ['idF' => 7, 'idM' => 5],  // Développement Web
            ['idF' => 7, 'idM' => 9],  // IA
            ['idF' => 7, 'idM' => 3],  // BD
            ['idF' => 7, 'idM' => 27], // Techniques de Recherche
            ['idF' => 7, 'idM' => 25], // Anglais
        ];

        // Master Intelligence Artificielle (idF=9)
        $ia = [
            ['idF' => 9, 'idM' => 9],  // IA
            ['idF' => 9, 'idM' => 10], // Machine Learning
            ['idF' => 9, 'idM' => 15], // Probabilités et Stats
            ['idF' => 9, 'idM' => 16], // Analyse Numérique
            ['idF' => 9, 'idM' => 4],  // POO
            ['idF' => 9, 'idM' => 27], // Recherche
        ];

        // Master Cybersécurité (idF=10)
        $cyber = [
            ['idF' => 10, 'idM' => 11], // Sécurité Informatique
            ['idF' => 10, 'idM' => 7],  // Réseaux
            ['idF' => 10, 'idM' => 8],  // Systèmes d'Exploitation
            ['idF' => 10, 'idM' => 12], // Cloud Computing
            ['idF' => 10, 'idM' => 28], // Droit Informatique
            ['idF' => 10, 'idM' => 27], // Recherche
        ];

        $allContient = array_merge(
            $informatiqueLicence,
            $mathematiques,
            $physique,
            $economie,
            $genieLogi,
            $ia,
            $cyber
        );

        DB::table('contient')->insert($allContient);
    }
}