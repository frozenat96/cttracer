<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\models\Project; 
use Auth;
use Illuminate\Support\Facades\Input;

class ProjAppController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user_id = Auth::id(); 

        $proj = DB::table('project_approval')
        ->join('panel_group','panel_group.panelGroupNo','=','project_approval.projAppPGroupNo')
        ->join('group', 'group.groupNo', '=', 'panel_group.panelCGroupNo')
        ->join('project','project.projGroupNo','=','group.groupNo')
        ->join('panel_verdict', 'panel_verdict.panelVerdictNo', '=', 'project.projPVerdictNo')
        ->join('stage', 'stage.stageNo', '=', 'project.projStageNo')
        ->select('project.*','group.*','panel_verdict.*','stage.*','project_approval.*','panel_group.*')
        ->where('panel_group.panelAccNo','=',$user_id)
        ->whereIn('group.groupStatus',['Submitted to Content Adviser'])
        ->whereIn('project.projPVerdictNo',[2,3]) 
        ->paginate(1);
        $data = ['proj'=>$proj];
        return view('pages.approve_projects.index')->with('data',$proj);
    }

    
    public function search(Request $request)
    {
        $user_id = Auth::id(); 

        $q = Input::get('q');
        if($q != '') {
            $data = DB::table('project_approval')
            ->join('panel_group','panel_group.panelGroupNo','=','project_approval.projAppPGroupNo')
            ->join('group', 'group.groupNo', '=', 'panel_group.panelCGroupNo')
            ->join('project','project.projGroupNo','=','group.groupNo')
            ->join('panel_verdict', 'panel_verdict.panelVerdictNo', '=', 'project.projPVerdictNo')
            ->join('stage', 'stage.stageNo', '=', 'project.projStageNo')
            ->select('project.*','group.*','panel_verdict.*','stage.*','project_approval.*','panel_group.*','account.*')
            ->where('project.projName','LIKE', "%".$q."%")
            ->where('panel_group.panelAccNo','=',$user_id)
            ->whereIn('group.groupStatus',['Approved by Content Adviser'])
            ->whereIn('project.projPVerdictNo',[2,3])
            ->paginate(1)
            ->setpath('');
 
            $data->appends(array(
                'q' => Input::get('q')
            ));
            return view('pages.approve_projects.index')->with('data',$data);
        } else {
            return redirect()->action('ProjAppController@index');
        }
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
        //
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
