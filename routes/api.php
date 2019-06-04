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

Route::get('/gousto/recipe/{id}', 'RecipeController@getRecipe')->where(['id' => '[0-9]+'])->name('get-recipe');
Route::get('/gousto/recipes', 'RecipeController@getAll')->name('get-all-recipes');
Route::post('/gousto/recipe/rate/{id}', 'RecipeController@rateRecipe')->where(['id' => '[0-9]+'])->name('rate-recipe');
Route::post('/gousto/recipe/store', 'RecipeController@storeRecipe')->name('store-recipe');
Route::put('/gousto/recipe/update', 'RecipeController@updateRecipe')->name('update-recipe');
