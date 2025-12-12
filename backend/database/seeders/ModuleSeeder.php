<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ModuleSeeder extends Seeder
{
    public function run(): void
    {
        $modules = [
            // Modules Informatique
            ['nomM' => 'Programmation C', 'code' => 'INFO101'],
            ['nomM' => 'Algorithmes et Structures de Données', 'code' => 'INFO102'],
            ['nomM' => 'Bases de Données', 'code' => 'INFO201'],
            ['nomM' => 'Programmation Orientée Objet', 'code' => 'INFO202'],
            ['nomM' => 'Développement Web', 'code' => 'INFO301'],
            ['nomM' => 'Génie Logiciel', 'code' => 'INFO302'],
            ['nomM' => 'Réseaux Informatiques', 'code' => 'INFO303'],
            ['nomM' => 'Systèmes d\'Exploitation', 'code' => 'INFO304'],
            ['nomM' => 'Intelligence Artificielle', 'code' => 'INFO401'],
            ['nomM' => 'Machine Learning', 'code' => 'INFO402'],
            ['nomM' => 'Sécurité Informatique', 'code' => 'INFO403'],
            ['nomM' => 'Cloud Computing', 'code' => 'INFO404'],
            
            // Modules Mathématiques
            ['nomM' => 'Analyse Mathématique', 'code' => 'MATH101'],
            ['nomM' => 'Algèbre Linéaire', 'code' => 'MATH102'],
            ['nomM' => 'Probabilités et Statistiques', 'code' => 'MATH201'],
            ['nomM' => 'Analyse Numérique', 'code' => 'MATH202'],
            
            // Modules Physique
            ['nomM' => 'Mécanique du Point', 'code' => 'PHYS101'],
            ['nomM' => 'Électromagnétisme', 'code' => 'PHYS102'],
            ['nomM' => 'Thermodynamique', 'code' => 'PHYS201'],
            ['nomM' => 'Optique', 'code' => 'PHYS202'],
            
            // Modules Économie
            ['nomM' => 'Microéconomie', 'code' => 'ECON101'],
            ['nomM' => 'Macroéconomie', 'code' => 'ECON102'],
            ['nomM' => 'Comptabilité Générale', 'code' => 'ECON201'],
            ['nomM' => 'Finance d\'Entreprise', 'code' => 'ECON202'],
            
            // Modules Transversaux
            ['nomM' => 'Anglais Scientifique', 'code' => 'LANG101'],
            ['nomM' => 'Communication', 'code' => 'COMM101'],
            ['nomM' => 'Techniques de Recherche', 'code' => 'RECH101'],
            ['nomM' => 'Droit de l\'Informatique', 'code' => 'DROIT101'],
        ];

        DB::table('module')->insert($modules);
    }
}