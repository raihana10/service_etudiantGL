<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FiliereSeeder extends Seeder
{
    public function run(): void
    {
        $filieres = [

            ['nomF' => '2Ap', 'niveau' => '1ere année'],
            ['nomF' => 'GI', 'niveau' => '1ere année'],
            ['nomF' => 'GSTR', 'niveau' => '1ere année'],
            ['nomF' => 'SCM', 'niveau' => '1ere année'],
            ['nomF' => 'IA', 'niveau' => '1ere année'],
            ['nomF' => 'Civil', 'niveau' => '1ere année'],
            ['nomF' => 'Cyder', 'niveau' => '1ere année'],
            
            ['nomF' => '2Ap', 'niveau' => '2ere année'],
            ['nomF' => 'GI', 'niveau' => '2ere année'],
            ['nomF' => 'GSTR', 'niveau' => '2ere année'],
            ['nomF' => 'SCM', 'niveau' => '2ere année'],
            ['nomF' => 'IA', 'niveau' => '2ere année'],
            ['nomF' => 'Civil', 'niveau' => '2ere année'],
            ['nomF' => 'Cyder', 'niveau' => '2ere année'],
            

            ['nomF' => 'GI', 'niveau' => '3ere année'],
            ['nomF' => 'GSTR', 'niveau' => '3ere année'],
            ['nomF' => 'SCM', 'niveau' => '3ere année'],
            ['nomF' => 'IA', 'niveau' => '3ere année'],
            ['nomF' => 'Civil', 'niveau' => '3ere année'],
            ['nomF' => 'Cyder', 'niveau' => '3ere année'],
        ];

        DB::table('filiere')->insert($filieres);
    }
}