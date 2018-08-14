<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Auth;
use App\models\Project;
use App\models\AccountGroup;
use App\models\Schedule;
use App\models\ScheduleApproval;
use App\models\Dashboard;
use App\models\ApplicationSetting;
use App\models\AccessControl;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Webpatser\Uuid\Uuid;
use DB;
use Exception;
use Session;

class PagesController extends Controller
{
    public function __construct()
    {
        $ac = new AccessControl;
        $accesscontrol = $ac->status; 
        if($accesscontrol == true) {
            $this->middleware('auth');
            $this->middleware('roles', ['roles'=> ['Capstone Coordinator'],'only' => ['truncateNotifications']]);
            //$this->middleware('permission:edit-posts',   ['only' => ['edit']]);
        }
    }

    public function index() {
        $user = User::find(Auth::user()->getId());
        if($user->accType=='1') {
            $db = new Dashboard;
            $data = $db->getDashboardCoord();
            return view('pages.dashboard.dashboard-coordinator')->with('data',$data);
        }
        elseif($user->accType=='2') {
            //return view('pages.index')->with('success','Hi ' . $user->accFName . ', welcome to CT-Tracer');
            $db = new Dashboard;
            $data = $db->getDashboardPanel();
            return view('pages.dashboard.dashboard-panel')->with('data',$data);
        } else {
            return view('pages.index');
        }
    }

    public function truncateNotifications() {
        try {
        DB::beginTransaction();
        DB::table('notification')->truncate();
        DB::commit();
        } catch(Exception $e) {
        DB::rollback();
        }
        Session::flash('success','All notifications has been deleted!');
        return view('pages.index')->with('success','All notifications has been deleted!');
    }

    public function about() {
        return view('pages.misc.about');
    }

    public function terms() {
        return view('pages.misc.terms');
    }

    public function contact() {
        return view('pages.misc.contact');
    }

    public function folderSettingsCreate() {
        $user_id = Auth::user()->getId();
        $application_settings = DB::table('application_setting')
        ->where('settingCoordID','=',$user_id)
        ->first();
        return view('pages.application_settings.edit')->with('data',$application_settings);
    }

    public function appSettingsStore(Request $request) {
        try {   
            $validator = Validator::make($request->all(), [
                'document_folder_link' => ['required','max:150','active_url'],
                'project_archive_folder_link' => ['required','max:150','active_url'],
            ]);
            if ($validator->fails()) {
                return redirect()->back()->withInput($request->all)->withErrors($validator);
            }
            DB::beginTransaction();
            $user_id = Auth::user()->getId();
            $app = new ApplicationSetting;
            $app->settingID = Uuid::generate()->string;
            $app->settingCoordID = $user_id;
            $app->settingDocLink = $request->input('document_folder_link');
            $app->settingProjArcLink = $request->input('project_archive_folder_link');
            $app->save();
            DB::commit();
            return redirect()->back()->withInput($request->all)->withSuccess('Folder settings was created successfully.');
        } catch(Exception $e) {
            DB::rollback();
            return redirect()->back()->withInput($request->all)->withErrors('Folder settings was not created.');
        }        
    }

    public function appSettingsEdit() {
        $user_id = Auth::user()->getId();
        $application_settings = DB::table('application_setting')
        ->where('settingCoordID','=',$user_id)
        ->first();
        if(is_null($application_settings)) {
            return view('pages.application_settings.create');
        }
        return view('pages.application_settings.edit')->with('data',$application_settings);
    }

    public function appSettingsUpdate($id) {
        try {  
            $validator = Validator::make($request->all(), [
                'document_folder_link' => ['required','max:150','active_url'],
                'project_archive_folder_link' => ['required','max:150','active_url'],
            ]);
            if ($validator->fails()) {
                return redirect()->back()->withInput($request->all)->withErrors($validator);
            }
            $app = ApplicationSetting::find($id);
            $app->settingDocLink = $request->input('document_folder_link');
            $app->settingProjArcLink = $request->input('project_archive_folder_link');
            $app->save();
            DB::commit();
            return redirect()->back()->withInput($request->all)->withSuccess('Folder settings was updated successfully.');
        } catch(Exception $e) {
            DB::rollback();
            return redirect()->back()->withInput($request->all)->withErrors('Folder settings was not updated.');
        }
        return view('pages.application_settings.edit')->with('data',$application_settings);
    }

}
