<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateScoreCardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('score_cards', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->morphs('reservation');
            $table->bigInteger('player_member_id');
            $table->bigInteger('manager_member_id');
            $table->decimal('handicap', 5, 2);
            $table->enum('tee',[
              'Pink',
              'Black',
              'Gold',
              'Blue',
              'Silver',
              'Green',
              'White',
              'Purple',
              'Orange',

            ]);
            $table->enum('scorecard_type', array('STROKE PLAY'));
            $table->enum('use_handicap', array('YES', 'NO'))->default('NO');
            $table->integer('round_type');
            $table->integer('starting_hole');
//            $table->integer('wager');
//            $table->integer('side_bets');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('score_cards');
    }
}
