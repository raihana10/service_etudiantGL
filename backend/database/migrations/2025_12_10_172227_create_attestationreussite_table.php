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
        Schema::create('attestationreussite', function (Blueprint $table) {
            $table->integer('idAR', true);
            $table->integer('idDemande')->unique('iddemande');
            $table->string('anneeObtention', 20);
            $table->string('diplomeConcernee', 150);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attestationreussite');
    }
};
