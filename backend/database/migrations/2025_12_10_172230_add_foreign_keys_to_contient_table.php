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
        Schema::table('contient', function (Blueprint $table) {
            $table->foreign(['idF'], 'contient_ibfk_1')->references(['idF'])->on('filiere')->onUpdate('restrict')->onDelete('cascade');
            $table->foreign(['idM'], 'contient_ibfk_2')->references(['idM'])->on('module')->onUpdate('restrict')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contient', function (Blueprint $table) {
            $table->dropForeign('contient_ibfk_1');
            $table->dropForeign('contient_ibfk_2');
        });
    }
};
