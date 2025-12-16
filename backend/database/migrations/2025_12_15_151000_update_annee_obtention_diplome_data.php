<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Mettre à jour les étudiants existants avec des valeurs pour anneeObtentionDiplome
        DB::table('etudiant')->where('numApogee', '20210001')->update(['anneeObtentionDiplome' => '2024']); // ALAMI
        DB::table('etudiant')->where('numApogee', '20210004')->update(['anneeObtentionDiplome' => '2024']); // DARIF
        DB::table('etudiant')->where('numApogee', '20210006')->update(['anneeObtentionDiplome' => '2024']); // FASSI
        DB::table('etudiant')->where('numApogee', '20210014')->update(['anneeObtentionDiplome' => '2024']); // NACIRI
        
        DB::table('etudiant')->where('numApogee', '20190007')->update(['anneeObtentionDiplome' => '2023']); // GHAZI
        DB::table('etudiant')->where('numApogee', '20200008')->update(['anneeObtentionDiplome' => '2023']); // HAJJI
        DB::table('etudiant')->where('numApogee', '20190009')->update(['anneeObtentionDiplome' => '2023']); // IDRISSI
        DB::table('etudiant')->where('numApogee', '20200010')->update(['anneeObtentionDiplome' => '2023']); // JAAFARI
        DB::table('etudiant')->where('numApogee', '20190011')->update(['anneeObtentionDiplome' => '2023']); // KHALIL
        DB::table('etudiant')->where('numApogee', '20200012')->update(['anneeObtentionDiplome' => '2023']); // LAHLOU
        
        // Les étudiants suivants restent null (pas encore diplômés)
        // 20220002 - BENNANI
        // 20230003 - CHAKIR
        // 20220005 - ELIDRISSI
        // 20220013 - MANSOURI
        // 20230015 - OUARDI
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remettre toutes les valeurs à null
        DB::table('etudiant')->update(['anneeObtentionDiplome' => null]);
    }
};
