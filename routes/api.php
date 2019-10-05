<?php

use Illuminate\Http\Request;

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

    /*===============================/Categories\=======================================*/
    Route::post('category', 'CategoriesController@store');
    Route::put('category', 'CategoriesController@edit');
    Route::delete('category', 'CategoriesController@delete');
    Route::get('categories', 'CategoriesController@index');
    Route::get('categories/paginate', 'CategoriesController@CategoriesPaginate');

    /*===============================/cash register\=======================================*/
    Route::post('cash/register', 'CashregistersController@store');

});
