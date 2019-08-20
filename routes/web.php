<?php

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

Route::get('/', ['uses' => 'HomeController@index', 'as' => 'home']);

Route::middleware('auth')->group(function () {
    Route::prefix('account')->name('account.')->group(function () {
        Route::get('/', [
            'uses' => 'AccountController@profile',
            'as' => 'profile',
        ]);
    });
    Route::resource('cards', 'CardController')->only([
        'show', 'index', 'store'
    ]);
    Route::patch('cards/block/{card}', 'CardController@block')
        ->name('cards.block');
    Route::patch('cards/replenish/{card}', 'CardController@replenish')
        ->name('cards.replenish');
    
    Route::resource('transactions', 'TransactionController');
});
