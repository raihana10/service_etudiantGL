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
            $table->enum('statut', ['En cours', 'Résolue'])
                  ->default('En cours')
                  ->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reclamation', function (Blueprint $table) {
             $table->enum('statut', ['Nouvelle', 'En cours', 'Résolue'])
                  ->default('Nouvelle')
                  ->change();
        });
    }
};
