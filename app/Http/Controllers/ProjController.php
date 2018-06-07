<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use DB;
use App\models\Project;

class ProjController extends Controller
{
    public function myProject() {
        $user_id = Auth::id(); 

        $data = DB::table('account')
            ->join('account_group', 'account.accNo', '=', 'account_group.accNo')
            ->join('group', 'account_group.grpNo', '=', 'group.groupNo')
            ->join('project', 'group.groupProjNo', '=', 'project.projNo')
            ->select('project.*','account.*','group.*','account_group.*')
            ->where('account.accNo','=',$user_id)
            ->get();
        //return dd($users);
        return view('pages.my_project.index')->with('data', $data);
    }

    public function edit($id) {
        $proj = Project::find($id);
        return view('pages.my_project.edit')->with('data', $users);
    }
}
