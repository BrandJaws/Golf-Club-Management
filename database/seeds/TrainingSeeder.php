<?php
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Config;
use App\Http\Models\Club;
use Carbon\Carbon;
use App\Http\Models\Coach;

class TrainingSeeder extends Seeder
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
        $faker->addProvider(new Faker\Provider\DateTime($faker));
        $clubList = Club::select('id')->orderBy("id", 'ASC')
            ->get()
            ->toArray();
        $coachList = Coach::select('id')->orderBy("id", 'ASC')
            ->get()
            ->toArray();
        for ($i = 0; $i < 10; $i ++) {
            $randomClub = $faker->randomElement($clubList);
            $randomCoach = $faker->randomElement($clubList);
            $randomCoach = $faker->randomElement($clubList);
            DB::table('training')->insert([
                'club_id' => $randomClub['id'],
                'coach_id' => $randomCoach['id'],
                'name' => $faker->sentence(2, true),
                'description' => $faker->paragraph(3, true),
                'date' => $faker->date('Y-m-d', 'now'),
                'seats' => $faker->randomDigitNotNull,
                'promotionType' => \Config('global.contentType.video'),
                'promotionContent' => 'https://youtu.be/6v2L2UGZJAM'
            ]);
        }
    }
}
