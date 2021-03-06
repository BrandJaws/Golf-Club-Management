<?php
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Config;
use Carbon\Carbon;
use App\Http\Models\Course;

class BeaconSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker\Factory::create();
        $faker->addProvider(new Faker\Provider\Uuid($faker));
        $faker->addProvider(new Faker\Provider\Lorem($faker));
        $faker->addProvider(new Faker\Provider\Base($faker));
        $clubList = Course::select('id')->orderBy("id", 'ASC')
            ->get()
            ->toArray();
        for ($i = 0; $i < 5; $i ++) {
            $status = $faker->randomElement(['INACTIVE','ACTIVE']);
            $randomCourse = $faker->randomElement($clubList);
            DB::table('beacon')->insert([
                'club_id' => 1,
                'course_id' => $randomCourse['id'],
                'name' => $faker->sentence(2, true),
                'UUID' => $faker->uuid,
                'major' => $faker->numberBetween(1000, 10000),
                'minor' => $faker->numberBetween(1000, 10000),
                'status' => $status
            ]);
        }
        DB::table('beacon')->insert([
            'club_id' => 1,
            'course_id' => 1,
            'name' => 'Original Beacon',
            'UUID' => 'b9407f30-f5f8-466e-aff9-25556b57fe6d',
            'major' => '25492',
            'minor' => '63993',
            'status' => 'ACTIVE',
        ]);
    }
}
