<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DemandeSeeder extends Seeder
{
    public function run(): void
    {
        $demandes = [
            // Demandes Validées
            [
                'idEtudiant' => 1,
                'idAdmin' => 1,
                'datesoumission' => Carbon::now()->subDays(30),
                'typeDoc' => 'ReleveNote',
                'statut' => 'Validée',
                'motif_refus' => null,
                'date_traitement' => Carbon::now()->subDays(28),
            ],
            [
                'idEtudiant' => 2,
                'idAdmin' => 2,
                'datesoumission' => Carbon::now()->subDays(25),
                'typeDoc' => 'AttestationScolarite',
                'statut' => 'Validée',
                'motif_refus' => null,
                'date_traitement' => Carbon::now()->subDays(23),
            ],
            [
                'idEtudiant' => 4,
                'idAdmin' => 1,
                'datesoumission' => Carbon::now()->subDays(20),
                'typeDoc' => 'AttestationReussite',
                'statut' => 'Validée',
                'motif_refus' => null,
                'date_traitement' => Carbon::now()->subDays(18),
            ],
            [
                'idEtudiant' => 7,
                'idAdmin' => 3,
                'datesoumission' => Carbon::now()->subDays(15),
                'typeDoc' => 'ConventionStage',
                'statut' => 'Validée',
                'motif_refus' => null,
                'date_traitement' => Carbon::now()->subDays(12),
            ],
            
            // Demandes En attente
            [
                'idEtudiant' => 3,
                'idAdmin' => null,
                'datesoumission' => Carbon::now()->subDays(5),
                'typeDoc' => 'AttestationScolarite',
                'statut' => 'En attente',
                'motif_refus' => null,
                'date_traitement' => null,
            ],
            [
                'idEtudiant' => 5,
                'idAdmin' => null,
                'datesoumission' => Carbon::now()->subDays(3),
                'typeDoc' => 'ReleveNote',
                'statut' => 'En attente',
                'motif_refus' => null,
                'date_traitement' => null,
            ],
            [
                'idEtudiant' => 9,
                'idAdmin' => null,
                'datesoumission' => Carbon::now()->subDays(2),
                'typeDoc' => 'ConventionStage',
                'statut' => 'En attente',
                'motif_refus' => null,
                'date_traitement' => null,
            ],
            [
                'idEtudiant' => 10,
                'idAdmin' => null,
                'datesoumission' => Carbon::now()->subDay(),
                'typeDoc' => 'AttestationReussite',
                'statut' => 'En attente',
                'motif_refus' => null,
                'date_traitement' => null,
            ],
            
            // Demandes Refusées
            [
                'idEtudiant' => 6,
                'idAdmin' => 2,
                'datesoumission' => Carbon::now()->subDays(10),
                'typeDoc' => 'AttestationReussite',
                'statut' => 'Refusée',
                'motif_refus' => 'Diplôme non encore obtenu. Veuillez soumettre votre demande après validation de tous vos modules.',
                'date_traitement' => Carbon::now()->subDays(8),
            ],
            [
                'idEtudiant' => 11,
                'idAdmin' => 3,
                'datesoumission' => Carbon::now()->subDays(7),
                'typeDoc' => 'ConventionStage',
                'statut' => 'Refusée',
                'motif_refus' => 'Informations incomplètes sur l\'entreprise d\'accueil. Veuillez compléter les coordonnées de l\'encadrant.',
                'date_traitement' => Carbon::now()->subDays(6),
            ],
            
            // Demandes récentes
            [
                'idEtudiant' => 8,
                'idAdmin' => 1,
                'datesoumission' => Carbon::now()->subDays(12),
                'typeDoc' => 'ReleveNote',
                'statut' => 'Validée',
                'motif_refus' => null,
                'date_traitement' => Carbon::now()->subDays(10),
            ],
            [
                'idEtudiant' => 12,
                'idAdmin' => null,
                'datesoumission' => Carbon::now()->subHours(12),
                'typeDoc' => 'AttestationScolarite',
                'statut' => 'En attente',
                'motif_refus' => null,
                'date_traitement' => null,
            ],
        ];

        DB::table('demande')->insert($demandes);
    }
}