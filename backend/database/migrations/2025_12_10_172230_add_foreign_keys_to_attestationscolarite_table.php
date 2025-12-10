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
        Schema::table('attestationscolarite', function (Blueprint $table) {
            $table->foreign(['idDemande'], 'attestationscolarite_ibfk_1')->references(['idDemande'])->on('demande')->onUpdate('restrict')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attestationscolarite', function (Blueprint $table) {
            $table->dropForeign('attestationscolarite_ibfk_1');
        });
    }
};
