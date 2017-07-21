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
            DB::table('course')->insert([
                'club_id' => $randomClub['id'],
                'name' => $faker->sentence(2, true),
                'openTime' => '05:00:00',
                'closeTime' => '23:00:00',
                'bookingInterval' => 15,
                'bookingDuration'=>240,
                'status' => 'OPEN',
                'tees' => '[]'
            ]);
        }
    }
}
