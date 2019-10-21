<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

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

Route::post('register', 'UserController@register');
Route::post('login', 'UserController@authenticate');

Route::group(['middleware' => ['auth:api']], function() {

    Route::get('user', 'UserController@getAuthenticatedUser');
    Route::post('logout', 'UserController@logout');

    /*===============================/Products\=======================================*/
    Route::post('product', 'ProductsController@store');
    Route::put('product', 'ProductsController@edit');
    Route::get('products', 'ProductsController@index');
    Route::get('products/search', 'ProductsController@search');
    Route::get('products/paginate', 'ProductsController@ProductsPaginate');
    Route::delete('product', 'ProductsController@delete');

    /*===============================/Categories\=======================================*/
    Route::post('category', 'CategoriesController@store');
    Route::put('category', 'CategoriesController@edit');
    Route::delete('category', 'CategoriesController@delete');
    Route::get('categories', 'CategoriesController@index');
    Route::get('categories/paginate', 'CategoriesController@CategoriesPaginate');

    /*===============================/cash register\=======================================*/
    Route::post('cash/register', 'CashregistersController@store');

    /*===============================/ Invoice \=======================================*/
    Route::post('billing', 'InvoicesController@store');

    /*===============================/Dashboard\=======================================*/
    Route::get('dashboard/summary', 'InvoicesController@summary');
    Route::get('dashboard/salesPerMonth', 'InvoicesController@SalesPerMonth');
    Route::get('dashboard/DailySales', 'InvoicesController@DailySales');

    /*===============================/migration update\=======================================*/
    Route::post('update/database', function (){
        Artisan::call('migrate', [
            '--database' => 'client',
            '--force' => true,
            '--path' => '/database/migrations/client'
        ]);
    });
});
