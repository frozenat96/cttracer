<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Auth;
use App\models\Project;
use App\models\AccountGroup;
use App\models\Schedule;
use App\models\ScheduleApproval;
use Illuminate\Support\Facades\Validator;
use DB;
use Illuminate\Support\Facades\Input;

class PagesController extends Controller
{
    public function index() {
        $title = 'Welcome to Laravel!';
        //return view('pages.index',compact('title'));
        $data = array(
            'title' => 'Services',
            'services' => ['Web Design', 'Programming', 'SEO']
        );
        return view('pages.index')->with($data);
    }

    public function addAccounts() {
        return view('pages.add-accounts');
    }

    public function addGroup() {
        return view('pages.add-groups');
    }

    public function projectSearch($x = null) {
        return view('pages.project_search.p-search-result')->with('data',$x);
    }

    public function searchGroupIndex() {
        $groups = DB::table('group')
        ->join('project','project.projGroupNo','=','group.groupNo')
        ->join('panel_verdict','panel_verdict.panelVerdictNo','=','project.projPVerdictNo')
        ->join('stage','stage.stageNo','=','project.projStageNo')
        ->join('account','group.groupCAdviserNo','=','account.accNo')
        ->select('group.*','project.*','account.*','stage.*','panel_verdict.*')
        ->paginate(3); 
        return view('pages.search-groups')->with('data',$groups);
    }

    public function searchGroup() {
        $q = Input::get('q');
      
        if($q != '') {
            $data = DB::table('group')
            ->join('project','project.projGroupNo','=','group.groupNo')
            ->join('panel_verdict','panel_verdict.panelVerdictNo','=','project.projPVerdictNo')
            ->join('stage','stage.stageNo','=','project.projStageNo')
            ->join('account','group.groupCAdviserNo','=','account.accNo')
            ->select('group.*','project.*','account.*','stage.*','panel_verdict.*')
            ->where('group.groupName','LIKE', "%".$q."%")
            ->orWhere(DB::raw('CONCAT(account.accFName," ",account.accMInitial," ",account.accLName," ",account.accTitle)'), 'LIKE', "%".$q."%")
            ->orWhere('group.groupStatus','LIKE', "%".$q."%")
            ->orWhere('panel_verdict.pVerdictDescription','LIKE', "%".$q."%")
            ->orWhere('stage.stageName','LIKE', "%".$q."%")
            ->paginate(3);
        } else {
            return redirect()->action('PagesController@searchGroupIndex');
        }

        $data->appends(array(
            'q' => Input::get('q')
        ));
           
        return view('pages.search-groups')->withData($data);
    }

    public function stageSettings() {
        return view('pages.stage-settings');
    }

    public function transferRole() {
        $data = DB::table('account')
        ->where('account.accType','=','2')
        ->get();
        return view('pages.transfer-role')->with('data',$data);
    }

    public function getData(Request $request) {
        $data=Project::where('projName','LIKE','%'.$request->search."%")->paginate(1);
        $data = response()->json($data);
        return redirect()->action('ProjSearchController@index')->with('data',$data)->send();
    }

}
