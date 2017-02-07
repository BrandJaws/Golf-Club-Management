<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
class GroupMember extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create ( 'group_member', function (Blueprint $table) {
			$table->bigIncrements ( 'id' );
			$table->bigInteger ( 'group_id', false, true );
			$table->bigInteger ( 'member_id', false, true );
		} );
	}
	
	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::drop ( 'group_member' );
	}
}
