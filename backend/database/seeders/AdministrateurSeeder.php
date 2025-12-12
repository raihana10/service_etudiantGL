<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdministrateurSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('administrateur')->insert([
            [
                'email' => 'admin@scolarite.ma',
                'motDePasse' => Hash::make('Admin@2024'),
                'created_at' => now(),
            ],
            [
                'email' => 'direction@scolarite.ma',
                'motDePasse' => Hash::make('Direction@2024'),
                'created_at' => now(),
            ],
            [
                'email' => 'scolarite@scolarite.ma',
                'motDePasse' => Hash::make('Scolarite@2024'),
                'created_at' => now(),
            ],
            [
                'email' => 'secretariat@scolarite.ma',
                'motDePasse' => Hash::make('Secret@2024'),
                'created_at' => now(),
            ],
        ]);
    }
}