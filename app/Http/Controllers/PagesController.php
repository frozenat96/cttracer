<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Auth;
use App\models\Project;
use App\models\AccountGroup;
use DB;

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

    public function addGroups() {
        return view('pages.add-groups');
    }

    public function approveSchedules() {
        return view('pages.approve-schedules');
    }

    public function projectSearch($x = null) {
        return view('pages.project_search.p-search-result')->with('data',$x);
    }

    public function scheduleSettings() {
        return view('pages.schedule-settings');
    }

    public function searchGroups() {
        return view('pages.search-groups');
    }

    public function stageSettings() {
        return view('pages.stage-settings');
    }

    public function transferRole() {
        return view('pages.transfer-role');
    }

    public function getData(Request $request) {
        $data=Project::where('projName','LIKE','%'.$request->search."%")->paginate(1);
        $data = response()->json($data);
        return redirect()->action('ProjSearchController@index')->with('data',$data)->send();
    }
}
