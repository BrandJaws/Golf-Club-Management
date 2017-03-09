<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCoursesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('course', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('club_id');
            $table->string('name',50);
            $table->time('openTime');
            $table->time('closeTime');
            $table->integer('bookingInterval');
            $table->integer('bookingDuration');
            $table->integer('numberOfHoles')->default(0);
            $table->enum('status', array('CLOSED', 'OPEN'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('course');
    }
}
