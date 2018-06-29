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
use App\Events\eventTrigger;

Route::group(['middleware' => ['auth']], function() {
    Route::get('/', [
        'uses'=>'PagesController@index',
    ]
    );

    Route::group(['middleware' => 'roles', 'roles' => ['Capstone Coordinator','Panel Member','Student']], function() {
        Route::resource('/projects', 'ProjectController')->parameters([
            ]);
            
        Route::any('/proj-search-results', [
                'uses'=>'ProjectController@search',
            ]
            ); 
    }); //End Route::Group Capstone Coordinator/Panel Members


    Route::group(['middleware' => 'roles', 'roles' => ['Capstone Coordinator','Panel Member','Student']], function() {
        Route::resource('/accounts', 'AccountController');
        Route::any('/acc-search-results', [
            'uses'=>'AccountController@search',
        ]
        );


        Route::resource('/groups', 'GroupController')->parameters([]);
            Route::any('/group-search-results', [
            'uses'=>'GroupController@search',
            ]
            );

            Route::resource('/quick-view', 'QuickViewController');
        
            Route::any('/quick-view-search-results', [
                'uses'=>'QuickViewController@search',
            ]
            ); 
        
            Route::any('/modifyProjApp/{id}', [
                'uses'=>'QuickViewController@modifyProjApp',
                'as'=> 'modifyProjApp',
            ]
            );
        
            Route::any('/modifyProjAppUpdate', [
                'uses'=>'QuickViewController@modifyProjAppUpdate',
                'as'=> 'modifyProjAppUpdate',
            ]
            );
    
            Route::resource('/stage-settings', 'StageController')->parameters([
            ]);

            Route::any('/stage-search-results', [
                'uses'=>'StageController@search',
            ]
            );

            Route::get('/transfer-role-index', [
                'uses'=>'PagesController@transferRole',
            ]
            );

            Route::any('/transfer-role-results', [
                'uses'=>'AccountController@transfer',
            ]
            ); 

    }); //End Route::Group Capstone Coordinator

   
    Route::group(['middleware' => 'roles', 'roles' => ['Capstone Coordinator','Panel Member','Student']], function() {
        Route::resource('/advised-groups', 'AdvisedGroupsController')->parameters([
        ]);
    
        Route::any('/advised-groups-search-results', [
            'uses'=>'AdvisedGroupsController@search',
        ]
        ); 

        Route::any('/contentAdvAppForSched', [
            'uses'=>'AdvisedGroupsController@contentAdvAppForSched',
            'as' => 'ContentAdvAppForSched',
            'roles' => 'Student'
        ]
        ); 
    
        Route::any('/contentAdvCorrectForSched', [
            'uses'=>'AdvisedGroupsController@ContentAdvCorrectForSched',
            'as' => 'ContentAdvCorrectForSched',
            'roles' => 'Student'
        ]
        ); 

        Route::resource('/approve-schedules', 'SchedAppController')->parameters([
            'roles' => 'Student',
        ]);

        Route::any('/approve-schedules-search-results', [
            'uses'=>'SchedAppController@search',
        ]
        ); 


        Route::any('/approveStatus', [
            'uses'=>'SchedAppController@approvalStatus',
            'as' => 'approve-status',
        ]
        ); 

        Route::resource('/approve-projects', 'ProjAppController')->parameters([
            'roles' => 'Student'
        ]);
    
        Route::any('/app-proj-search-results', [
            'uses'=>'ProjAppController@search',
            'roles' => 'Student'
        ]
        ); 

    }); //End Route::Group Panel Member

    Route::group(['middleware' => 'roles', 'roles' => ['Capstone Coordinator','Panel Member','Student']], function() {
        Route::resource('/my-project', 'MyProjController')->parameters([
            'roles' => 'Student'
        ]);
    }); //End Route::Group Student

    //routes without middleware:
    Route::resource('/project-archive', 'ProjSearchController')->parameters([
    ]);
    
    Route::any('/proj-archive-search-results', [
        'uses'=>'ProjSearchController@search',
    ]
    ); 
   
    //Notifications
    Route::get('/alertBox',function(){
        return view('events.event-listener');
    });

    Route::get('/fireEvent', 'NotificationController@notifyFireSchedRequest');
    /*
    Route::get('/fireEvent',function(){
        event(new eventTrigger('How Are You?'));
        return 'Event fired';
    });*/

    Route::post('/NotifyPanelOnSchedRequest','NotificationController@NotifyPanelOnSchedRequest');
    Route::any('/NotifyPanelOnSchedRequest_d','NotificationController@NotifyPanelOnSchedRequest_d');

    Route::post('/NotifyPanelOnRevisions','NotificationController@NotifyPanelOnRevisions');
    Route::any('/NotifyPanelOnRevisions_d','NotificationController@NotifyPanelOnRevisions_d');

    Route::post('/NotifyAdviserOnSchedRequest','NotificationController@NotifyAdviserOnSchedRequest');
    Route::any('/NotifyAdviserOnSchedRequest_d','NotificationController@NotifyAdviserOnSchedRequest_d');

    Route::post('/NotifyAdviserOnRevisions','NotificationController@NotifyAdviserOnRevisions');
    Route::any('/NotifyAdviserOnRevisions_d','NotificationController@NotifyAdviserOnRevisions_d');

});

Route::get('login/{provider}', 'Auth\LoginController@redirectToProvider');
Route::get('login/{provider}/callback', 'Auth\LoginController@handleProviderCallback');
Route::get('logout', '\App\Http\Controllers\Auth\LoginController@logout');

Auth::routes();


