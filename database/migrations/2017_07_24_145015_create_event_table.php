<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('event', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('club_id');
            $table->bigInteger('coach_id');
            $table->string('name', 100);
            $table->text('description')
              ->nullable()
              ->default(null);
            $table->integer('seats', false, true)->default(0);
            $table->integer('sessions', false, true)->default(0);
            $table->integer('price', false, true)->default(0);
            $table->text('promotionContent')
              ->nullable()
              ->default(null);
            $table->enum('promotionType', [
              config('global.contentType.image'),
              config('global.contentType.video')
            ])->default(config('global.contentType.image'));
            $table->date('startDate')
              ->nullable()
              ->default(null);
            $table->date('endDate')
              ->nullable()
              ->default(null);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('event');
    }
}
