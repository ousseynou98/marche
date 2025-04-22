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
        Schema::table('demande_avis', function (Blueprint $table) {
            $table->text('commentaire')->nullable();
            // $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('demande_avis', function (Blueprint $table) {
            $table->dropColumn('commentaire');
            // $table->dropForeign(['created_by']);
        });
    }
};
