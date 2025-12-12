<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReclamationSeeder extends Seeder
{
    public function run(): void
    {
        $reclamations = [
            // Réclamations Résolues
            [
                'idEtudiant' => 1,
                'idAdmin' => 1,
                'description' => 'Ma note du module "Bases de Données" affichée sur mon relevé (12.5) ne correspond pas à celle annoncée par le professeur (14.5). Je demande une vérification.',
                'statut' => 'Résolue',
                'priorite' => 'Haute',
                'datesoumission' => Carbon::now()->subDays(45),
                'dateReponse' => Carbon::now()->subDays(40),
                'reponse' => 'Bonjour Mohammed, après vérification auprès du département, il s\'agissait effectivement d\'une erreur de saisie. Votre note a été corrigée à 14.5/20. Un nouveau relevé sera disponible sous 48h.',
                'sujet' => 'Erreur sur relevé de notes - Module BD',
            ],
            [
                'idEtudiant' => 3,
                'idAdmin' => 2,
                'description' => 'Je n\'arrive pas à accéder à la plateforme de téléchargement des cours. Mon compte semble bloqué depuis 3 jours.',
                'statut' => 'Résolue',
                'priorite' => 'Haute',
                'datesoumission' => Carbon::now()->subDays(20),
                'dateReponse' => Carbon::now()->subDays(18),
                'reponse' => 'Bonjour Youssef, votre compte a été débloqué. Le problème était dû à une expiration de mot de passe. Nous vous avons envoyé un email pour réinitialiser votre mot de passe.',
                'sujet' => 'Problème d\'accès à la plateforme',
            ],
            [
                'idEtudiant' => 8,
                'idAdmin' => 3,
                'description' => 'Mon attestation de scolarité validée il y a 2 semaines n\'est toujours pas disponible au service de scolarité. J\'en ai besoin de toute urgence pour ma demande de bourse.',
                'statut' => 'Résolue',
                'priorite' => 'Haute',
                'datesoumission' => Carbon::now()->subDays(15),
                'dateReponse' => Carbon::now()->subDays(13),
                'reponse' => 'Bonjour Nadia, nous nous excusons pour ce retard. Votre attestation est maintenant prête et vous pouvez la récupérer dès maintenant au bureau de la scolarité (bureau 204).',
                'sujet' => 'Retard de délivrance d\'attestation',
            ],
            
            // Réclamations En cours
            [
                'idEtudiant' => 5,
                'idAdmin' => 1,
                'description' => 'Le professeur du module "Analyse Numérique" n\'a toujours pas publié les résultats de l\'examen final passé il y a 3 semaines. Nous n\'avons aucune information.',
                'statut' => 'En cours',
                'priorite' => 'Normale',
                'datesoumission' => Carbon::now()->subDays(8),
                'dateReponse' => Carbon::now()->subDays(6),
                'reponse' => 'Bonjour Omar, nous avons contacté le professeur concerné. Il nous a informés que les résultats seront publiés d\'ici la fin de cette semaine. Nous vous tiendrons informé.',
                'sujet' => 'Retard publication résultats examen',
            ],
            [
                'idEtudiant' => 11,
                'idAdmin' => 2,
                'description' => 'Ma demande de convention de stage a été refusée pour informations incomplètes. Cependant, j\'ai bien rempli tous les champs obligatoires. Pouvez-vous préciser ce qui manque ?',
                'statut' => 'En cours',
                'priorite' => 'Haute',
                'datesoumission' => Carbon::now()->subDays(5),
                'dateReponse' => Carbon::now()->subDays(4),
                'reponse' => 'Bonjour Hamza, après examen de votre dossier, il manque les coordonnées téléphoniques et email de votre encadrant en entreprise. Veuillez soumettre une nouvelle demande avec ces informations.',
                'sujet' => 'Précisions sur refus de convention',
            ],
            
            // Réclamations Nouvelles
            [
                'idEtudiant' => 4,
                'idAdmin' => null,
                'description' => 'L\'emploi du temps publié pour le semestre prochain présente un chevauchement entre deux modules obligatoires (Topologie et Algèbre Avancée) le mardi de 14h à 16h.',
                'statut' => 'Nouvelle',
                'priorite' => 'Normale',
                'datesoumission' => Carbon::now()->subDays(3),
                'dateReponse' => null,
                'reponse' => null,
                'sujet' => 'Conflit d\'horaire emploi du temps',
            ],
            [
                'idEtudiant' => 9,
                'idAdmin' => null,
                'description' => 'Je souhaite modifier mon sujet de stage PFE car l\'entreprise a changé d\'orientation. Comment procéder pour mettre à jour ma convention ?',
                'statut' => 'Nouvelle',
                'priorite' => 'Normale',
                'datesoumission' => Carbon::now()->subDays(2),
                'dateReponse' => null,
                'reponse' => null,
                'sujet' => 'Modification sujet de stage',
            ],
            [
                'idEtudiant' => 12,
                'idAdmin' => null,
                'description' => 'La salle informatique A12 est très souvent indisponible alors qu\'elle apparaît comme libre dans le système de réservation. Cela perturbe nos TPs.',
                'statut' => 'Nouvelle',
                'priorite' => 'Basse',
                'datesoumission' => Carbon::now()->subDay(),
                'dateReponse' => null,
                'reponse' => null,
                'sujet' => 'Problème réservation salle informatique',
            ],
            [
                'idEtudiant' => 6,
                'idAdmin' => null,
                'description' => 'Je n\'ai pas reçu la notification par email de validation de mon attestation de scolarité alors que le statut indique "Validée" sur le portail depuis 2 jours.',
                'statut' => 'Nouvelle',
                'priorite' => 'Basse',
                'datesoumission' => Carbon::now()->subHours(18),
                'dateReponse' => null,
                'reponse' => null,
                'sujet' => 'Non-réception email de notification',
            ],
            [
                'idEtudiant' => 10,
                'idAdmin' => null,
                'description' => 'Le lien de téléchargement du support de cours du module "Deep Learning" est cassé. Impossible d\'accéder aux ressources depuis une semaine.',
                'statut' => 'Nouvelle',
                'priorite' => 'Normale',
                'datesoumission' => Carbon::now()->subHours(5),
                'dateReponse' => null,
                'reponse' => null,
                'sujet' => 'Lien de téléchargement cassé',
            ],
        ];

        DB::table('reclamation')->insert($reclamations);
    }
}