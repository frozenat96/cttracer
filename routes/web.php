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
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;

Route::group(['middleware' => ['auth']], function() {
    Route::get('/', [
        'uses'=>'PagesController@index',
    ]
    );

    Route::resource('/accounts', 'AccountController')->parameters([
        'rol' => 'admin_user'
    ]);

    Route::any('/acc-search-results', [
        'uses'=>'AccountController@search'
    ]
    );

    Route::resource('/groups', 'GroupController')->parameters([
        'rol' => 'admin_user'
    ]);

    Route::any('/group-search-results', [
        'uses'=>'GroupController@search'
    ]
    );

    Route::resource('/advised-groups', 'AdvisedGroupsController')->parameters([
        'rol' => 'admin_user'
    ]);

    Route::any('/advised-groups-search-results', [
        'uses'=>'AdvisedGroupsController@search'
    ]
    ); 

    Route::any('/contentAdvAppForSched', [
        'uses'=>'AdvisedGroupsController@contentAdvAppForSched',
        'as' => 'ContentAdvAppForSched'
    ]
    ); 

    Route::any('/contentAdvCorrectForSched', [
        'uses'=>'AdvisedGroupsController@ContentAdvCorrectForSched',
        'as' => 'ContentAdvCorrectForSched'
    ]
    ); 

    Route::resource('/approve-schedules', 'SchedAppController')->parameters([
        'rol' => 'admin_user'
    ]);

    Route::any('/approve-schedules-search-results', [
        'uses'=>'SchedAppController@search'
    ]
    ); 

    Route::any('/approveStatus', [
        'uses'=>'SchedAppController@approvalStatus',
        'as' => 'approve-status'
    ]
    ); 


    Route::resource('/my-project', 'MyProjController')->parameters([
        'rol' => 'admin_user'
    ]);

    Route::resource('/project-archive', 'ProjSearchController')->parameters([
        'rol' => 'admin_user'
    ]);
    
    Route::any('/proj-archive-search-results', [
        'uses'=>'ProjSearchController@search'
    ]
    ); 

    Route::resource('/projects', 'ProjectController')->parameters([
        'rol' => 'admin_user'
    ]);
    
    Route::any('/proj-search-results', [
        'uses'=>'ProjectController@search'
    ]
    ); 

    Route::resource('/approve-projects', 'ProjAppController')->parameters([
        'rol' => 'admin_user'
    ]);

    Route::any('/app-proj-search-results', [
        'uses'=>'ProjAppController@search'
    ]
    ); 

   
    Route::resource('/quick-view', 'QuickViewController')->parameters([
        'rol' => 'admin_user'
    ]);

    Route::any('/quick-view-search-results', [
        'uses'=>'QuickViewController@search',
    ]
    ); 

    Route::any('/modifyProjApp/{id}', [
        'uses'=>'QuickViewController@modifyProjApp',
        'as'=> 'modifyProjApp'
    ]
    );

    Route::any('/modifyProjAppUpdate', [
        'uses'=>'QuickViewController@modifyProjAppUpdate',
        'as'=> 'modifyProjAppUpdate'
    ]
    );

    Route::get('/search-groups', [
        'uses'=>'PagesController@searchGroupIndex',
    ]
    );

    Route::resource('/stage-settings', 'StageController')->parameters([
        'rol' => 'admin_user'
    ]);

    Route::any('/stage-search-results', [
        'uses'=>'StageController@search'
    ]
    );

    Route::get('/transfer-role-index', [
        'uses'=>'PagesController@transferRole',
    ]
    );

    Route::any('/transfer-role-results', [
        'uses'=>'AccountController@transfer'
    ]
    ); 
    
    Route::post('/searchProjects','ProjSearchController@search')->name('searchProj');
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

 Route::resource('/schedule-settings', 'SchedSettingController')->parameters([
        'rol' => 'admin_user'
    ]);

    Route::any('/schedule-search-results', [
        'uses'=>'SchedSettingController@search',
    ]
    );

 */




Auth::routes();


