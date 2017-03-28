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
		Schema::create('group_member', function (Blueprint $table) {
			$table->bigInteger('group_id')->unsigned();
			$table->bigInteger('member_id')->unsigned();
			$table->foreign('group_id')->references('id')
				->on('group')
				->onDelete('cascade');
			$table->foreign('member_id')->references('id')
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
	public function down() {
		Schema::drop ( 'group_member' );
	}
}
