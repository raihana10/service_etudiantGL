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
        Schema::create('filiere', function (Blueprint $table) {
            $table->integer('idF', true);
            $table->string('nomF', 100);
            $table->string('niveau', 50);

            $table->unique(['nomF', 'niveau'], 'nomf_niveau');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('filiere');
    }
};
