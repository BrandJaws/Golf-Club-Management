<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBeaconTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('beacon', function (Blueprint $table) {
            $table->bigincrements('id');
            $table->bigInteger('club_id');
            $table->bigInteger('course_id');
            $table->string('name', 50);
            $table->string('UUID');
            $table->integer('major');
            $table->integer('minor');
            $table->text('configuration')
                ->nullable()
                ->default(Null);
            $table->enum('status', [
                'ACTIVE',
                'INACTIVE'
            ])->default('ACTIVE');
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
        Schema::drop('beacon');
    }
}