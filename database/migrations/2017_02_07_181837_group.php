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
		Schema::create ( 'group', function (Blueprint $table) {
			$table->bigIncrements ( 'id' );
			$table->bigInteger ( 'member_id', false, true );
			$table->string ( 'name', 250 );
		} );
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
