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
        Schema::create('assujettissements', function (Blueprint $table) {
            $table->id();
            $table->text('fk_contribuable')->nullable();
            $table->text('date_debut')->nullable();
            $table->text('date_fin')->nullable();
            $table->text('fk_impots')->nullable();
            $table->text('fk_actes_generateurs')->nullable();
            $table->text('create_date')->nullable();
            $table->text('create_agent')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('assujettissements');
    }
};
