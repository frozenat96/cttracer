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

Route::group(['middleware' => ['auth']], function() {
    Route::get('/', 'PagesController@index');
});

Route::get('login/{provider}', 'Auth\LoginController@redirectToProvider');
Route::get('login/{provider}/callback', 'Auth\LoginController@handleProviderCallback');
Route::get('logout', '\App\Http\Controllers\Auth\LoginController@logout');
/*
Route::get('/hello', function () {
    // return view('welcome');
    return 'Hello World';
 });

 Route::get('/users/{id}',function($id){
    return 'This is user ' . $id;
});
 */




Auth::routes();


