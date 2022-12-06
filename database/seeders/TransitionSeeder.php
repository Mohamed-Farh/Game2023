<?php

namespace Database\Seeders;

use App\Models\Transition;
use App\Models\User;
use Illuminate\Database\Seeder;

class TransitionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $player = User::whereHas('roles', function($query){
            $query->where('name', 'player');
        })->inRandomOrder()->first();

        for ($i = 0; $i <100; $i++) {
            $sender = User::whereHas('roles', function($query){
                                $query->where('name', 'player');
                            })->inRandomOrder()->first()->id;

            $receiver = User::where('id', '!=', $sender)->whereHas('roles', function($query){
                                $query->where('name', 'player');
                            })->inRandomOrder()->first()->id;

            Transition::create([
                'sender_id'     => $sender,
                'receiver_id'   =>  $receiver,
                'amount'        => random_int(100000, 999999),
            ]);
        }
    }
}
