<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateScoreHolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('score_holes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('score_card_id')->unsigned();
            $table->bigInteger('hole_id');
            $table->integer('score');
            $table->integer('putts');
            $table->enum('fairway',["LEFT","CENTER","RIGHT"])->default("CENTER");
            $table->foreign('score_card_id')->references('id')
              ->on('score_cards')
              ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('score_holes');
    }
}
