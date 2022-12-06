<?php

use App\Http\Controllers\Api\Player\CartController;
use App\Http\Controllers\Api\Player\CategoryController;
use App\Http\Controllers\Api\Player\GeneralController;
use App\Http\Controllers\Api\Player\InvoiceController;
use App\Http\Controllers\Api\Player\OrderController;
use App\Http\Controllers\Api\Player\ProductController;
use App\Http\Controllers\Api\Player\ProductReviewController;
use App\Http\Controllers\Api\Player\ProductUnitController;
use App\Http\Controllers\Api\Customer\CustomerController;
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

Route::post('auth-token', [CustomerController::class, 'store']);
Route::post('create-customer-account', [CustomerController::class, 'createCustomerAccount']);





Route::group(['middleware' => 'auth:sanctum'], function () {

    /* customer login */
    Route::post('update-image', [TokenController::class, 'updateImage']);


});


