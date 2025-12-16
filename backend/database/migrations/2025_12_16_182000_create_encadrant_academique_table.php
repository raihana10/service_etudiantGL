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
        Schema::create('encadrant_academique', function (Blueprint $table) {
            $table->integer('idEncadrant', true);
            $table->string('nom', 100);
            $table->string('prenom', 100);
            $table->string('grade', 50);
            $table->string('specialite', 100)->nullable();
            $table->string('email', 150)->unique();
            $table->string('telephone', 20)->nullable();
            $table->boolean('actif')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('encadrant_academique');
    }
};
