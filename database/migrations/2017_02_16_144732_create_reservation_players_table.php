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
            $table->morphs('reservation');
            $table->bigInteger('member_id')->unsigned();
            $table->bigInteger('parent_id')->unsigned()->nullable();
            $table->enum('response_status', array('PENDING','CONFIRMED','CANCELLED','NA'));
            $table->enum('reservation_status', array('RESERVED','WAITING','PENDING RESERVED','PENDING WAITING'));
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
        Schema::dropIfExists('reservation_players');
    }
}
