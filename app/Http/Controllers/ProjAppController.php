<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\models\ProjectApproval;
use App\models\Project;
use App\models\Group;
use Auth;
use DB;
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
        $ValidGroupStatus = ['Approved by Content Adviser'];

        $data = DB::table('project_approval')
        ->join('panel_group','panel_group.panelGroupNo','=','project_approval.projAppPGroupNo')
        ->join('group','group.groupNo','=','panel_group.panelCGroupNo')
        ->join('project','project.projGroupNo','=','group.groupNo')
        ->join('panel_verdict','panel_verdict.panelVerdictNo','=','project.projPVerdictNo')
        ->join('account','account.accNo','=','panel_group.panelAccNo')
        ->select('project_approval.*','panel_group.*','account.*','project.*','group.*','panel_verdict.*')
        ->where('panel_group.panelAccNo','=',$user_id)
        ->whereIn('group.groupStatus', $ValidGroupStatus)
        ->whereIn('project.projPVerdictNo',['2','3'])
        ->paginate(3); 
        //return $this->calcSchedStatus($sched[0]->panelCGroupNo);
        return view('pages.approve_projects.index')->with('data',$data);
    }

    
    public function search(Request $request)
    {
        $user_id = Auth::id();
        $q = Input::get('q');
      
        $ValidGroupStatus = ['Approved by Content Adviser'];
        if($q != '') {
            $data = DB::table('project_approval')
            ->join('panel_group','panel_group.panelGroupNo','=','project_approval.projAppPGroupNo')
            ->join('group','group.groupNo','=','panel_group.panelCGroupNo')
            ->join('project','project.projGroupNo','=','group.groupNo')
            ->join('panel_verdict','panel_verdict.panelVerdictNo','=','project.projPVerdictNo')
            ->join('account','account.accNo','=','panel_group.panelAccNo')
            ->select('project_approval.*','panel_group.*','account.*','project.*','group.*','panel_verdict.*')
            ->where('panel_group.panelAccNo','=',$user_id)
            ->whereIn('group.groupStatus', $ValidGroupStatus)
            ->whereIn('project.projPVerdictNo',['2','3'])
            ->where('group.groupName','LIKE', "%".$q."%")
            ->paginate(3); 
        } else {
            return redirect()->action('ProjAppController@index');
        }

        $data->appends(array(
            'q' => Input::get('q')
        ));
           
        return view('pages.approve_projects.index')->with('data',$data);
    }

    public function projApprovalStatus(Request $request) {
        if (is_null($request->input('submit'))){
            return redirect()->back()->with('error', 'Schedule approval failed.');
        } 
        $q = DB::table('project_approval')
        ->join('panel_group','panel_group.panelGroupNo','=','project_approval.projAppPGroupNo')
        ->join('group','group.groupNo','=','panel_group.panelCGroupNo')
        ->join('account','account.accNo','=','panel_group.panelAccNo')
        ->select('project_approval.*','account.*','panel_group.*','group.*')
        ->where('account.accNo','=',$request->input('acc'))
        ->where('panel_group.panelCGroupNo','=',$request->input('grp'))
        ->first();
       
        try{
            DB::beginTransaction();
            $approval = ProjectApproval::find($q->projAppNo);
            if($request->input('opt')=='1') {
                $approval->isApproved = 1;
                $msg = 'The project of group : ' . $q->groupName . ' was approved.';
            } else {
                $approval->isApproved = 2;
                $msg = 'The project of group : ' . $q->groupName . ' was disapproved.';
            }

            if($approval->save() && $this->calcProjAppStatus($request->input('grp'))) {
                DB::commit();
                return redirect()->back()->with('success', $msg);
            } else {
                DB::rollback();
                return redirect()->back()->withError('Project approval failed.');
            }

        } catch (\Exception $e) { 
            DB::rollback();
            return redirect()->back()->withError('Project approval failed.');
        }
        
        
    }

    public function calcProjAppStatus($groupNo){

        $pMembers = DB::table('panel_group')
        ->where('panel_group.panelCGroupNo','=',$groupNo)
        ->count();

        $pMembersCorrected = DB::table('project_approval')
        ->join('panel_group','panel_group.panelGroupNo','=','project_approval.projAppPGroupNo')
        ->select('project_approval.isApproved')
        ->where('panel_group.panelCGroupNo','=',$groupNo)
        ->where('project_approval.isApproved','=','2')
        ->count();

        $pMembersWaiting = DB::table('project_approval')
        ->join('panel_group','panel_group.panelGroupNo','=','project_approval.projAppPGroupNo')
        ->select('project_approval.isApproved')
        ->where('panel_group.panelCGroupNo','=',$groupNo)
        ->where('project_approval.isApproved','=','0')
        ->count();

        $proj = DB::table('project')
        ->join('group','group.groupNo','=','project.projGroupNo')
        ->select('project.*')
        ->where('project.projGroupNo','=',$groupNo)
        ->first();
        $projStatus = Project::find($proj->projNo);
        $group = Group::find($groupNo);

        if(!$pMembersWaiting && $pMembersCorrected) {
            $group->groupStatus = 'Corrected by Panel Members';
        } elseif(!$pMembersWaiting && !$pMembersCorrected) {
            $group->groupStatus = 'Ready for Next Stage';
            $projStatus->projPVerdictNo = '5';
        }

        if($projStatus->save() && $group->save()) {
            return 1;
        } else {
            return 0;
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
