<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ConventionStageSeeder extends Seeder
{
    public function run(): void
    {
        $conventions = [
            // idDemande 4 (Karim GHAZI - Master GL M2) - Validée
            [
                'idDemande' => 4,
                'representantEntreprise' => 'Ahmed BENKIRANE',
                'villeEntreprise' => 'Casablanca',
                'encadrantAcademique' => 'Prof. Khalid ALAOUI',
                'encadrantEntreprise' => 'Rachid TAZI',
                'fctEncadrant' => 'Responsable Technique',
                'TLEncadrant' => '+212661234567',
                'emailEncadrant' => 'r.tazi@softtech.ma',
                'emailEntreprise' => 'contact@softtech.ma',
                'TLEntreprise' => '+212522334455',
                'fctRepresentant' => 'Directeur Général',
                'raisonSocialeEntreprise' => 'SoftTech Solutions SARL',
                'secteurEntreprise' => 'Technologies de l\'Information',
                'adresseEntreprise' => '45 Boulevard Mohammed V, Casablanca 20000',
                'typeStage' => 'Stage PFE',
                'dateDebut' => Carbon::create(2024, 2, 1),
                'dateFin' => Carbon::create(2024, 6, 30),
                'sujetStage' => 'Développement d\'une plateforme de gestion de projets avec intégration IA',
            ],
            
            // idDemande 7 (Mehdi IDRISSI - Master IA M2) - En attente
            [
                'idDemande' => 7,
                'representantEntreprise' => 'Fatima LAZRAK',
                'villeEntreprise' => 'Rabat',
                'encadrantAcademique' => 'Prof. Said BENJELLOUN',
                'encadrantEntreprise' => 'Omar CHRAIBI',
                'fctEncadrant' => 'Lead Data Scientist',
                'TLEncadrant' => '+212662345678',
                'emailEncadrant' => 'o.chraibi@datalab.ma',
                'emailEntreprise' => 'rh@datalab.ma',
                'TLEntreprise' => '+212537445566',
                'fctRepresentant' => 'Directrice des Ressources Humaines',
                'raisonSocialeEntreprise' => 'DataLab Morocco SA',
                'secteurEntreprise' => 'Intelligence Artificielle et Big Data',
                'adresseEntreprise' => '12 Avenue Hassan II, Agdal, Rabat 10090',
                'typeStage' => 'Stage PFE',
                'dateDebut' => Carbon::create(2024, 3, 1),
                'dateFin' => Carbon::create(2024, 7, 31),
                'sujetStage' => 'Développement d\'un système de recommandation basé sur le Deep Learning',
            ],
            
            // idDemande 10 (Hamza KHALIL - Master Cyber M2) - Refusée
            [
                'idDemande' => 10,
                'representantEntreprise' => 'Youssef TOUNSI',
                'villeEntreprise' => 'Casablanca',
                'encadrantAcademique' => 'Prof. Nadia FARIS',
                'encadrantEntreprise' => 'Karim SENHAJI',
                'fctEncadrant' => 'Chef de Projet Sécurité',
                'TLEncadrant' => '',  // Information manquante - raison du refus
                'emailEncadrant' => '',  // Information manquante
                'emailEntreprise' => 'contact@cyberguard.ma',
                'TLEntreprise' => '+212522778899',
                'fctRepresentant' => 'Directeur Technique',
                'raisonSocialeEntreprise' => 'CyberGuard Maroc',
                'secteurEntreprise' => 'Cybersécurité',
                'adresseEntreprise' => '78 Rue Prince Moulay Abdellah, Casablanca',
                'typeStage' => 'Stage PFE',
                'dateDebut' => Carbon::create(2024, 2, 15),
                'dateFin' => Carbon::create(2024, 7, 15),
                'sujetStage' => 'Audit de sécurité et mise en place d\'un SOC pour PME',
            ],
        ];

        DB::table('conventionstage')->insert($conventions);
    }
}