<?php

namespace Database\Seeders;

use App\Models\Shop;
use App\Models\Transition;
use App\Models\User;
use Carbon\Carbon;
use Faker\Factory;
use Illuminate\Database\Seeder;

class ShopSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Factory::create();

        for ($i = 0; $i <20; $i++) {

            Shop::create([
                'name' => $faker->name,
                'win_tokens' => random_int(100, 9999),
                'cost' => random_int(100, 99999999),
                'code' => $faker->firstName . random_int(1000, 9999),
                'start_time' => Carbon::now(),
                'end_time' => Carbon::now()->addDays($i+60),
                'active' => random_int(0, 1),
                'image' => null,
            ]);

        }

        Shop::create([
            'name' => $faker->name,
            'win_tokens' => 10,
            'cost' => 0,
            'code' => $faker->firstName . random_int(1000, 9999),
            'start_time' => Carbon::now(),
            'end_time' => Carbon::now()->addDays(60),
            'active' => 1,
            'free' => 1,
            'image' => 'images/icon/free_gift.png',
        ]);
    }
}
