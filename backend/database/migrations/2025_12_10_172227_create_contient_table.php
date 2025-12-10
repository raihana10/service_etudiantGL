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
            $table->enum('semestre', ['S1', 'S2', 'S3', 'S4', 'S5', 'S6']);
            $table->decimal('coefficient', 3)->nullable()->default(1);

            $table->unique(['idF', 'idM', 'semestre'], 'filiere_module_semestre');
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
