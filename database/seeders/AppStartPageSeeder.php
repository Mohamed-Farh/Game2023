<?php

namespace Database\Seeders;

use App\Models\AppStartPage;
use Illuminate\Database\Seeder;

class AppStartPageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        AppStartPage::create(['text' => 'هذا النص الاول هو مثال لنص يمكن أن يستبدل في نفس المساحة .',     'image' => 'images/appStartPage/1.jpeg',   'status' => true, 'number' => '1' ]);
        AppStartPage::create(['text' => 'هذا النص الثاني هو مثال لنص يمكن أن يستبدل في نفس المساحة .',   'image' => 'images/appStartPage/2.jpeg',    'status' => true, 'number' => '2' ]);
        AppStartPage::create(['text' => 'هذا النص الثالث هو مثال لنص يمكن أن يستبدل في نفس المساحة .',   'image' => 'images/appStartPage/3.jpeg',    'status' => true, 'number' => '3' ]);

    }
}
