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
    Route::get('/', [
        'uses'=>'PagesController@index',
    ]
    );

    Route::get('/add-accounts', [
        'uses'=>'PagesController@addAccounts',
    ]
    );

    Route::get('/add-groups', [
        'uses'=>'PagesController@addGroups',
    ]
    );

    Route::get('/advised-groups', [
        'uses'=>'PagesController@advisedGroups',
    ]
    );

    Route::get('/approve-schedules', [
        'uses'=>'PagesController@approveSchedules',
    ]
    );

    Route::get('/my-project', [
        'uses'=>'ProjController@myProject',
    ]
    );

    Route::get('/project-search', [
        'uses'=>'PagesController@projectSearch',
    ]
    );

    Route::get('/schedule-settings', [
        'uses'=>'PagesController@scheduleSettings',
    ]
    );

    Route::get('/search-groups', [
        'uses'=>'PagesController@searchGroups',
    ]
    );

    Route::get('/stage-settings', [
        'uses'=>'PagesController@stageSettings',
    ]
    );

    Route::get('/transfer-role', [
        'uses'=>'PagesController@transferRole',
    ]
    );
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


