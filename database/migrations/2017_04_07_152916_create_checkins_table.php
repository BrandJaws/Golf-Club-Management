<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCheckinsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('checkins', function (Blueprint $table) {

            $table->bigIncrements('id');
            $table->bigInteger('beacon_id',false,true);
            $table->morphs('reservation');
            $table->bigInteger('course_id',false,true);
            $table->bigInteger('member_id',false,true);
            $table->dateTime('checkinTime')->nullable()->defualt(Null);
            $table->enum('action',['clubEntry','gameEntry','clubHouse','gameExit']);
            $table->enum('recordedBy',['user','admin']);
            $table->boolean('onTime');

        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('checkins');
    }
}
