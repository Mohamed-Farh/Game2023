<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\UserMaxLimit;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Role;
use App\Models\User;
use Carbon\Carbon;
use Faker\Factory;

class EntrustSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Factory::create();


        $superAdminRole     = Role::create(['name' => 'superAdmin',  'display_name' => 'Administrator',  'description' => 'System Administrator', 'allowed_route' => 'admin']);
        $adminRole          = Role::create(['name' => 'admin',       'display_name' => 'admin',          'description' => 'System Admin',         'allowed_route' => 'admin']);
        $userRole           = Role::create(['name' => 'user',        'display_name' => 'User',           'description' => 'System User',          'allowed_route' => 'admin']);
        $playerRole         = Role::create(['name' => 'player',      'display_name' => 'Player',         'description' => 'Website Player',       'allowed_route' => null   ]);

        $superAdmin = User::create([
            'first_name' => 'Super',
            'last_name' => 'Admin',
            'username' => 'System Administrator',
            'email' => 'superAdmin@superAdmin.com',
            'email_verified_at' => Carbon::now(),
            'mobile' => '01234567890',
            'mobile_verify' => 'true',
            'password' => bcrypt('password'),
            'user_image'=>'',
            'active'=> 1,
            'remember_token' => Str::random(10),
        ]);
        $superAdmin->attachRole($superAdminRole);

        $superAdmin = User::create([
            'first_name' => 'Game',
            'last_name' => 'Admin',
            'username' => 'Game System Administrator',
            'email' => 'info@game.com',
            'mobile' => '01236667890',
            'email_verified_at' => Carbon::now(),
            'mobile_verify' => 'true',
            'password' => bcrypt('password'),
            'user_image'=>'',
            'active'=> 1,
            'remember_token' => Str::random(10),
        ]);
        $superAdmin->attachRole($superAdminRole);


        $admin = User::create([
            'first_name' => 'Admin',
            'last_name' => 'System',
            'username' => 'System Admin',
            'email' => 'admin@admin.com',
            'mobile' => '01234567880',
            'email_verified_at' => Carbon::now(),
            'mobile_verify' => 'true',
            'password' => bcrypt('password'),
            'user_image'=>'',
            'active'=> 1,
            'remember_token' => Str::random(10),
        ]);
        $admin->attachRole($adminRole);


        $user = User::create([
            'first_name' => 'User',
            'last_name' => 'System',
            'username' => 'System User',
            'email' => 'user@user.com',
            'mobile' => '01234567800',
            'password' => bcrypt('password'),
            'email_verified_at' => Carbon::now(),
            'mobile_verify' => 'true',
            'user_image'=>'',
            'active'=> 1,
            'remember_token' => Str::random(10),
        ]);
        $user->attachRole($userRole);

        $user1 = User::create(['first_name' => 'Mohamed',   'last_name' => 'Farh',      'username' => 'Mohamed Farh',       'email' => 'mohamed@yahoo.com',         'mobile' => '01234567799',  'mobile_verify'=> 1, 'password' => bcrypt('password'),  'email_verified_at' => Carbon::now(),   'user_image'=>'', 'remember_token' => Str::random(10), 'token_amount' => random_int(1000, 999999) ]);
        $user1->attachRole($playerRole);

        $user2 = User::create([ 'first_name' => 'Player',   'last_name' => 'Player',    'username' => 'Player Player',      'email' => 'player@player.com',         'mobile' => '01234567999',  'mobile_verify'=> 1, 'password' => bcrypt('password'),  'email_verified_at' => Carbon::now(),   'user_image'=>'', 'remember_token' => Str::random(10), 'token_amount' => random_int(1000, 999999) ]);
        $user2->attachRole($playerRole);

        for ($i = 0; $i <10; $i++) {
            $user_player = User::create([
                'first_name' => $faker->firstName,
                'last_name' => $faker->lastName,
                'username' => $faker->userName,
                'email' => $faker->email,
                'mobile' => '9665' . random_int(10000000, 99999999),
                'email_verified_at' => Carbon::now(),
                'mobile_verify' => 'true',
                'password' => bcrypt('password'),
                'user_image'=>'',
                'active'=> 1,
                'remember_token' => Str::random(10),
                'token_amount' => random_int(1000, 999999)
            ]);
            $user_player->attachRole($playerRole);
        }





        // MAIN
        $manageMain = Permission::create([
            'name' => 'main',
            'display_name' => 'الرئيسية',
            'description' => 'Administrator Dashboard',
            'route' => 'index',
            'module' => 'index',
            'as' => 'index',
            'icon' => 'fa fa-home text-blue',
            'parent' => '0',
            'parent_original' => '0',
            'sidebar_link' => '1',
            'appear' => '1',
            'ordering' => '1',
        ]);
        $manageMain->parent_show = $manageMain->id;
        $manageMain->save();


        // Admins
        $manageAdmins = Permission::create([ 'name' => 'manage_admins', 'display_name' => 'الأدمن', 'route' => 'admins.index', 'module' => 'admins', 'as' => 'admins.index', 'icon' => 'fas fa-user-shield text-blue', 'parent' => '0', 'parent_original' => '0','sidebar_link' => '0', 'appear' => '1', 'ordering' => '200', ]);
        $manageAdmins->parent_show = $manageAdmins->id;
        $manageAdmins->save();
        $showAdmins    = Permission::create([ 'name' => 'show_admins',          'display_name' => 'الأدمن',              'route' => 'admins.index',          'module' => 'admins', 'as' => 'admins.index',       'icon' => 'fas fa-user-shield text-blue',  'parent' => $manageAdmins->id, 'parent_show' => $manageAdmins->id, 'parent_original' => $manageAdmins->id,'sidebar_link' => '0', 'appear' => '1', ]);
        $createAdmins  = Permission::create([ 'name' => 'create_admins',        'display_name' => 'انشاء ادمن',       'route' => 'admins.create',         'module' => 'admins', 'as' => 'admins.create',      'icon' => null,                  'parent' => $manageAdmins->id, 'parent_show' => $manageAdmins->id, 'parent_original' => $manageAdmins->id,'sidebar_link' => '0', 'appear' => '0', ]);
        $updateAdmins  = Permission::create([ 'name' => 'update_admins',        'display_name' => 'تعديل ادمن',       'route' => 'admins.edit',           'module' => 'admins', 'as' => 'admins.edit',        'icon' => null,                  'parent' => $manageAdmins->id, 'parent_show' => $manageAdmins->id, 'parent_original' => $manageAdmins->id,'sidebar_link' => '0', 'appear' => '0', ]);
        $destroyAdmins = Permission::create([ 'name' => 'delete_admins',        'display_name' => 'حذف ادمن',       'route' => 'admins.destroy',        'module' => 'admins', 'as' => 'admins.destroy',     'icon' => null,                  'parent' => $manageAdmins->id, 'parent_show' => $manageAdmins->id, 'parent_original' => $manageAdmins->id,'sidebar_link' => '0', 'appear' => '0', ]);

        // Users
        $manageUsers = Permission::create([ 'name' => 'manage_users', 'display_name' => 'المستخدمين', 'route' => 'users.index', 'module' => 'users', 'as' => 'users.index', 'icon' => 'fas fa-users text-blue', 'parent' => '0', 'parent_original' => '0','sidebar_link' => '0', 'appear' => '1', 'ordering' => '210', ]);
        $manageUsers->parent_show = $manageUsers->id;
        $manageUsers->save();
        $showUsers    = Permission::create([ 'name' => 'show_users',          'display_name' => 'المستخدمين',              'route' => 'users.index',          'module' => 'users', 'as' => 'users.index',       'icon' => 'fas fa-users text-blue',        'parent' => $manageUsers->id, 'parent_show' => $manageUsers->id, 'parent_original' => $manageUsers->id,'sidebar_link' => '0', 'appear' => '1', ]);
        $createUsers  = Permission::create([ 'name' => 'create_users',        'display_name' => 'انشاء مستخدم',       'route' => 'users.create',         'module' => 'users', 'as' => 'users.create',      'icon' => null,                  'parent' => $manageUsers->id, 'parent_show' => $manageUsers->id, 'parent_original' => $manageUsers->id,'sidebar_link' => '0', 'appear' => '0', ]);
        $updateUsers  = Permission::create([ 'name' => 'update_users',        'display_name' => 'تعديل مستخدم',       'route' => 'users.edit',           'module' => 'users', 'as' => 'users.edit',        'icon' => null,                  'parent' => $manageUsers->id, 'parent_show' => $manageUsers->id, 'parent_original' => $manageUsers->id,'sidebar_link' => '0', 'appear' => '0', ]);
        $destroyUsers = Permission::create([ 'name' => 'delete_users',        'display_name' => 'حذف مستخدم',       'route' => 'users.destroy',        'module' => 'users', 'as' => 'users.destroy',     'icon' => null,                  'parent' => $manageUsers->id, 'parent_show' => $manageUsers->id, 'parent_original' => $manageUsers->id,'sidebar_link' => '0', 'appear' => '0', ]);



        //
        $manageHundredGame = Permission::create([ 'name' => 'manage_hundred_games', 'display_name' => 'لعبة (100) رقم', 'route' => 'hundred-games.index', 'module' => 'hundred-games', 'as' => 'hundred-games.index', 'icon' => 'fas fa-gamepad text-blue', 'parent' => '0', 'parent_original' => '0','sidebar_link' => '1', 'appear' => '1', 'ordering' => '2', ]);
        $manageHundredGame->parent_show = $manageHundredGame->id;
        $manageHundredGame->save();
        $showHundredGame    = Permission::create([ 'name' => 'show_hundred_games',          'display_name' => 'لعبة (100) رقم',           'route' => 'hundred-games.index',          'module' => 'hundred-games',    'as' => 'hundred-games.index',       'icon' => 'fas fa-gamepad',      'parent' => $manageHundredGame->id, 'parent_show' => $manageHundredGame->id, 'parent_original' => $manageHundredGame->id,'sidebar_link' => '1', 'appear' => '1', ]);
        $createHundredGame  = Permission::create([ 'name' => 'create_hundred_games',        'display_name' => 'انشاء لعبة (100) رقم',     'route' => 'hundred-games.create',         'module' => 'hundred-games',    'as' => 'hundred-games.create',      'icon' => null,                  'parent' => $manageHundredGame->id, 'parent_show' => $manageHundredGame->id, 'parent_original' => $manageHundredGame->id,'sidebar_link' => '1', 'appear' => '0', ]);
        $updateHundredGame  = Permission::create([ 'name' => 'update_hundred_games',        'display_name' => 'تعديل لعبة (100) رقم',     'route' => 'hundred-games.edit',           'module' => 'hundred-games',    'as' => 'hundred-games.edit',        'icon' => null,                  'parent' => $manageHundredGame->id, 'parent_show' => $manageHundredGame->id, 'parent_original' => $manageHundredGame->id,'sidebar_link' => '1', 'appear' => '0', ]);
        $destroyHundredGame = Permission::create([ 'name' => 'delete_hundred_games',        'display_name' => 'حذف لعبة (100) رقم',       'route' => 'hundred-games.destroy',        'module' => 'hundred-games',    'as' => 'hundred-games.destroy',     'icon' => null,                  'parent' => $manageHundredGame->id, 'parent_show' => $manageHundredGame->id, 'parent_original' => $manageHundredGame->id,'sidebar_link' => '1', 'appear' => '0', ]);

        //
        $manageNineGame = Permission::create([ 'name' => 'manage_nine_games', 'display_name' => 'لعبة (9) رقم', 'route' => 'nine-games.index', 'module' => 'nine-games', 'as' => 'nine-games.index', 'icon' => 'fas fa-gamepad text-blue', 'parent' => '0', 'parent_original' => '0','sidebar_link' => '1', 'appear' => '1', 'ordering' => '10', ]);
        $manageNineGame->parent_show = $manageNineGame->id;
        $manageNineGame->save();
        $showNineGame    = Permission::create([ 'name' => 'show_nine_games',          'display_name' => 'لعبة (9) رقم',           'route' => 'nine-games.index',          'module' => 'nine-games',    'as' => 'nine-games.index',       'icon' => 'fas fa-gamepad',      'parent' => $manageNineGame->id, 'parent_show' => $manageNineGame->id, 'parent_original' => $manageNineGame->id,'sidebar_link' => '1', 'appear' => '1', ]);
        $createNineGame  = Permission::create([ 'name' => 'create_nine_games',        'display_name' => 'انشاء لعبة (9) رقم',     'route' => 'nine-games.create',         'module' => 'nine-games',    'as' => 'nine-games.create',      'icon' => null,                  'parent' => $manageNineGame->id, 'parent_show' => $manageNineGame->id, 'parent_original' => $manageNineGame->id,'sidebar_link' => '1', 'appear' => '0', ]);
        $updateNineGame  = Permission::create([ 'name' => 'update_nine_games',        'display_name' => 'تعديل لعبة (9) رقم',     'route' => 'nine-games.edit',           'module' => 'nine-games',    'as' => 'nine-games.edit',        'icon' => null,                  'parent' => $manageNineGame->id, 'parent_show' => $manageNineGame->id, 'parent_original' => $manageNineGame->id,'sidebar_link' => '1', 'appear' => '0', ]);
        $destroyNineGame = Permission::create([ 'name' => 'delete_eight_games',        'display_name' => 'حذف لعبة (9) رقم',       'route' => 'eight-games.destroy',        'module' => 'eight-games',    'as' => 'eight-games.destroy',     'icon' => null,                  'parent' => $manageNineGame->id, 'parent_show' => $manageNineGame->id, 'parent_original' => $manageNineGame->id,'sidebar_link' => '1', 'appear' => '0', ]);

        //
        $manageLoseNumberGame = Permission::create([ 'name' => 'manage_lose_number_games', 'display_name' => 'لعبة (1) رقم', 'route' => 'lose-number-games.index', 'module' => 'lose-number-games', 'as' => 'lose-number-games.index', 'icon' => 'fas fa-gamepad text-blue', 'parent' => '0', 'parent_original' => '0','sidebar_link' => '1', 'appear' => '1', 'ordering' => '20', ]);
        $manageLoseNumberGame->parent_show = $manageLoseNumberGame->id;
        $manageLoseNumberGame->save();
        $showLoseNumberGame    = Permission::create([ 'name' => 'show_lose_number_games',          'display_name' => 'لعبة (1) رقم',           'route' => 'lose-number-games.index',          'module' => 'lose-number-games',    'as' => 'lose-number-games.index',       'icon' => 'fas fa-gamepad',      'parent' => $manageLoseNumberGame->id, 'parent_show' => $manageLoseNumberGame->id, 'parent_original' => $manageLoseNumberGame->id,'sidebar_link' => '1', 'appear' => '1', ]);
        $createLoseNumberGame  = Permission::create([ 'name' => 'create_lose_number_games',        'display_name' => 'انشاء لعبة (1) رقم',     'route' => 'lose-number-games.create',         'module' => 'lose-number-games',    'as' => 'lose-number-games.create',      'icon' => null,                  'parent' => $manageLoseNumberGame->id, 'parent_show' => $manageLoseNumberGame->id, 'parent_original' => $manageLoseNumberGame->id,'sidebar_link' => '1', 'appear' => '0', ]);
        $updateLoseNumberGame  = Permission::create([ 'name' => 'update_lose_number_games',        'display_name' => 'تعديل لعبة (1) رقم',     'route' => 'lose-number-games.edit',           'module' => 'lose-number-games',    'as' => 'lose-number-games.edit',        'icon' => null,                  'parent' => $manageLoseNumberGame->id, 'parent_show' => $manageLoseNumberGame->id, 'parent_original' => $manageLoseNumberGame->id,'sidebar_link' => '1', 'appear' => '0', ]);
        $destroyLoseNumberGame = Permission::create([ 'name' => 'delete_lose_number_games',        'display_name' => 'حذف لعبة (1) رقم',       'route' => 'lose-number-games.destroy',        'module' => 'lose-number-games',    'as' => 'lose-number-games.destroy',     'icon' => null,                  'parent' => $manageLoseNumberGame->id, 'parent_show' => $manageLoseNumberGame->id, 'parent_original' => $manageLoseNumberGame->id,'sidebar_link' => '1', 'appear' => '0', ]);


        //Players
        $managePlayers = Permission::create([ 'name' => 'manage_players', 'display_name' => 'اللاعبين', 'route' => 'players.index', 'module' => 'players', 'as' => 'players.index', 'icon' => 'fas fa-user text-blue', 'parent' => '0', 'parent_original' => '0','sidebar_link' => '1', 'appear' => '1', 'ordering' => '35', ]);
        $managePlayers->parent_show = $managePlayers->id;
        $managePlayers->save();
        $showPlayers    = Permission::create([ 'name' => 'show_players',          'display_name' => 'اللاعبين',       'route' => 'players.index',          'module' => 'players', 'as' => 'players.index',       'icon' => 'fas fa-user',         'parent' => $managePlayers->id, 'parent_show' => $managePlayers->id, 'parent_original' => $managePlayers->id,'sidebar_link' => '1', 'appear' => '1', ]);
        $createPlayers  = Permission::create([ 'name' => 'create_players',        'display_name' => 'انشاء لاعب',     'route' => 'players.create',         'module' => 'players', 'as' => 'players.create',      'icon' => null,                  'parent' => $managePlayers->id, 'parent_show' => $managePlayers->id, 'parent_original' => $managePlayers->id,'sidebar_link' => '1', 'appear' => '0', ]);
        $updatePlayers  = Permission::create([ 'name' => 'update_players',        'display_name' => 'تعديل لاعب',     'route' => 'players.edit',           'module' => 'players', 'as' => 'players.edit',        'icon' => null,                  'parent' => $managePlayers->id, 'parent_show' => $managePlayers->id, 'parent_original' => $managePlayers->id,'sidebar_link' => '1', 'appear' => '0', ]);
        $destroyPlayers = Permission::create([ 'name' => 'delete_players',        'display_name' => 'حذف لاعب',       'route' => 'players.destroy',        'module' => 'players', 'as' => 'players.destroy',     'icon' => null,                  'parent' => $managePlayers->id, 'parent_show' => $managePlayers->id, 'parent_original' => $managePlayers->id,'sidebar_link' => '1', 'appear' => '0', ]);
        #####
//        $pendingOrders      = Permission::create([ 'name' => 'show_players_pending_orders',     'display_name' => 'الطلبات المعلقة',        'route' => 'player_orders.pending',        'module' => 'players',     'as' => 'player_orders.pending',     'icon' => 'fas fa-bullhorn',            'parent' => $managePlayers->id, 'parent_show' => $managePlayers->id, 'parent_original' => $managePlayers->id,'sidebar_link' => '1', 'appear' => '1', ]);



//        //Categories
//        $manageCategories = Permission::create([ 'name' => 'manage_categories', 'display_name' => 'أنواع الخدمات (الأقسام)', 'route' => 'categories.index', 'module' => 'categories', 'as' => 'categories.index', 'icon' => 'fas fa-th-large text-blue', 'parent' => '0', 'parent_original' => '0','sidebar_link' => '1', 'appear' => '1', 'ordering' => '5', ]);
//        $manageCategories->parent_show = $manageCategories->id;
//        $manageCategories->save();
//        $showCategories    = Permission::create([ 'name' => 'show_categories',          'display_name' => 'الأقسام',       'route' => 'categories.index',          'module' => 'categories', 'as' => 'categories.index',       'icon' => 'fas fa-th',          'parent' => $manageCategories->id, 'parent_show' => $manageCategories->id, 'parent_original' => $manageCategories->id,'sidebar_link' => '1', 'appear' => '1', ]);


//        //Products
//        $manageProducts = Permission::create([ 'name' => 'manage_products', 'display_name' => 'المنتجات', 'route' => 'products.index', 'module' => 'products', 'as' => 'products.index', 'icon' => 'fas fa-tshirt text-blue', 'parent' => '0', 'parent_original' => '0','sidebar_link' => '1', 'appear' => '1', 'ordering' => '15', ]);
//        $manageProducts->parent_show = $manageProducts->id;
//        $manageProducts->save();
//        $showProducts    = Permission::create([ 'name' => 'show_products',          'display_name' => 'المنتجات',             'route' => 'products.index',          'module' => 'products', 'as' => 'products.index',       'icon' => 'fas fa-tshirt',       'parent' => $manageProducts->id, 'parent_show' => $manageProducts->id, 'parent_original' => $manageProducts->id,'sidebar_link' => '1', 'appear' => '1', ]);
//        $createProducts  = Permission::create([ 'name' => 'create_products',        'display_name' => 'انشاء منتج',      'route' => 'products.create',         'module' => 'products', 'as' => 'products.create',      'icon' => null,                  'parent' => $manageProducts->id, 'parent_show' => $manageProducts->id, 'parent_original' => $manageProducts->id,'sidebar_link' => '1', 'appear' => '0', ]);
//        $updateProducts  = Permission::create([ 'name' => 'update_products',        'display_name' => 'تعديل منتج',      'route' => 'products.edit',           'module' => 'products', 'as' => 'products.edit',        'icon' => null,                  'parent' => $manageProducts->id, 'parent_show' => $manageProducts->id, 'parent_original' => $manageProducts->id,'sidebar_link' => '1', 'appear' => '0', ]);
//        $destroyProducts = Permission::create([ 'name' => 'delete_products',        'display_name' => 'حذف منتج',      'route' => 'products.destroy',        'module' => 'products', 'as' => 'products.destroy',     'icon' => null,                  'parent' => $manageProducts->id, 'parent_show' => $manageProducts->id, 'parent_original' => $manageProducts->id,'sidebar_link' => '1', 'appear' => '0', ]);
//            ## Product Reviews ##
//            $showProductReviews     = Permission::create([ 'name' => 'show_productReviews',     'display_name' => 'تقييمات المنتجات',   'route' => 'productReviews.index',  'module' => 'products', 'as' => 'productReviews.index',     'icon' => 'fas fa-comments',              'parent' => $manageProducts->id, 'parent_show' => $manageProducts->id, 'parent_original' => $manageProducts->id,'sidebar_link' => '1', 'appear' => '1', ]);
//            ## Product Units ##
//            $showunits       = Permission::create([ 'name' => 'show_units',       'display_name' => 'أوزان المنتجات',     'route' => 'units.index',    'module' => 'products', 'as' => 'units.index',       'icon' => 'fas fa-balance-scale-left',    'parent' => $manageProducts->id, 'parent_show' => $manageProducts->id, 'parent_original' => $manageProducts->id,'sidebar_link' => '1', 'appear' => '1', ]);


//        //Order
//        $manageOrders = Permission::create([ 'name' => 'manage_orders', 'display_name' => 'الطلبات', 'route' => 'orders.index', 'module' => 'orders', 'as' => 'orders.index', 'icon' => 'fas fa-shopping-bag text-blue', 'parent' => '0', 'parent_original' => '0','sidebar_link' => '1', 'appear' => '1', 'ordering' => '20', ]);
//        $manageOrders->parent_show = $manageOrders->id;
//        $manageOrders->save();
//        #####
//        $pendingOrders      = Permission::create([ 'name' => 'show_pending_orders',     'display_name' => 'الطلبات المعلقة',        'route' => 'orders.pending',            'module' => 'orders',     'as' => 'orders.pending',     'icon' => 'fas fa-bullhorn',                    'parent' => $manageOrders->id, 'parent_show' => $manageOrders->id, 'parent_original' => $manageOrders->id,'sidebar_link' => '1', 'appear' => '1', ]);
//        #####
//        $AcceptedOrders     = Permission::create([ 'name' => 'show_accepted_orders',    'display_name' => 'الطلبات الموافق عليها',  'route' => 'orders.accepted',           'module' => 'orders',     'as' => 'orders.accepted',    'icon' => 'fas fa-shopping-basket',             'parent' => $manageOrders->id, 'parent_show' => $manageOrders->id, 'parent_original' => $manageOrders->id,'sidebar_link' => '1', 'appear' => '1', ]);
//        #####
//        $CompletedOrders    = Permission::create([ 'name' => 'show_completed_orders',   'display_name' => 'الطلبات المكتملة',       'route' => 'orders.completed',          'module' => 'orders',     'as' => 'orders.completed',   'icon' => 'fas fa-shopping-bag',                'parent' => $manageOrders->id, 'parent_show' => $manageOrders->id, 'parent_original' => $manageOrders->id,'sidebar_link' => '1', 'appear' => '1', ]);
//        #####
//        $pendingInvoices    = Permission::create([ 'name' => 'show_pending_invoices',   'display_name' => 'الفواتير المعلقة',        'route' => 'orders.pendingInvoices',   'module' => 'orders',     'as' => 'orders.pendingInvoices',     'icon' => 'fas fa-clipboard',           'parent' => $manageOrders->id, 'parent_show' => $manageOrders->id, 'parent_original' => $manageOrders->id,'sidebar_link' => '1', 'appear' => '1', ]);
//        #####
//        $CompletedInvoices  = Permission::create([ 'name' => 'show_completed_invoices', 'display_name' => 'الفواتير المكتملة',       'route' => 'orders.completedInvoices', 'module' => 'orders',     'as' => 'orders.completedInvoices',   'icon' => 'fas fa-file-invoice-dollar', 'parent' => $manageOrders->id, 'parent_show' => $manageOrders->id, 'parent_original' => $manageOrders->id,'sidebar_link' => '1', 'appear' => '1', ]);
////        #####
////        $RefusedOrders      = Permission::create([ 'name' => 'show_refused_orders',     'display_name' => 'الطلبات المرفوضة',       'route' => 'orders.refused',        'module' => 'orders',     'as' => 'orders.refused',     'icon' => 'fas fa-cart-arrow-down',     'parent' => $manageOrders->id, 'parent_show' => $manageOrders->id, 'parent_original' => $manageOrders->id,'sidebar_link' => '1', 'appear' => '1', ]);
////        #####
////        $CanclledOrders     = Permission::create([ 'name' => 'show_cancelled_orders',    'display_name' => 'الطلبات الملغاة',        'route' => 'orders.cancelled',      'module' => 'orders',     'as' => 'orders.cancelled',   'icon' => 'fas fa-window-close',        'parent' => $manageOrders->id, 'parent_show' => $manageOrders->id, 'parent_original' => $manageOrders->id,'sidebar_link' => '1', 'appear' => '1', ]);

        //Shop
        $manageShop = Permission::create([ 'name' => 'manage_shop', 'display_name' => 'المتجر', 'route' => 'shops.index', 'module' => 'shops', 'as' => 'shops.index', 'icon' => 'fas fa-gift text-blue', 'parent' => '0', 'parent_original' => '0','sidebar_link' => '1', 'appear' => '1', 'ordering' => '40', ]);
        $manageShop->parent_show = $manageShop->id;
        $manageShop->save();
        $showShop    = Permission::create([ 'name' => 'show_shop',          'display_name' => 'العروض',       'route' => 'shops.index',          'module' => 'shops', 'as' => 'shops.index',       'icon' => 'fas fa-gift',         'parent' => $manageShop->id, 'parent_show' => $manageShop->id, 'parent_original' => $manageShop->id,'sidebar_link' => '1', 'appear' => '1', ]);
        $createShop  = Permission::create([ 'name' => 'create_shop',        'display_name' => 'انشاء عرض',     'route' => 'shops.create',         'module' => 'shops', 'as' => 'shops.create',      'icon' => null,                  'parent' => $manageShop->id, 'parent_show' => $manageShop->id, 'parent_original' => $manageShop->id,'sidebar_link' => '1', 'appear' => '0', ]);
        $updateShop  = Permission::create([ 'name' => 'update_shop',        'display_name' => 'تعديل عرض',     'route' => 'shops.edit',           'module' => 'shops', 'as' => 'shops.edit',        'icon' => null,                  'parent' => $manageShop->id, 'parent_show' => $manageShop->id, 'parent_original' => $manageShop->id,'sidebar_link' => '1', 'appear' => '0', ]);
        $destroyShop = Permission::create([ 'name' => 'delete_shop',        'display_name' => 'حذف عرض',       'route' => 'shops.destroy',        'module' => 'shops', 'as' => 'shops.destroy',     'icon' => null,                  'parent' => $manageShop->id, 'parent_show' => $manageShop->id, 'parent_original' => $manageShop->id,'sidebar_link' => '1', 'appear' => '0', ]);
        #####


        //Countries
        $manageCountries = Permission::create([ 'name' => 'manage_countries', 'display_name' => 'الدول', 'route' => 'countries.index', 'module' => 'countries', 'as' => 'countries.index', 'icon' => 'fas fa-globe text-blue', 'parent' => '0', 'parent_original' => '0','sidebar_link' => '1', 'appear' => '1', 'ordering' => '100', ]);
        $manageCountries->parent_show = $manageCountries->id;
        $manageCountries->save();
        $showCountries    = Permission::create([ 'name' => 'show_countries',          'display_name' => 'الدول',              'route' => 'countries.index',          'module' => 'countries', 'as' => 'countries.index',       'icon' => 'fas fa-globe',        'parent' => $manageCountries->id, 'parent_show' => $manageCountries->id, 'parent_original' => $manageCountries->id,'sidebar_link' => '1', 'appear' => '1', ]);
        //States
        $showStates    = Permission::create([ 'name' => 'show_states',          'display_name' => 'المحافظات',              'route' => 'states.index',          'module' => 'countries', 'as' => 'states.index',       'icon' => 'fas fa-map-marker-alt', 'parent' => $manageCountries->id, 'parent_show' => $manageCountries->id, 'parent_original' => $manageCountries->id,'sidebar_link' => '1', 'appear' => '1', ]);
        //Cities
        $showCities    = Permission::create([ 'name' => 'show_cities',          'display_name' => 'المدن',              'route' => 'cities.index',          'module' => 'countries', 'as' => 'cities.index',       'icon' => 'fas fa-university',   'parent' => $manageCountries->id, 'parent_show' => $manageCountries->id, 'parent_original' => $manageCountries->id,'sidebar_link' => '1', 'appear' => '1', ]);


        //Contact
        $manageContacts = Permission::create([ 'name' => 'manage_contacts', 'display_name' => 'الاتصال', 'route' => 'socials.index', 'module' => 'socials', 'as' => 'socials.index', 'icon' => 'fas fa-mobile-alt text-blue', 'parent' => '0', 'parent_original' => '0','sidebar_link' => '1', 'appear' => '1', 'ordering' => '115', ]);
        $manageContacts->parent_show = $manageContacts->id;
        $manageContacts->save();
            ##Social Media
            $showSocials    = Permission::create([ 'name' => 'show_social', 'display_name' => 'وسائل التواصل الاجتماعي',   'route' => 'socials.index',     'module' => 'socials',     'as' => 'socials.index',    'icon' => 'fas fa-thumbs-up',           'parent' => $manageContacts->id, 'parent_show' => $manageContacts->id, 'parent_original' => $manageContacts->id,'sidebar_link' => '1', 'appear' => '1', ]);
            ##Phone Number
            $showPhones     = Permission::create([ 'name' => 'show_phone',  'display_name' => 'الموبيل',         'route' => 'phones.index',      'module' => 'socials',     'as' => 'phones.index',     'icon' => 'fas fa-phone-square-alt',    'parent' => $manageContacts->id, 'parent_show' => $manageContacts->id, 'parent_original' => $manageContacts->id,'sidebar_link' => '1', 'appear' => '1', ]);
            ##E_Mail
            $showEmails     = Permission::create([ 'name' => 'show_email',  'display_name' => 'البريد الالكتروني',        'route' => 'emails.index',      'module' => 'socials',     'as' => 'emails.index',     'icon' => 'fas fa-envelope-open-text',  'parent' => $manageContacts->id, 'parent_show' => $manageContacts->id, 'parent_original' => $manageContacts->id,'sidebar_link' => '1', 'appear' => '1', ]);

        //
        $manageContactUs = Permission::create([ 'name' => 'manage_contactUs_messages', 'display_name' => 'رسائل (تواصل معنا)', 'route' => 'contact-messages.index', 'module' => 'contact-messages', 'as' => 'contact-messages.index', 'icon' => 'fas fa-sms text-blue', 'parent' => '0', 'parent_original' => '0','sidebar_link' => '1', 'appear' => '1', 'ordering' => '120', ]);
        $manageContactUs->parent_show = $manageContactUs->id;
        $manageContactUs->save();
            $showMessages    = Permission::create([ 'name' => 'show__contactUs_messages', 'display_name' => 'الرسائل',   'route' => 'contact-messages.index',     'module' => 'contact-messages',     'as' => 'contact-messages.index',    'icon' => 'fas fa-sms',           'parent' => $manageContactUs->id, 'parent_show' => $manageContactUs->id, 'parent_original' => $manageContactUs->id,'sidebar_link' => '1', 'appear' => '1', ]);


        //Settings
        $manageSettings = Permission::create([ 'name' => 'manage_settings', 'display_name' => 'الإعدادات', 'route' => 'logos.index', 'module' => 'settings', 'as' => 'logos.index', 'icon' => 'fas fa-cogs text-blue', 'parent' => '0', 'parent_original' => '0','sidebar_link' => '1', 'appear' => '1', 'ordering' => '125', ]);
        $manageSettings->parent_show = $manageSettings->id;
        $manageSettings->save();
//            ##Logo
//            $showLogo           = Permission::create([ 'name' => 'show_logo',           'display_name' => 'لوجو الموقع',    'route' => 'logos.index',           'module' => 'settings',     'as' => 'logos.index',          'icon' => 'fas fa-paint-brush',     'parent' => $manageSettings->id, 'parent_show' => $manageSettings->id, 'parent_original' => $manageSettings->id,'sidebar_link' => '1', 'appear' => '1', ]);

//            ##Pages Titles
//            $showPages          = Permission::create([ 'name' => 'show_page_title',     'display_name' => 'نصوص العناوين',  'route' => 'page-titles.index',     'module' => 'settings',     'as' => 'page-titles.index',    'icon' => 'fas fa-heading',         'parent' => $manageSettings->id, 'parent_show' => $manageSettings->id, 'parent_original' => $manageSettings->id,'sidebar_link' => '1', 'appear' => '1', ]);

//            ##WorkingTimes
//            $showWorkingTimes   = Permission::create([ 'name' => 'show_working_times',  'display_name' => 'ساعات العمل',    'route' => 'working_times.index',   'module' => 'settings',     'as' => 'working_times.index',  'icon' => 'fas fa-clock',           'parent' => $manageSettings->id, 'parent_show' => $manageSettings->id, 'parent_original' => $manageSettings->id,'sidebar_link' => '1', 'appear' => '1', ]);

//            ##Locations
//            $showLocations      = Permission::create([ 'name' => 'show_locations',      'display_name' => 'موقع الشركة',    'route' => 'locations.index',       'module' => 'settings',     'as' => 'locations.index',      'icon' => 'fas fa-map-marker-alt',  'parent' => $manageSettings->id, 'parent_show' => $manageSettings->id, 'parent_original' => $manageSettings->id,'sidebar_link' => '1', 'appear' => '1', ]);

            ##AppStartPage
            $showAppStartPages   = Permission::create([ 'name' => 'show_app_start_pages',    'display_name' => 'صفحات البداية للتطبيق',        'route' => 'appStartPages.index',    'module' => 'settings',     'as' => 'appStartPages.index',   'icon' => 'fas fa-pager',        'parent' => $manageSettings->id, 'parent_show' => $manageSettings->id, 'parent_original' => $manageSettings->id,'sidebar_link' => '1', 'appear' => '1', ]);

            ##Informations
            $showInformations   = Permission::create([ 'name' => 'show_information',    'display_name' => 'معلومات التطبيق',        'route' => 'informations.index',    'module' => 'settings',     'as' => 'informations.index',   'icon' => 'fas fa-info-circle',        'parent' => $manageSettings->id, 'parent_show' => $manageSettings->id, 'parent_original' => $manageSettings->id,'sidebar_link' => '1', 'appear' => '1', ]);














    }
}
