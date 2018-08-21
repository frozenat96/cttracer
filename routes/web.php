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
use App\models\AccessControl;
use App\models\RevisionHistory;
use Illuminate\Support\Facades\Artisan;

Route::group(['middleware' => ['auth']], function() {
    //Access Control Switch
    //Controls what the user can access in the back end.
    $ac = new AccessControl;
    $rv = new RevisionHistory;
    $accesscontrols = $ac->status;
    //

    $student = $panel = $coord = $coordpanel = [];
    if($accesscontrols==true) {
        $coordpanel = ['Capstone Coordinator','Panel Member'];
        $coord = ['Capstone Coordinator'];
        $panel = ['Panel Member','Capstone Coordinator'];
        $student = ['Student'];
    } else {
        $all = ['Capstone Coordinator','Panel Member','Student'];
        $student = $panel = $coord = $coordpanel = $all;
    }
    if($rv->status==true) {
        $rvhist = ['Capstone Coordinator','Panel Member'];
    } else {
        $rvhist = ['Admin'];
    }
    

    Route::group(['middleware' => 'roles', 'roles' => $coordpanel], function() {
        Route::resource('/projects', 'ProjectController')->parameters([
            ]);
            
        Route::any('/proj-search-results', [
                'uses'=>'ProjectController@search',
            ]
            ); 
    }); //End Route::Group Capstone Coordinator/Panel Members

    Route::group(['middleware' => 'roles', 'roles' => $rvhist], function() {
        Route::resource('/revision-history', 'RevHistoryController');

        Route::any('/revision-history-search-results/{search}', [
            'uses'=>'RevHistoryController@search',
        ]
        );

        Route::any('/revision-history-view', [
            'uses'=>'RevHistoryController@view',
        ]
        );

        Route::any('/revision-history-delete-all-group/{id}', [
            'uses'=>'RevHistoryController@deleteAllByGroup',
        ]
        );

        Route::resource('/group-history', 'GrpHistoryController');

        Route::any('/group-history-search-results', [
            'uses'=>'GrpHistoryController@search',
        ]
        );

        Route::any('/group-history-delete-all-group/{id}', [
            'uses'=>'GrpHistoryController@deleteAllByGroup',
        ]
        );
    }); //End Route::Group Capstone Coordinator/Panel Members

    Route::group(['middleware' => 'roles', 'roles' => $coord], function() {
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

            Route::any('/accDelete/{id}', [
                'uses'=>'AccountController@deleteUpdate',
                'as' => 'accDelete',
            ]
            ); 
    
            Route::any('/quick-view-search-results', [
                'uses'=>'QuickViewController@search',
                'as' => 'quickViewSearch'
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

            Route::any('/next-stage', [
                'uses'=>'QuickViewController@nextStage',
                'as'=> 'nextstage',
            ]
            );

            Route::any('/finalize-schedule', [
                'uses'=>'QuickViewController@finalizeSchedule',
                'as'=> 'finalizeSchedule',
            ]
            );

            Route::any('/project-complete', [
                'uses'=>'QuickViewController@setToProjComplete',
                'as'=> 'projectComplete',
            ]
            );
    
            Route::resource('/stage-settings', 'StageController')->parameters([
            ]);

            Route::any('/stage-search-results', [
                'uses'=>'StageController@search',
            ]
            );

            Route::get('/transfer-role-index', [
                'uses'=>'AccountController@transferRole',
            ]
            );

            Route::any('/transferExecute', [
                'uses'=>'AccountController@transferExecute',
                'as' => 'transferExecute',
            ]
            ); 

            Route::any('/coordRequestForSched',[
                'uses'=>'ScheduleController@coordRequestForSched'
            ]);

            Route::any('/coordRequestForSched-form/{id}',[
                'uses'=>'ScheduleController@coordRequestForSchedForm',
                'as'=>'request-schedule'
            ]);

            Route::resource('/project-archive', 'ProjSearchController')->except([
                'index',
            ]);

            Route::any('/setPanelVerdictIndex/{groupNo}',[
                'uses'=>'QuickViewController@setProjectVerdictIndex',
                'as'=>'setPanelVerdictIndex'
            ]);

            Route::any('/setPanelVerdict',[
                'uses'=>'QuickViewController@setProjectVerdict',
                'as'=>'setPanelVerdict'
            ]);

            Route::any('/deleteFinishedGroups',[
                'uses'=>'GroupController@deleteFinishedGroups',
            ]); 

            //secret routes
            Route::any('/trn',[ //Delete all notifications
                'uses'=>'PagesController@truncateNotifications',
            ]);

            Route::any('/trv',[ //Delete all revision history
                'uses'=>'RevHistoryController@truncateRevHistory',
            ]);

            Route::any('/dap',[ //Disable all group approvals
                'uses'=>'ProjectController@disableAllApprovals',
            ]);

            Route::any('/trg',[ //Disable all group history
                'uses'=>'GrpHistoryController@deleteAll',
            ]);

            //general settings
            Route::any('/application-settings',[
                'uses'=>'PagesController@appSettingsEdit',
            ]); 
            
            Route::any('/application-settings-store',[
                'uses'=>'PagesController@appSettingsStore',
            ]); 
            Route::any('/application-settings-update/{id}',[
                'uses'=>'PagesController@appSettingsUpdate',
            ]); 
            
    }); //End Route::Group Capstone Coordinator

   
    Route::group(['middleware' => 'roles', 'roles' => $panel], function() {
        Route::resource('/advised-groups', 'AdvisedGroupsController')->parameters([
        ]);
    
        Route::any('/advised-groups-search-results', [
            'uses'=>'AdvisedGroupsController@search',
        ]
        ); 

        Route::any('/contentAdvAppForSched', [
            'uses'=>'AdvisedGroupsController@contentAdvAppForSched',
            'as' => 'ContentAdvAppForSched',
        ]
        ); 
    
        Route::any('/contentAdvApproval', [
            'uses'=>'AdvisedGroupsController@contentAdvApproval',
            'as' => 'contentAdvApproval',
        ]
        );  
        
        Route::any('/contentAdvCorrections', [
            'uses'=>'AdvisedGroupsController@contentAdvCorrections',
            'as' => 'contentAdvCorrections',
        ]
        );  

        Route::resource('/approve-schedules', 'SchedAppController')->parameters([
        ]);

        Route::any('/approve-schedules-search-results', [
            'uses'=>'SchedAppController@search',
        ]
        ); 


        Route::any('/schedApprovalStatus', [
            'uses'=>'SchedAppController@schedApprovalStatus',
            'as' => 'sched-approve-status',
        ]
        ); 

        Route::any('/projApprovalStatus', [
            'uses'=>'ProjAppController@projApprovalStatus',
            'as' => 'proj-approve-status',
        ]
        ); 

        Route::resource('/approve-projects', 'ProjAppController')->parameters([
        ]);
    
        Route::any('/app-proj-search-results', [
            'uses'=>'ProjAppController@search',
        ]
        ); 

    }); //End Route::Group Panel Member

    Route::group(['middleware' => 'roles', 'roles' => $student], function() {
        Route::resource('/my-project', 'MyProjController')->parameters([
        ]);

        Route::any('/my-project/{id}/submit-project-archive', [
            'uses'=>'MyProjController@submitProjectArchive',
        ]
        ); 

        Route::any('/submitProjectArchiveStore/{id}', [
            'uses'=>'MyProjController@submitProjectArchiveStore',
        ]
        ); 

        Route::any('/submission-instructions', [
            'uses'=>'PagesController@submissionInstructionsIndex',
        ]
        );
        
    }); //End Route::Group Student

    //routes without roles needed
    Route::get('/', [
        'uses'=>'PagesController@index',
        'as'=> 'home'
    ]
    );

    Route::resource('/project-archive', 'ProjSearchController')->only([
        'index',
    ]);

    Route::any('/proj-archive-search-results', [
        'uses'=>'ProjSearchController@search',
    ]
    );

    Route::resource('/final-schedule-list', 'ScheduleController')->parameters([
        ]);


    Route::any('/final-schedule-list-results', [
        'uses'=>'ScheduleController@search',
    ]
    ); 
   
    //Notifications
    
    Route::get('/alertBox',function(){
        return view('events.event-listener');
    });

    Route::get('/fireEvent', 'NotificationController@notifyFireSchedRequest');
    
    Route::post('/n/{name}',[
        'uses'=> 'NotificationController@NotifyGet'
    ]);
    Route::any('/nd/{name}/{redirect}/{input}',[
        'uses'=> 'NotificationController@NotifyDelete'
    ]); 

    //For Email
    Route::any('/NotifyPanelOnSchedRequest_e','MailController@NotifyPanelOnSchedRequest_e');
    Route::any('/NotifyPanelOnSchedRequest_s','MailController@NotifyPanelOnSchedRequest_s');

    Route::any('/NotifyCoordOnSchedRequest_e','MailController@NotifyCoordOnSchedRequest_e');

    //misc
    Route::get('/terms','PagesController@terms');
    Route::get('/contact','PagesController@contact');
    Route::get('/about','PagesController@about');

});

Route::get('login/{provider}', 'Auth\LoginController@redirectToProvider');
Route::get('login/{provider}/callback', 'Auth\LoginController@handleProviderCallback');
Route::get('logout', '\App\Http\Controllers\Auth\LoginController@logout');
Route::any('/approved-sched-via-email',function(){
    return view('events.approved-sched-via-email');
});
Route::any('/schedApprovalStatus', [
    'uses'=>'SchedAppController@schedApprovalStatus',
    'as' => 'sched-approve-status',
]
); 

Route::any('/testEmail', function(){
    return view('email-notifications.test')->with(['grp'=>'1','acc'=>'10']);
}); 
 
Auth::routes();