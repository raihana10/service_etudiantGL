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
        Schema::create('relevenote', function (Blueprint $table) {
            $table->integer('idRN', true);
            $table->integer('idDemande')->unique('iddemande');
            $table->string('annee', 20);
            $table->enum('semestre', ['S1', 'S2', 'S3', 'S4', 'S5', 'S6']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('relevenote');
    }
};
