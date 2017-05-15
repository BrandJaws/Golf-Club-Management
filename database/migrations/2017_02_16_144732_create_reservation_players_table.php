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
            $table->tinyInteger('group_size');
            $table->enum('response_status', array('CONFIRMED','PENDING','DROPPED'));
            $table->enum('reservation_status', array('RESERVED','WAITING','PENDING RESERVED','PENDING WAITING','NEW ADDITION'));
            $table->tinyInteger('nextJobToProcess')->default(0);
            $table->enum('process_type', array('INITIAL','FINAL'))->default('INITIAL');
            $table->enum('comingOnTime',array("NOT RESPONDED","YES","NO"))->default('NOT RESPONDED');
            $table->timestamps();
            $table->foreign('parent_id')->references('id')
                ->on('member');
           
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
