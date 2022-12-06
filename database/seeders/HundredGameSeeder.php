<?php

namespace Database\Seeders;


use App\Models\HundredGame;
use App\Models\Price;
use Carbon\Carbon;
use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HundredGameSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Factory::create();
        DB::beginTransaction();
        try{
            for ($i = 0; $i <50; $i++) {
                // Game data
                $no_of_win_numbers = random_int(1, 9);
                $win_numbers = array();
                for ($k = 0; $k < $no_of_win_numbers; $k++) {
                    $win_numbers[$k] = random_int(1, 100);
                }

                $game = HundredGame::create([
                    'no_of_win_numbers'=> $no_of_win_numbers,
                    'win_numbers'=> $win_numbers,
                    'timer' => $faker->dateTimeBetween('00:01:00', '00:59:59'),
                    'start' => Carbon::now()->addDays($i),
                    'end' => Carbon::now()->addDays($i+1),

                ]);

                // Price data
                Price::create([
                    'game_id' => $game->id,
                    'game_type' => 'hundred',
                    'name' => $faker->userName,
                    'description' => $faker->paragraph,
                    'value' => random_int(100, 90000),
                    'code' => $faker->firstName . random_int(1000, 9999),
                    'start_time' => $game->start,
                    'end_time' => $game->end,
                    'basic' => 1,
                ]);
                DB::commit(); // insert data
            }
        }catch (\Exception $e){
            DB::rollback();
        }
    }
}
