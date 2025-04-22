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
        Schema::create('avis_historiques', function (Blueprint $table) {
            $table->id();
            $table->foreignId('demande_avis_id')->constrained()->onDelete('cascade');
            $table->foreignId('agent_id')->constrained('users')->onDelete('cascade');
            $table->string('avis'); // 'favorable' ou 'défavorable'
            $table->string('projet_avis_document'); // chemin du fichier
            $table->string('statut')->default('En attente validation CPM'); // ou "Validé"
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('avis_historiques');
    }
};
