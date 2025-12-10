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
        Schema::create('attestationscolarite', function (Blueprint $table) {
            $table->integer('idAS', true);
            $table->integer('idDemande')->unique('iddemande');
            $table->integer('nbrExemplaire')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attestationscolarite');
    }
};
