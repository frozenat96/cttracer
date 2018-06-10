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

   
    Route::resource('/my-project', 'MyProjController')->parameters([
        'rol' => 'admin_user'
    ]);

    Route::resource('/project-search', 'ProjSearchController')->parameters([
        'rol' => 'admin_user'
    ]);

    Route::any('/proj-search-results', function(){
        $q = Input::get('q');
        if($q != '') {
            $data = DB::table('project')->where('project.projName','LIKE', '%'.$q.'%')
            ->select('project.*')
            ->paginate(1)
            ->setpath('');
        $data->appends(array(
            'q' => Input::get('q')
        ));
            if(count($data)>0) {
                return view('pages.project_search.index')->withData($data);
            } else {
                return view('pages.project_search.index')->withMessage("No results found");
            }
        }
    });

    Route::any('/project-search-r', [
        'uses' => 'ProjSearchController@search'
    ]);

    Route::resource('/approve-projects', 'ProjAppController')->parameters([
        'rol' => 'admin_user'
    ]);

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
 */




Auth::routes();


