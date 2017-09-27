<?php
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Config;
use App\Http\Models\Club;
use Carbon\Carbon;

class CourseSeeder extends Seeder
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
        $clubList = Club::select('id')->orderBy("id", 'ASC')
            ->get()
            ->toArray();
        for ($i = 0; $i < 10; $i ++) {
            $randomClub = $faker->randomElement($clubList);
            $course = \App\Http\Models\Course::create([
                'club_id' => $randomClub['id'],
                'name' => $faker->sentence(2, true),
                'openTime' => '05:00:00',
                'closeTime' => '23:00:00',
                'bookingInterval' => 15,
                'bookingDuration'=>240,
                'status' => 'OPEN',
                'numberOfHoles'=> 18,
                'tees' => '[{"color":"Pink","distance":1,"mensRating":"","mensSlope":"","womensRating":"","womensSlope":""},{"color":"Black","distance":1,"mensRating":"","mensSlope":"","womensRating":"","womensSlope":""}]'
            ]);
            for($holeCount = 0; $holeCount < 18; $holeCount ++){
                $courseHole = new \App\Http\Models\CourseHole();
                $courseHole->course_id = $course->id;
                $courseHole->hole_number = $holeCount+1;
                $courseHole->mens_handicap = 1;
                $courseHole->mens_par = 1;
                $courseHole->womens_handicap = 1;
                $courseHole->womens_par = 1;
                $courseHole->tee_values = '[{"color":"Pink","cssClass":"pink","distance":1},{"color":"Black","cssClass":"black","distance":1}]';
                $courseHole->save();
            }
        }
    }
}
