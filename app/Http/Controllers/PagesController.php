<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Auth;
use App\models\Project;
use App\models\AccountGroup;
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
        ->join('account','group.groupAdviser','=','account.accNo')
        ->select('group.*','project.*','account.*')
        ->paginate(10); 
        return view('pages.search-groups')->with('data',$groups);
    }

    public function searchGroup() {
        $q = Input::get('q');
      
        if($q != '') {
            $data = DB::table('group')
            ->join('account','account.accNo','=','group.groupAdviser')
            ->join('project','project.projGroupNo','=','group.groupNo')
            ->select('account.*','group.*','project.*')
            ->where('group.groupName','LIKE', "%".$q."%")
            ->orWhere(DB::raw('CONCAT(account.accFName," ",account.accMInitial," ",account.accLName," ",account.accTitle)'), 'LIKE', "%".$q."%")
            ->orWhere('group.groupStatus','LIKE', "%".$q."%")
            ->paginate(10);
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

    public function advisedGroupsIndex() {
        $substatus = ['Submitted To Content Adviser','Not Ready For Defense','Ready For Defense','Submitted For Panel Approval'];
        $user_id = Auth::id(); 
        $groups = DB::table('group')
        ->join('project','project.projGroupNo','=','group.groupNo')
        ->join('account','group.groupAdviser','=','account.accNo')
        ->select('group.*','project.*','account.*')
        ->where('account.accType','=','2')
        ->where('group.groupAdviser','=',$user_id)
        ->whereIn('group.groupStatus', $substatus)
        ->paginate(10); 
        return view('pages.advised-groups')->with('data',$groups);
    }

    public function advisedGroupsSearch() {
        $q = Input::get('q');
      
        if($q != '') {
            $data = DB::table('group')
            ->join('account','account.accNo','=','group.groupAdviser')
            ->join('project','project.projGroupNo','=','group.groupNo')
            ->select('account.*','group.*','project.*')
            ->where('account.accType','=','2')
            ->where('group.groupAdviser','=',$user_id)
            ->where('group.groupStatus','=', 'Submitted To Content Adviser')
            ->where('group.groupName','LIKE', "%".$q."%")
            ->orWhere(DB::raw('CONCAT(account.accFName," ",account.accMInitial," ",account.accLName," ",account.accTitle)'), 'LIKE', "%".$q."%")
            ->paginate(10);
        } else {
            return redirect()->action('PagesController@advisedGroupsIndex');
        }

        $data->appends(array(
            'q' => Input::get('q')
        ));
           
        return view('pages.groups.index')->withData($data);
    }

}
