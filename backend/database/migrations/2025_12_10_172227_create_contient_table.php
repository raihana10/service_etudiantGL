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
        Schema::create('contient', function (Blueprint $table) {
            $table->integer('idContient', true);
            $table->integer('idF')->index('idx_contient_filiere');
            $table->integer('idM')->index('idx_contient_module');

            // Supprimer la contrainte unique car elle contenait "semestre"
            $table->unique(['idF', 'idM'], 'filiere_module');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contient');
    }
};
