<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('reclamation', function (Blueprint $table) {
            $table->foreign(['idEtudiant'], 'reclamation_ibfk_1')->references(['idEtudiant'])->on('etudiant')->onUpdate('restrict')->onDelete('cascade');
            $table->foreign(['idAdmin'], 'reclamation_ibfk_2')->references(['idAdmin'])->on('administrateur')->onUpdate('restrict')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reclamation', function (Blueprint $table) {
            $table->dropForeign('reclamation_ibfk_1');
            $table->dropForeign('reclamation_ibfk_2');
        });
    }
};
