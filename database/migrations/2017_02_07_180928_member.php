<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Member extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('member', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('club_id');
            $table->string('firstName', 50);
            $table->string('lastName', 50);
            $table->string('email', 50);
            $table->string('phone', 50);
            $table->string('profilePic', 250)
                ->nullable()
                ->default(NULL);
            $table->string('password', 100);
            $table->enum('gender', [
                'MALE',
                'FEMALE'
            ]);
            $table->date('dob')
                ->nullable()
                ->default(NULL);
            $table->string('device_registeration_id', 255)
                ->nullable()
                ->default(NULL);
            $table->enum('device_type', [
                'Android',
                'Iphone'
            ])
                ->nullable()
                ->default(NULL);
            $table->bigInteger('main_member_id', false, true)->default(0);
            $table->enum('status', [
                'ACTIVE',
                'INACTIVE'
            ])->default('ACTIVE');
            $table->string('auth_token', 100)
                ->nullable()
                ->default(NULL);
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
        Schema::drop('member');
    }
}
