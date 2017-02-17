<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmployeeTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('club_id');
            $table->string('firstName', 50);
            $table->string('lastName', 50);
            $table->string('email', 50)->unique();
            $table->string('password', 100);
            $table->string('remember_token', 250)->nullable()
                ->default(Null);
            $table->string('phone', 50)
                ->nullable()
                ->default(Null);
            $table->string('profilePic', 250)
                ->nullable()
                ->default(Null);
            $table->text('permissions')
                ->nullable()
                ->default(Null);
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
        Schema::drop('employee');
    }
}
