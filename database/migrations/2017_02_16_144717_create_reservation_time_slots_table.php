<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReservationTimeSlotsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reservation_time_slots', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('reservation_id');
            $table->morphs('reservation_type','reservation_timeslots_reservation_type_morph_index');
            $table->dateTime('time_start');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reservation_time_slots');
    }
}
