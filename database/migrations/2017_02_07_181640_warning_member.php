<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class WarningMember extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create ( 'warning_member', function (Blueprint $table) {
			$table->bigIncrements ( 'id' );
			$table->bigInteger( 'warning_id',false,true);
			$table->bigInteger( 'member_id',false,true);
		} );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('warning_member');
    }
}
