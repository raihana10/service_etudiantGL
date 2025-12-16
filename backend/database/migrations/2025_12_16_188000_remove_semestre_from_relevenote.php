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
        if (Schema::hasColumn('relevenote', 'semestre')) {
            Schema::table('relevenote', function (Blueprint $table) {
                $table->dropColumn('semestre');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('relevenote', function (Blueprint $table) {
            $table->enum('semestre', ['S1', 'S2', 'S3', 'S4', 'S5', 'S6'])->after('annee');
        });
    }
};
