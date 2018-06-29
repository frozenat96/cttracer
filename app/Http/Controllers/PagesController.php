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
