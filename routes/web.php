<?php

use App\Http\Controllers\Frontend\FrontendController;
use App\Http\Controllers\HomeController;
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
//دا علشان يبغت ايميل تاكيد عند التسجيل
Auth::routes(['verify'=>true]);

Auth::routes();

Route::get('/welcome', function () {
    return view('welcome');
});


Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::get('/',         [FrontendController::class, 'index'   ])->name('frontend.index');















//Reoptimized class loader:
Route::get('/optimize-clear', function() {
    $exitCode = Artisan::call('optimize:clear');
    return '<h1>Reoptimized class loader</h1>';
});
//Make Migrate:
Route::get('/migrate', function() {
    $exitCode = Artisan::call('migrate');
    return '<h1>Database Migration</h1>';
});
//Make Migrate Fresh:
Route::get('/migrate-fresh', function() {
    $exitCode = Artisan::call('migrate:fresh');
    return '<h1>Database Migration Fresh</h1>';
});
//Make Migrate Fresh And Seed:
Route::get('/migrate-fresh-seed', function() {
    $exitCode = Artisan::call('migrate:fresh --seed');
    return '<h1>Database Migration Fresh And Seeding</h1>';
});
