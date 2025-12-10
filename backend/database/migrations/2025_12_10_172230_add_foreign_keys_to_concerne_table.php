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
        Schema::table('concerne', function (Blueprint $table) {
            $table->foreign(['idContient'], 'concerne_ibfk_1')->references(['idContient'])->on('contient')->onUpdate('restrict')->onDelete('cascade');
            $table->foreign(['idEtudiant'], 'concerne_ibfk_2')->references(['idEtudiant'])->on('etudiant')->onUpdate('restrict')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('concerne', function (Blueprint $table) {
            $table->dropForeign('concerne_ibfk_1');
            $table->dropForeign('concerne_ibfk_2');
        });
    }
};
