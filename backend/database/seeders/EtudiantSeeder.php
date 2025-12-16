<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class EtudiantSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('fr_FR');
        
        $etudiants = [
            // Informatique Licence
            [
                'nom' => 'ALAMI',
                'prenom' => 'Mohammed',
                'idF' => 1,
                'niveau' => 'GI3',
                'CIN' => 'AA123456',
                'numApogee' => '20210001',
                'emailInstitu' => 'm.alami@etu.university.ma',
                'dateNaissance' => '2002-05-15',
                'lieuNaissance' => 'Casablanca',
                'anneeObtentionDiplome' => null,
            ],
            [
                'nom' => 'BENNANI',
                'prenom' => 'Fatima',
                'idF' => 1,
                'niveau' => 'GSTR2',
                'CIN' => 'BB234567',
                'numApogee' => '20220002',
                'emailInstitu' => 'f.bennani@etu.university.ma',
                'dateNaissance' => '2003-08-22',
                'lieuNaissance' => 'Rabat',
                'anneeObtentionDiplome' => null,
            ],
            [
                'nom' => 'CHAKIR',
                'prenom' => 'Youssef',
                'idF' => 1,
                'niveau' => '2Ap1',
                'CIN' => 'CC345678',
                'numApogee' => '20230003',
                'emailInstitu' => 'y.chakir@etu.university.ma',
                'dateNaissance' => '2004-12-10',
                'lieuNaissance' => 'Fès',
                'anneeObtentionDiplome' => null,
            ],
            
            // Mathématiques
            [
                'nom' => 'DARIF',
                'prenom' => 'Salma',
                'idF' => 2,
                'niveau' => 'SCM3',
                'CIN' => 'DD456789',
                'numApogee' => '20210004',
                'emailInstitu' => 's.darif@etu.university.ma',
                'dateNaissance' => '2002-03-18',
                'lieuNaissance' => 'Marrakech',
                'anneeObtentionDiplome' => null,
            ],
            [
                'nom' => 'ELIDRISSI',
                'prenom' => 'Omar',
                'idF' => 2,
                'niveau' => 'Cyber2',
                'CIN' => 'EE567890',
                'numApogee' => '20220005',
                'emailInstitu' => 'o.elidrissi@etu.university.ma',
                'dateNaissance' => '2003-07-25',
                'lieuNaissance' => 'Tanger',
                'anneeObtentionDiplome' => null,
            ],
            
            // Économie
            [
                'nom' => 'FASSI',
                'prenom' => 'Amina',
                'idF' => 6,
                'niveau' => 'IA3',
                'CIN' => 'FF678901',
                'numApogee' => '20210006',
                'emailInstitu' => 'a.fassi@etu.university.ma',
                'dateNaissance' => '2002-11-30',
                'lieuNaissance' => 'Fès',
                'anneeObtentionDiplome' => null,
            ],
            
            // Master Génie Logiciel
            [
                'nom' => 'GHAZI',
                'prenom' => 'Karim',
                'idF' => 7,
                'niveau' => '"Ap2',
                'CIN' => 'GG789012',
                'numApogee' => '20190007',
                'emailInstitu' => 'k.ghazi@etu.university.ma',
                'dateNaissance' => '2000-04-12',
                'lieuNaissance' => 'Casablanca',
                'anneeObtentionDiplome' => 2024,
            ],
            [
                'nom' => 'HAJJI',
                'prenom' => 'Nadia',
                'idF' => 7,
                'niveau' => 'GI1',
                'CIN' => 'HH890123',
                'numApogee' => '20200008',
                'emailInstitu' => 'n.hajji@etu.university.ma',
                'dateNaissance' => '2001-09-05',
                'lieuNaissance' => 'Rabat',
                'anneeObtentionDiplome' => null,
            ],
            
            // Master IA
            [
                'nom' => 'IDRISSI',
                'prenom' => 'Mehdi',
                'idF' => 9,
                'niveau' => 'SCM2',
                'CIN' => 'II901234',
                'numApogee' => '20190009',
                'emailInstitu' => 'm.idrissi@etu.university.ma',
                'dateNaissance' => '2000-06-20',
                'lieuNaissance' => 'Meknès',
                'anneeObtentionDiplome' => 2024,
            ],
            [
                'nom' => 'JAAFARI',
                'prenom' => 'Sofia',
                'idF' => 9,
                'niveau' => '2Ap1',
                'CIN' => 'JJ012345',
                'numApogee' => '20200010',
                'emailInstitu' => 's.jaafari@etu.university.ma',
                'dateNaissance' => '2001-02-14',
                'lieuNaissance' => 'Agadir',
                'anneeObtentionDiplome' => null,
            ],
            
            // Master Cybersécurité
            [
                'nom' => 'KHALIL',
                'prenom' => 'Hamza',
                'idF' => 10,
                'niveau' => 'cyber2',
                'CIN' => 'KK123456',
                'numApogee' => '20190011',
                'emailInstitu' => 'h.khalil@etu.university.ma',
                'dateNaissance' => '2000-10-08',
                'lieuNaissance' => 'Casablanca',
                'anneeObtentionDiplome' => 2024,
            ],
            [
                'nom' => 'LAHLOU',
                'prenom' => 'Imane',
                'idF' => 10,
                'niveau' => 'GSTR1',
                'CIN' => 'LL234567',
                'numApogee' => '20200012',
                'emailInstitu' => 'i.lahlou@etu.university.ma',
                'dateNaissance' => '2001-01-28',
                'lieuNaissance' => 'Oujda',
                'anneeObtentionDiplome' => null,
            ],
            
            // Physique
            [
                'nom' => 'MANSOURI',
                'prenom' => 'Anas',
                'idF' => 3,
                'niveau' => '2Ap2',
                'CIN' => 'MM345678',
                'numApogee' => '20220013',
                'emailInstitu' => 'a.mansouri@etu.university.ma',
                'dateNaissance' => '2003-04-17',
                'lieuNaissance' => 'Tétouan',
                'anneeObtentionDiplome' => null,
            ],
            
            // Chimie
            [
                'nom' => 'NACIRI',
                'prenom' => 'Yasmine',
                'idF' => 4,
                'niveau' => 'GI3',
                'CIN' => 'NN456789',
                'numApogee' => '20210014',
                'emailInstitu' => 'y.naciri@etu.university.ma',
                'dateNaissance' => '2002-08-03',
                'lieuNaissance' => 'Kenitra',
                'anneeObtentionDiplome' => null,
            ],
            
            // Biologie
            [
                'nom' => 'OUARDI',
                'prenom' => 'Rachid',
                'idF' => 5,
                'niveau' => 'IA1',
                'CIN' => 'OO567890',
                'numApogee' => '20230015',
                'emailInstitu' => 'r.ouardi@etu.university.ma',
                'dateNaissance' => '2004-11-19',
                'lieuNaissance' => 'El Jadida',
                'anneeObtentionDiplome' => null,
            ],
        ];

        DB::table('etudiant')->insert($etudiants);
    }
}