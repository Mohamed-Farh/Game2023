<?php

use App\Http\Controllers\Backend\AboutController;
use App\Http\Controllers\Backend\AdminController;
use App\Http\Controllers\Backend\AppStartPageController;
use App\Http\Controllers\Backend\BackendController;
use App\Http\Controllers\Backend\CityController;
use App\Http\Controllers\Backend\ContactMessageController;
use App\Http\Controllers\Backend\CountryController;
use App\Http\Controllers\Backend\LoseNumberGameController;
use App\Http\Controllers\Backend\LoseNumberGamePriceController;
use App\Http\Controllers\Backend\NineGameController;
use App\Http\Controllers\Backend\HundredGameController;
use App\Http\Controllers\Backend\NineGamePriceController;
use App\Http\Controllers\Backend\PlayerController;
use App\Http\Controllers\Backend\EmailController;
use App\Http\Controllers\Backend\InformationController;
use App\Http\Controllers\Backend\LocationController;
use App\Http\Controllers\Backend\LogoController;
use App\Http\Controllers\Backend\PageTitleController;
use App\Http\Controllers\Backend\PhoneController;
use App\Http\Controllers\Backend\SocialMediaController;
use App\Http\Controllers\Backend\StateController;
use App\Http\Controllers\Backend\UserController;
use App\Http\Controllers\Backend\WorkingTimeController;
use App\Http\Controllers\Backend\PriceController;
use App\Http\Controllers\Backend\TransitionController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Auth::routes();

Route::group(['prefix' => 'admin', 'as'=>'admin.' ], function(){

    Route::group(['middleware' => 'guest' ], function(){


        Route::get('/login',               [BackendController::class, 'login'    ])->name('login');
        Route::get('/forget_password',     [BackendController::class, 'forget_password'])->name('forget_password');
    });


        //==========================================================================================================
        Route::group(['middleware' => ['roles', 'role:superAdmin|admin|user'] ], function(){

            Route::get('/',               [BackendController::class, 'index'    ])->name('index_route');
            Route::get('/index',          [BackendController::class, 'index'    ])->name('index');
            /*  Country - State - City */
            Route::get('/getState',     [BackendController::class, 'get_state'    ])->name('backend.get_state');
            Route::get('/getCity',      [BackendController::class, 'get_city'    ])->name('backend.get_city');



            /*  Admins   */
            Route::resource('admins'    ,AdminController::class);
            Route::post('admins-removeImage', [AdminController::class,'removeImage'])->name('admins.removeImage');
            Route::get('admins-changeStatus', [AdminController::class,'changeStatus'])->name('admins.changeStatus');
            Route::post('admins-destroyAll', [AdminController::class,'massDestroy'])->name('admins.massDestroy');
            /*  Users   */
            Route::resource('users'    ,UserController::class);
            Route::post('users-removeImage', [UserController::class,'removeImage'])->name('users.removeImage');
            Route::get('users-changeStatus', [UserController::class,'changeStatus'])->name('users.changeStatus');
            Route::post('users-destroyAll', [UserController::class,'massDestroy'])->name('users.massDestroy');
            /*-------------------------------- */


            /*-------------------------------- */
            /*  Customers   */
            Route::resource('players',      PlayerController::class);
            Route::get('/getPlayerSearch',            [PlayerController::class, 'getPlayerSearch'])->name('players.getPlayerSearch');
            Route::post('players-removeImage',        [PlayerController::class,'removeImage'])->name('players.removeImage');
            Route::get('players-changeStatus',        [PlayerController::class,'changeStatus'])->name('players.changeStatus');
            Route::post('playersDestroyAll',          [PlayerController::class,'massDestroy'])->name('players.playersDestroyAll');
            /*-------------------------------- */


            /*-------------------------------- */
            //Players
            Route::get('players/{player}/prices', [PlayerController::class,'showPrices'])->name('players.showPrices');
            Route::get('players/{player}/transitions', [PlayerController::class,'showTransitions'])->name('players.showTransitions');
            Route::resource('players', PlayerController::class);
            //Prices
            Route::get('prices-changeStatus', [PriceController::class,'changeStatus'])->name('prices.changeStatus');
            Route::post('prices-destroyAll', [PriceController::class,'massDestroy'])->name('prices.massDestroy');
            Route::post('prices-removeImage',        [PriceController::class,'removeImage'])->name('prices.removeImage');
            Route::resource('prices', PriceController::class);

            //Transition
            Route::get('transitions/export-transitions', [TransitionController::class,'showExportTransitions'])->name('transitions.showExportTransitions');
            Route::get('transitions/import-transitions', [TransitionController::class,'showImportTransitions'])->name('transitions.showImportTransitions');
            Route::resource('transitions', TransitionController::class);
            /*-------------------------------- */


            /*-------------------------------- */
            /*  Hundred Game   */
            Route::get('hundred-games-changeStatus', [HundredGameController::class,'changeStatus'])->name('hundred-games.changeStatus');
            Route::post('hundred-games-destroyAll', [HundredGameController::class,'massDestroy'])->name('hundred-games.massDestroy');
            Route::get('hundred-games/{hundredGame}/prices', [HundredGameController::class,'showPrices'])->name('hundred-games.showPrices');
            Route::resource('hundred-games'    ,HundredGameController::class);
            /*-------------------------------- */


            /*-------------------------------- */
            /*  Nine Game   */
            Route::get('nine-games-changeStatus', [NineGameController::class,'changeStatus'])->name('nine-games.changeStatus');
            Route::post('nine-games-destroyAll', [NineGameController::class,'massDestroy'])->name('nine-games.massDestroy');
            Route::get('nine-games/{nineGame}/prices', [NineGameController::class,'showPrices'])->name('nine-games.showPrices');
            Route::resource('nine-games'    ,NineGameController::class);
            //Prices
            Route::get('nine-games-prices-changeStatus', [NineGamePriceController::class,'changeStatus'])->name('nine-games-prices.changeStatus');
            Route::post('nine-games-prices-destroyAll', [NineGamePriceController::class,'massDestroy'])->name('nine-games-prices.massDestroy');
            Route::post('nine-games-prices-removeImage',        [NineGamePriceController::class,'removeImage'])->name('nine-games-prices.removeImage');
            Route::resource('nine-games-prices', NineGamePriceController::class);

            /*-------------------------------- */


            /*-------------------------------- */
            /*  One Game   */
            Route::get('lose-number-games-changeStatus', [LoseNumberGameController::class,'changeStatus'])->name('lose-number-games.changeStatus');
            Route::post('lose-number-games-destroyAll', [LoseNumberGameController::class,'massDestroy'])->name('lose-number-games.massDestroy');
            Route::get('lose-number-games/{loseNumberGame}/prices', [LoseNumberGameController::class,'showPrices'])->name('lose-number-games.showPrices');
            Route::resource('lose-number-games'    ,LoseNumberGameController::class);
            //Prices
            Route::get('lose-number-games-prices-changeStatus', [LoseNumberGamePriceController::class,'changeStatus'])->name('lose-number-games-prices.changeStatus');
            Route::post('lose-number-games-prices-destroyAll', [LoseNumberGamePriceController::class,'massDestroy'])->name('lose-number-games-prices.massDestroy');
            Route::post('lose-number-games-prices-removeImage',        [LoseNumberGamePriceController::class,'removeImage'])->name('lose-number-games-prices.removeImage');
            Route::resource('lose-number-games-prices', LoseNumberGamePriceController::class);

            /*-------------------------------- */


            /*-------------------------------- */
            /*-------------------------------- */
            /*-------------------------------- */




            // Route::get('/get_customer_customerSearch',   [CustomerSearchController::class, 'index'    ])->name('customers.get_customer');
            // Route::get('/get_state_customerSearch',      [CustomerSearchController::class, 'get_state_customerSearch'    ])->name('customers.get_state_customerSearch');
            // Route::get('/get_city_customerSearch',      [CustomerSearchController::class, 'get_city_customerSearch'    ])->name('customers.get_city_customerSearch');
            // Route::resource('customer_addresses' ,CustomerAddressController::class);

            /*  countries   */
            Route::resource('countries'    ,CountryController::class);
            Route::get('countries-changeStatus', [CountryController::class,'changeStatus'])->name('countries.changeStatus');
            Route::post('countries-destroyAll', [CountryController::class,'massDestroy'])->name('countries.massDestroy');
            /*  states   */
            Route::resource('states'    ,StateController::class);
            Route::get('states-changeStatus', [StateController::class,'changeStatus'])->name('states.changeStatus');
            Route::post('states-destroyAll', [StateController::class,'massDestroy'])->name('states.massDestroy');
            /*  cities   */
            Route::resource('cities'    ,CityController::class);
            Route::get('cities-changeStatus', [CityController::class,'changeStatus'])->name('cities.changeStatus');
            Route::post('cities-destroyAll', [CityController::class,'massDestroy'])->name('cities.massDestroy');


            /*  socials   */
            Route::get('socials-changeStatus', [SocialMediaController::class,'changeStatus'])->name('socials.changeStatus');
            Route::post('socials-destroyAll', [SocialMediaController::class,'massDestroy'])->name('socials.massDestroy');
            Route::resource('socials'    ,SocialMediaController::class);
            /*  phones   */
            Route::resource('phones'    ,PhoneController::class);
            Route::get('phones-changeStatus', [PhoneController::class,'changeStatus'])->name('phones.changeStatus');
            Route::post('phones-destroyAll', [PhoneController::class,'massDestroy'])->name('phones.massDestroy');
            /*  emails   */
            Route::resource('emails'    ,EmailController::class);
            Route::get('emails-changeStatus', [EmailController::class,'changeStatus'])->name('emails.changeStatus');
            Route::post('emails-destroyAll', [EmailController::class,'massDestroy'])->name('emails.massDestroy');


            /*  about   */
            Route::resource('abouts'    ,AboutController::class);
            Route::post('ckeditor/upload', [AboutController::class, 'upload'])->name('ckeditor.upload');


            //socials
            Route::resource('contact-messages',ContactMessageController::class);
            Route::get('contact-messages-changeStatus', [ContactMessageController::class,'changeStatus'])->name('contact-messages.changeStatus');
            Route::post('contact-messages-destroyAll', [ContactMessageController::class,'massDestroy'])->name('contact-messages.massDestroy');


            //Settings
            Route::resource('logos',LogoController::class);
            Route::get('logos-changeStatus', [LogoController::class,'changeStatus'])->name('logos.changeStatus');

            Route::resource('page-titles',PageTitleController::class);
            Route::get('page-titles-changeStatus', [PageTitleController::class,'changeStatus'])->name('page-titles.changeStatus');

            Route::resource('locations',LocationController::class);
            Route::get('locations-changeStatus', [LocationController::class,'changeStatus'])->name('locations.changeStatus');

            Route::resource('working_times',WorkingTimeController::class);
            Route::get('working_times-changeStatus', [WorkingTimeController::class,'changeStatus'])->name('working_times.changeStatus');

            Route::resource('informations',InformationController::class);

            Route::get('appStartPages-changeStatus', [AppStartPageController::class,'changeStatus'])->name('appStartPages.changeStatus');
            Route::post('appStartPages-destroyAll', [AppStartPageController::class,'massDestroy'])->name('appStartPages.massDestroy');
            Route::post('appStartPages-removeImage', [AppStartPageController::class,'removeImage'])->name('appStartPages.removeImage');
            Route::resource('appStartPages', AppStartPageController::class);



        });

});
