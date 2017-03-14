<?php
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Config;
use App\Http\Models\Club;
use Carbon\Carbon;

class CoachSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker\Factory::create();
        $faker->addProvider(new Faker\Provider\Lorem($faker));
        $faker->addProvider ( new Faker\Provider\pt_BR\PhoneNumber ( $faker ) );
        
        $clubList = Club::select('id')->orderBy("id", 'ASC')
            ->get()
            ->toArray();
        for ($i = 0; $i < 10; $i ++) {
            $randomClub = $faker->randomElement($clubList);
            $selectedGender = $faker->randomElement ( Config::get ( 'global.gender' ) );
            $firstName = ($selectedGender == Config::get ( 'global.gender.male' )) ? $faker->firstNameMale () : $faker->firstNameFemale ();
            $lastName = $faker->lastName ();
            $password = Hash::make ( '123456' );
            DB::table('coaches')->insert([
                'club_id' => $randomClub['id'],
                'firstName' => $firstName,
                'lastName' => $lastName,
                'email' => strtolower ( $faker->email () ),
                'phone' => $faker->phoneNumber (),
                'profilePic' => NULL,
                'dob' => Carbon::createFromDate ( $faker->year (), $faker->month (), $faker->dayOfMonth () )->startOfDay ()->toDateString (),
                'gender' => $selectedGender,
                'status'=>'ACTIVE'
            ]);
        }
    }
}
