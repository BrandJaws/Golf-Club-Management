<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCoachesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coaches', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('club_id');
            $table->string('firstName', 50);
            $table->string('lastName', 50);
            $table->string('email', 50);
            $table->string('phone', 50);
            $table->text('specialities')
                ->nullable()
                ->default(NULL);
            $table->string('profilePic', 250)
                ->nullable()
                ->default(NULL);
            $table->enum('gender', [
                'MALE',
                'FEMALE'
            ]);
            $table->date('dob')
                ->nullable()
                ->default(NULL);
            $table->enum('status', [
                'ACTIVE',
                'INACTIVE'
            ])->default('ACTIVE');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('coaches');
    }
}
