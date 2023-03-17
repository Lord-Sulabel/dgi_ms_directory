<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dgi_assujettissements', function (Blueprint $table) {
            $table->id();
            $table->text('fk_repertoire')->nullable();
            $table->text('dateDebut')->nullable();
            $table->text('dateFin')->nullable();
            $table->integer('etat')->nullable();
            
            $table->date('dateCreate')->nullable();
            $table->text('agentCreate')->nullable();
            $table->date('dateUpdate')->nullable();
            $table->text('agentUpdate')->nullable();
            $table->date('dateDelete')->nullable();
            $table->text('agentDelete')->nullable();

            $table->text('fk_natureImpots')->nullable();
            $table->text('fk_impots')->nullable();


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dgi_assujettissements');
    }
};
