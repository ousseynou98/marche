<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('demandes_avis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('marche_id')->constrained()->onDelete('cascade');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade'); // DAGE qui fait la demande
            $table->foreignId('assigned_to')->nullable()->constrained('users')->onDelete('set null'); // Agent CPM assigné
            $table->string('statut')->default('En attente'); // Statut: En attente, Traité, Approuvé, Rejeté
            $table->string('document')->nullable(); // Document de demande d'avis
            $table->text('projet_avis')->nullable(); // Projet d'avis rédigé par l'agent
            $table->text('avis_final')->nullable(); // Avis final validé par le CPM
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('demande_avis');
    }
};
