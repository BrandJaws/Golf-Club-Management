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
            $table->bigInteger('score_id')->unsigned();
            $table->bigInteger('hole_id');
            $table->integer('score');
            $table->integer('putts');
            $table->integer('fairway');
            $table->integer('distance');
            $table->integer('par');
            $table->integer('handicap');
            $table->foreign('score_id')->references('id')
              ->on('scores')
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
