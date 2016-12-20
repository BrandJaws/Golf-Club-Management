<?php
use Illuminate\Database\Seeder;
use App\Http\Models\Employee;
use Illuminate\Support\Facades\Hash;
class CreateClubAndStaff extends Seeder {
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run() {
		$faker = Faker\Factory::create ();
		$id = \DB::table ( 'club' )->insert ( [ 
				'name' => $faker->name (),
				'address' => $faker->address (),
				'opening' => '00:00:00',
				'closing' => '00:00:00' 
		]
		 );
		
		Employee::create( [ 
				'club_id'=>$id,
				'firstName' =>  $faker->firstNameMale(),
				'lastName' =>  $faker->lastName(),
				'email' => 'admin@grit.com',
				'password' => Hash::make( '123456' ),
				'created_at' => $faker->dateTime ( 'now' ) 
		] );
	}
}
