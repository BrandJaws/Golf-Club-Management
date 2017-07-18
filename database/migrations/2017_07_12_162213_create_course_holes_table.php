<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCourseHolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('course_holes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('course_id')->unsigned();
            $table->bigInteger('hole_number')->unsigned();
            $table->integer('mens_handicap');
            $table->integer('mens_par');
            $table->integer('womens_handicap');
            $table->integer('womens_par');
            $table->text('tee_values');
            $table->timestamps();
            $table->foreign('course_id')->references('id')
              ->on('course')
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
        Schema::dropIfExists('course_holes');
    }
}
