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
        if (Schema::hasColumn('reclamation', 'priorite')) {
            Schema::table('reclamation', function (Blueprint $table) {
                $table->dropColumn('priorite');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reclamation', function (Blueprint $table) {
            $table->enum('priorite', ['Haute', 'Normale', 'Basse'])->nullable()->default('Normale')->after('statut');
        });
    }
};
