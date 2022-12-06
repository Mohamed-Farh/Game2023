<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        $this->call(WorldSeeder::class);
        $this->call(WorldStatusSeeder::class);
        $this->call(EntrustSeeder::class);
//        $this->call(CategorySeeder::class);
        $this->call(AppStartPageSeeder::class);
//        $this->call(TagSeeder::class);
//        $this->call(UnitSeeder::class);
//        $this->call(ProductSeeder::class);
//        $this->call(ProductsTagsSeeder::class);
//        $this->call(ProductsImagesSeeder::class);
//        $this->call(ProductCouponSeeder::class);
//        $this->call(ProductReviewSeeder::class);
        $this->call(SocialMediaSeeder::class);
        $this->call(TransitionSeeder::class);
        $this->call(InformationSeeder::class);
        $this->call(AboutSeeder::class);


        $this->call(HundredGameSeeder::class);
        $this->call(NineGameSeeder::class);
        $this->call(LoseNumberGameSeeder::class);

    }
}
