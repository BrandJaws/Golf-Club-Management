<?php
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Config;
use App\Http\Models\Club;
use Carbon\Carbon;
class MemberSeeder extends Seeder {
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run() {
		$faker = Faker\Factory::create ();
		$faker->addProvider ( new Faker\Provider\pt_BR\PhoneNumber ( $faker ) );
		
		// $corpBusinesses = Business::where('type', '=', Config::get('global.business.type.corporate'))->get();
		
		$genderSelectionArr = array (
				'Male',
				'Female' 
		);
		
		$clubList = Club::select ( 'id' )->orderBy ( "id", 'ASC' )->get ()->toArray ();
		for($i = 0; $i < 100; $i ++) {
			$randomClub = $faker->randomElement ( $clubList );
			
			$selectedGender = $faker->randomElement ( Config::get ( 'global.gender' ) );
			$firstName = ($selectedGender == Config::get ( 'global.gender.male' )) ? $faker->firstNameMale () : $faker->firstNameFemale ();
			$lastName = $faker->lastName ();
			$password = Hash::make ( '123456' );
			$customerId = DB::table ( 'member' )->insertGetId ( [ 
					'club_id' => $randomClub ['id'],
					'firstName' => $firstName,
					'lastName' => $lastName,
					'email' => strtolower ( $faker->email () ),
					'phone' => $faker->phoneNumber (),
					'password' => $password,
					'profilePic' => NULL,
					'dob' => Carbon::createFromDate ( $faker->year (), $faker->month (), $faker->dayOfMonth () )->startOfDay ()->toDateString (),
					'gender' => $selectedGender,
					'auth_token' => NULL,
					'created_at' => Carbon::now () 
			] );
		}
	}
}
