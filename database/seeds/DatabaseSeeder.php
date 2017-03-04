<?php
use Illuminate\Database\Seeder;
class DatabaseSeeder extends Seeder {
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run() {
		$this->call ( CreateClubAndStaff::class );
		$this->call ( MemberSeeder::class );
		$this->call ( CourseSeeder::class );
		$this->call ( BeaconSeeder::class );
	}
}
