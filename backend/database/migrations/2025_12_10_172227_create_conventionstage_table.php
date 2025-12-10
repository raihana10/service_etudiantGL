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
        Schema::create('conventionstage', function (Blueprint $table) {
            $table->integer('idCS', true);
            $table->integer('idDemande')->unique('iddemande');
            $table->string('representantEntreprise', 100);
            $table->string('villeEntreprise', 100);
            $table->string('encadrantAcademique', 100);
            $table->string('encadrantEntreprise', 100);
            $table->string('fctEncadrant', 100);
            $table->string('TLEncadrant', 20);
            $table->string('emailEncadrant', 100);
            $table->string('emailEntreprise', 100);
            $table->string('TLEntreprise', 20);
            $table->string('fctRepresentant', 100);
            $table->string('raisonSocialeEntreprise', 150);
            $table->string('secteurEntreprise', 100);
            $table->text('adresseEntreprise');
            $table->string('typeStage', 50);
            $table->date('dateDebut');
            $table->date('dateFin');
            $table->text('sujetStage');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conventionstage');
    }
};
