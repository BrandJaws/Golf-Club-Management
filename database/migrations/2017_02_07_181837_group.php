<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
class Group extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('group', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->bigInteger('member_id')->unsigned();
			$table->string ( 'name', 250 );
			$table->foreign('member_id')->references('id')
				->on('member')
				->onDelete('cascade');


		});
	}
	
	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::drop ( 'group' );
	}
}
