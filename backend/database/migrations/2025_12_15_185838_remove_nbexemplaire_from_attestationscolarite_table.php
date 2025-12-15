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
            $table->dropColumn('nbrExemplaire');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attestationscolarite', function (Blueprint $table) {
            $table->integer('nbrExemplaire')->default(1);
        });
    }
};
