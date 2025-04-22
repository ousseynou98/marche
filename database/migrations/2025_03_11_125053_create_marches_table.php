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
    Schema::create('marches', function (Blueprint $table) {
        $table->id();
        $table->string('reference')->unique();
        $table->string('realisation_envisagee');
        $table->string('type_marche');
        $table->string('source_financement');
        $table->string('mode_passation');
        $table->date('date_lancement')->nullable();
        $table->date('date_attribution')->nullable();
        $table->date('date_demarrage')->nullable();
        $table->date('date_achevement')->nullable();
        $table->decimal('montant', 15, 2);
        $table->string('publicite')->nullable();
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('marches');
    }
};
