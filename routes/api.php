<?php

use App\Http\Controllers\Api\Player\CartController;
use App\Http\Controllers\Api\Player\CategoryController;
use App\Http\Controllers\Api\Player\GeneralController;
use App\Http\Controllers\Api\Player\HundredGameApiController;
use App\Http\Controllers\Api\Player\InvoiceController;
use App\Http\Controllers\Api\Player\LoseNumberGameApiController;
use App\Http\Controllers\Api\Player\NineGameApiController;
use App\Http\Controllers\Api\Player\OrderController;
use App\Http\Controllers\Api\Player\ProductController;
use App\Http\Controllers\Api\Player\ProductReviewController;
use App\Http\Controllers\Api\Player\ProductUnitController;
use App\Http\Controllers\Api\Player\AuthController;
use App\Http\Controllers\Api\Player\ProfileController;
use App\Http\Controllers\Api\Player\WishListController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('player-login', [AuthController::class, 'playerLogin']);
Route::post('create-player-account', [AuthController::class, 'createPlayerAccount']);
Route::post('phone-verify',[AuthController::class, 'phoneVerify']);
Route::post('forgot-password', [AuthController::class,'forgotPassword']);
Route::post('reset-password', [AuthController::class,'resetPassword']);


Route::get('/app-start-pages', [GeneralController::class, 'appStartPages']);

Route::get('/get-about-us', [GeneralController::class, 'getAboutUs']);
Route::get('/get-privacy', [GeneralController::class, 'getPrivacy']);
Route::get('/get-rules', [GeneralController::class, 'getRule']);



Route::group(['middleware' => 'auth:sanctum'], function () {

    /* Player login */
    Route::get('player-login-data', [AuthController::class, 'playerLoginData']);
    Route::delete('user-token-logout', [AuthController::class, 'destroy']);
    Route::post('update-image', [AuthController::class, 'updateImage']);
    Route::post('update-player-info', [AuthController::class, 'updatePlayerInfo']);



    /* General */
    Route::get('/get-phones', [GeneralController::class, 'getPhones']);
    Route::get('/get-socialMedia', [GeneralController::class, 'getSocialMedia']);
    Route::get('/get-emails', [GeneralController::class, 'getEmails']);
    Route::post('/send-contact-message', [GeneralController::class, 'sendContactMessage']);
    /* Home */
    Route::group(['prefix' => 'home', 'as'=>'home.' ], function(){
        Route::get('/latest-win-game', [GeneralController::class, 'latestWinGame']);
        Route::get('/price-details', [GeneralController::class, 'priceDetails']);
        Route::get('/my-notifications', [GeneralController::class, 'myNotifications']);
        Route::get('/read-notification', [GeneralController::class, 'readNotification']);
        Route::get('/shops', [GeneralController::class, 'shops']);
        Route::get('/shop-details', [GeneralController::class, 'shopDetails']);
    });


    /* A Hundred Game */
    Route::group(['prefix' => 'hundred-game', 'as'=>'hundred-game.' ], function(){
        Route::get('/current-price', [HundredGameApiController::class, 'currentPrice']);
        Route::get('/current-hundred-game', [HundredGameApiController::class, 'currentHundredGame']);
        Route::get('/hundred-game-details', [HundredGameApiController::class, 'hundredGameDetails']);
        Route::post('/start-hundred-game', [HundredGameApiController::class, 'startHundredGame']);
        Route::post('/play-hundred-game', [HundredGameApiController::class, 'playHundredGame']);
        Route::post('/get-voting', [HundredGameApiController::class, 'getVoting']);
    });

    /*  Nine Game */
    Route::group(['prefix' => 'nine-game', 'as'=>'nine-game.' ], function(){
        Route::get('/current-price', [NineGameApiController::class, 'currentPrice']);
        Route::get('/current-nine-game', [NineGameApiController::class, 'currentNineGame']);
        Route::get('/nine-game-details', [NineGameApiController::class, 'nineGameDetails']);
        Route::post('/start-nine-game', [NineGameApiController::class, 'startNineGame']);
        Route::post('/play-nine-game', [NineGameApiController::class, 'playNineGame']);
        Route::post('/get-voting', [NineGameApiController::class, 'getVoting']);
    });

    /*  LoseNumber Game */
    Route::group(['prefix' => 'lose-number-game', 'as'=>'lose-number-game.' ], function(){
        Route::get('/current-price', [LoseNumberGameApiController::class, 'currentPrice']);
        Route::get('/current-lose-number-game', [LoseNumberGameApiController::class, 'currentLoseNumberGame']);
        Route::get('/lose-number-game-details', [LoseNumberGameApiController::class, 'loseNumberGameDetails']);
        Route::post('/start-lose-number-game', [LoseNumberGameApiController::class, 'startLoseNumberGame']);
        Route::post('/play-lose-number-game', [LoseNumberGameApiController::class, 'playLoseNumberGame']);
        Route::post('/get-voting', [LoseNumberGameApiController::class, 'getVoting']);
    });


    /*  LoseNumber Game */
    Route::group(['prefix' => 'profile', 'as'=>'profile.' ], function(){
        Route::get('/my-latest-price', [ProfileController::class, 'myLatestPrice']);
        Route::get('/my-prices-table', [ProfileController::class, 'myPricesTable']);
        Route::post('/update-profile', [ProfileController::class, 'updateProfile']);
    });
});


