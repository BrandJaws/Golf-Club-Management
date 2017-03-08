<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRoutineReservationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('routine_reservations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('club_id');
            $table->bigInteger('course_id');
            $table->bigInteger('parent_id')->unsigned()->nullable();
            $table->enum('status', array('RESERVED','WAITING','PENDING RESERVED','PENDING WAITING'));
            $table->tinyInteger('nextJobToProcess')->default(0);
            $table->foreign('parent_id')->references('id')
              ->on('member')
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
        Schema::dropIfExists('routine_reservations');
    }
}
