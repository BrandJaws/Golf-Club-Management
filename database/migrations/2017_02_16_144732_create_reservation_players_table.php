<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReservationPlayersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reservation_players', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('reservation_id');
            $table->morphs('reservation_type','reservation_timeslots_reservation_type_morph_index');
            $table->bigInteger('member_id')->unsigned();
            $table->enum('status', array('PENDING','CONFIRMED','CANCELLED','NA'));
           
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reservation_players');
    }
}
