<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\models\Group;
use App\models\Project;
use DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Auth;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = DB::table('group')
        ->join('project','project.projGroupNo','=','group.groupNo')
        ->join('panel_verdict','panel_verdict.panelVerdictNo','=','project.projPVerdictNo')
        ->select('project.*','group.*','panel_verdict.*')
        ->paginate(10);
        return view('pages.projects.index')->withData($data);
    }

    public function search(Request $request)
    {
        $q = Input::get('q');
        if($q != '') {
            $data = DB::table('group')
            ->join('project','project.projGroupNo','=','group.groupNo')
            ->join('panel_verdict','panel_verdict.panelVerdictNo','=','project.projPVerdictNo')
            ->select('project.*','group.*','panel_verdict.*')
            ->where('project.projName','LIKE', "%".$q."%") 
            ->paginate(10)
            ->setpath('');
        } else {
            return redirect()->action('ProjectController@index');
        }

        $data->appends(array(
            'q' => Input::get('q')
        ));
        
        return view('pages.projects.index')->with('data',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user_id = Auth::id(); 
        $Projectmodel = new Project();
        $proj = $Projectmodel->projectInfoByGroup($id);
        if(!count($proj)) {
            return redirect()->back()->withErrors('Project not found.');
        }
    
        $group = DB::table('account')
            ->join('group', 'account.accGroupNo', '=', 'group.groupNo')
            ->select('account.*')
            ->where('group.groupNo','=',$proj[0]->groupNo)
            ->get();
        
        $pgroup = DB::table('panel_group')
        ->join('account', 'account.accNo', '=', 'panel_group.panelAccNo')
        ->join('group', 'panel_group.panelCGroupNo', '=', 'group.groupNo')
        ->join('project_approval', 'project_approval.projAppPGroupNo', '=', 'panel_group.panelGroupNo')
        ->select('account.*','project_approval.*','panel_group.*')
        ->where('panel_group.panelCGroupNo','=',$proj[0]->groupNo)
        ->get();
        $adviser = DB::table('account')
        ->where('account.accNo','=',$proj[0]->groupCAdviserNo)
        ->get();
        $data = ['proj' => $proj, 'group' => $group,'adviser'=>$adviser,'pgroup'=>$pgroup];
        return view('pages.projects.view')->with('data', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
