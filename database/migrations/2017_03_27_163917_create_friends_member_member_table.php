<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFriendsMemberMemberTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('friends_member_member', function (Blueprint $table) {
            $table->bigInteger('member_id')->unsigned();
            $table->bigInteger('friend_member_id')->unsigned();
            $table->foreign('member_id')->references('id')
                ->on('member')
                ->onDelete('cascade');
            $table->foreign('friend_member_id')->references('id')
                ->on('member')
                ->onDelete('cascade');
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
        Schema::drop('friends_member_member');
    }
}
