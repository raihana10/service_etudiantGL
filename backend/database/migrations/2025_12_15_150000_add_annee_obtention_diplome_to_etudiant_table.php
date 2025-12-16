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
        Schema::table('etudiant', function (Blueprint $table) {
            $table->string('anneeObtentionDiplome', 20)->nullable()->after('lieuNaissance');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('etudiant', function (Blueprint $table) {
            $table->dropColumn('anneeObtentionDiplome');
        });
    }
};
