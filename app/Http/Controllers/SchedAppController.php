<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\models\ScheduleApproval;
use App\models\Schedule;
use App\models\Group;
use App\models\Project;
use App\models\Stage;
use App\models\AccessControl;
use Auth;
use DB;
use Illuminate\Support\Facades\Input;
use App\models\Notification;
use Exception;
use Illuminate\Validation\Rule;

class SchedAppController extends Controller
{
    public function __construct()
    {
        $ac = new AccessControl;
        $accesscontrol = $ac->status; 
        if($accesscontrol == true) {
            $this->middleware('auth',['only' => ['index','search']]);
            $this->middleware('roles', ['only' => ['index','search'],'roles'=> ['Panel Member']]);
            //$this->middleware('permission:edit-posts',   ['only' => ['edit']]);
        }
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user_id = Auth::user()->getId();
        $ValidGroupStatus = ['Waiting for Schedule Approval'];
        $sched = DB::table('group')
        ->join('project','project.projGroupID','=','group.groupID')
        ->join('stage','stage.stageNo','=','project.projStageNo')
        ->join('panel_group','panel_group.panelCGroupID','=','group.groupID')
        ->join('schedule_approval','schedule_approval.schedPanelGroupID','=','panel_group.panelGroupID')
        ->join('account','account.accID','=','panel_group.panelAccID')
        ->join('schedule','schedule.schedGroupID','=','group.groupID')
        ->join('panel_verdict','panel_verdict.panelVerdictNo','=','project.projPVerdictNo')
        ->where('panel_group.panelAccID','=',$user_id)
        ->whereIn('group.groupStatus', $ValidGroupStatus)
        ->whereNotIn('project.projPVerdictNo',['2','3'])
        ->where('schedule_approval.isApproved','=','0')
        ->paginate(5); 
        //return $this->calcSchedStatus($sched[0]->panelCGroupID);
      	//return dd($sched);
        return view('pages.approve_schedules.index')->with('data',$sched);
    }

    public function search() {
        $user_id = Auth::user()->getId();
        $q = Input::get('q');
        $ValidGroupStatus = ['Waiting for Schedule Approval'];
        if($q != '') {
            $data = DB::table('group')
            ->join('project','project.projGroupID','=','group.groupID')
            ->join('stage','stage.stageNo','=','project.projStageNo')
            ->join('panel_group','panel_group.panelCGroupID','=','group.groupID')
            ->join('schedule_approval','schedule_approval.schedPanelGroupID','=','panel_group.panelGroupID')
            ->join('account','account.accID','=','panel_group.panelAccID')
            ->join('schedule','schedule.schedGroupID','=','group.groupID')
            ->join('panel_verdict','panel_verdict.panelVerdictNo','=','project.projPVerdictNo')
            ->where('panel_group.panelAccID','=',$user_id)
            ->whereIn('group.groupStatus', $ValidGroupStatus)
            ->whereNotIn('project.projPVerdictNo',['2','3'])
            ->where('schedule_approval.isApproved','=','0')
            ->where(function ($query) use ($q){
                $query->where('group.groupName','LIKE', "%".$q."%")
                ->orWhere('project.projName','LIKE', "%".$q."%");
            })
            ->paginate(5); 
            
        } else {
            return redirect()->action('SchedAppController@index');
        }

        $data->appends(array(
            'q' => Input::get('q')
        ));
           
        return view('pages.approve_schedules.index')->withData($data);
    }

    public function schedApprovalStatus(Request $request) {
        if (is_null($request->input('opt'))){
            return redirect()->back()->withInput($request->all)->withErrors( 'Schedule approval failed.');
        }
        $stage = new Stage;
        $q = DB::table('schedule_approval')
        ->join('panel_group','panel_group.panelGroupID','=','schedule_approval.schedPanelGroupID')
        ->join('group','group.groupID','=','panel_group.panelCGroupID')
        ->join('project','project.projGroupID','=','group.groupID')
        ->join('account','account.accID','=','panel_group.panelAccID')
        ->where('account.accID','=',$request->input('acc'))
        ->where('panel_group.panelCGroupID','=',$request->input('grp'))
        ->where('panel_group.panelGroupType','=',$stage->current($request->input('grp')))
        ->first();
 
        if(in_array($q->projPVerdictNo,['2','3']) || $q->groupStatus!="Waiting for Schedule Approval") {
            $request->session()->flash('alert-danger', 'Schedule approval failed.');
            if(!Auth::user()) {
                return view('auth.login');
            }
            return view('pages.approve_schedules.index');
        }
        $approval = ScheduleApproval::find($q->schedAppID);
        if($request->input('opt')=='1') {
            $approval->isApproved = 1;
            $msg = 'The schedule request of group : ' . $q->groupName . ' was set to \'Available\'.';
        } else {
            $approval->isApproved = 2;
            $msg = 'The schedule request of group : ' . $q->groupName . ' was set to \'Not Available\'.';
        }

        $approval->schedAppMsg = !is_null($request->input('shortmsg')) ? $request->input('shortmsg') : '';
        
        try{
            DB::beginTransaction();
            $approval->save();
            $this->calcSchedStatus($request->input('grp'),$stage);
            DB::commit();
        } catch (Exception $e) {
            $request->session()->flash('alert-danger', 'Schedule approval failed.');
            if(!Auth::user()) {
                return view('auth.login');
            }
            return view('pages.approve_schedules.index');
        }
        $request->session()->flash('alert-success', $msg);
        if(!Auth::user()) {
            return view('auth.login');
        }
        return view('pages.approve_schedules.index');
    }

    private function calcSchedStatus($groupID,Stage $stage){
        $pMembersTotal = DB::table('schedule_approval')
        ->join('panel_group','panel_group.panelGroupID','=','schedule_approval.schedPanelGroupID')
        ->join('account','account.accID','=','panel_group.panelAccID')
        ->select('schedule_approval.isApproved')
        ->where('account.isActivePanel','=','1')
        ->where('panel_group.panelCGroupID','=',$groupID)
        ->where('panel_group.panelGroupType','=',$stage->current($groupID))
        ->count();

        $pMembersWaiting = DB::table('schedule_approval')
        ->join('panel_group','panel_group.panelGroupID','=','schedule_approval.schedPanelGroupID')
        ->join('account','account.accID','=','panel_group.panelAccID')
        ->select('schedule_approval.isApproved')
        ->where('account.isActivePanel','=','1')
        ->where('panel_group.panelCGroupID','=',$groupID)
        ->where('panel_group.panelGroupType','=',$stage->current($groupID))
        ->where('schedule_approval.isApproved','=','0')
        ->count();

        if($stage->current($groupID)=='Custom') {
        $chairPanelApp = DB::table('schedule_approval')
        ->join('panel_group','panel_group.panelGroupID','=','schedule_approval.schedPanelGroupID')
        ->join('account','account.accID','=','panel_group.panelAccID')
        ->select('schedule_approval.isApproved')
        ->where('account.isActivePanel','=','1')
        ->where('panel_group.panelCGroupID','=',$groupID)
        ->where('panel_group.panelGroupType','=',$stage->current($groupID))
        ->where('panel_group.panelIsChair','=','1')
        ->where('schedule_approval.isApproved','=','1')
        ->count();
        $chairDisapproved = DB::table('schedule_approval')
        ->join('panel_group','panel_group.panelGroupID','=','schedule_approval.schedPanelGroupID')
        ->join('account','account.accID','=','panel_group.panelAccID')
        ->select('schedule_approval.isApproved')
        ->where('account.isActivePanel','=','1')
        ->where('panel_group.panelCGroupID','=',$groupID)
        ->where('panel_group.panelGroupType','=',$stage->current($groupID))
        ->where('panel_group.panelIsChair','=','1')
        ->where('schedule_approval.isApproved','=','2')
        ->count();
        } elseif($stage->current($groupID)=='All') {
        $chairPanelApp = DB::table('schedule_approval')
        ->join('panel_group','panel_group.panelGroupID','=','schedule_approval.schedPanelGroupID')
        ->join('account','account.accID','=','panel_group.panelAccID')
        ->select('schedule_approval.isApproved')
        ->where('account.isActivePanel','=','1')
        ->where('panel_group.panelCGroupID','=',$groupID)
        ->where('panel_group.panelGroupType','=',$stage->current($groupID))
        ->where('account.isChairPanelAll','=','1')
        ->where('schedule_approval.isApproved','=','1')
        ->count();

        $chairDisapproved = DB::table('schedule_approval')
        ->join('panel_group','panel_group.panelGroupID','=','schedule_approval.schedPanelGroupID')
        ->join('account','account.accID','=','panel_group.panelAccID')
        ->select('schedule_approval.isApproved')
        ->where('account.isActivePanel','=','1')
        ->where('panel_group.panelCGroupID','=',$groupID)
        ->where('panel_group.panelGroupType','=',$stage->current($groupID))
        ->where('account.isChairPanelAll','=','1')
        ->where('schedule_approval.isApproved','=','2')
        ->count();
        }

        $panelMembersApp = DB::table('schedule_approval')
        ->join('panel_group','panel_group.panelGroupID','=','schedule_approval.schedPanelGroupID')
        ->join('account','account.accID','=','panel_group.panelAccID')
        ->select('schedule_approval.isApproved')
        ->where('account.isActivePanel','=','1')
        ->where('panel_group.panelCGroupID','=',$groupID)
        ->where('panel_group.panelGroupType','=',$stage->current($groupID))
        ->where('panel_group.panelIsChair','=','0')
        ->where('schedule_approval.isApproved','=','1')
        ->count();

        $panelDisapproved = DB::table('schedule_approval')
        ->join('panel_group','panel_group.panelGroupID','=','schedule_approval.schedPanelGroupID')
        ->join('account','account.accID','=','panel_group.panelAccID')
        ->select('schedule_approval.isApproved')
        ->where('account.isActivePanel','=','1')
        ->where('panel_group.panelCGroupID','=',$groupID)
        ->where('panel_group.panelGroupType','=',$stage->current($groupID))
        ->where('schedule_approval.isApproved','=','2')
        ->count();

        $sched = DB::table('schedule')
        ->join('group','group.groupID','=','schedule.schedGroupID')
        ->select('schedule.*')
        ->where('schedule.schedGroupID','=',$groupID)
        ->first();
        $schedstatus = Schedule::find($sched->schedID);
        $group = Group::find($groupID);
        $stg = DB::table('stage')->where('stage.stagePanel','=',$stage->current($groupID))
        ->first();
        $apprvl = null;
        if($stg->requireChairSched && $chairDisapproved) {
            $apprvl = false;//chair is required and chair has disapproved, result: disapprove
        } else if(($panelMembersApp + $chairPanelApp) >= $stg->minSchedPanel) {
            if($stg->requireChairSched && $chairPanelApp) {  
        //chair required, the total chair and panelmembers who has approved is 
        //greater or equal to minimum, result: approve 
            $apprvl = true;              
            } elseif(!$stg->requireChairSched) { //chair not required, minimum panel is met, result: approve 
                $apprvl = true;
            }
        } elseif($panelDisapproved > ($pMembersTotal - $stg->minSchedPanel)) {
        //total disapproval is greater than the total panel required 
        //less the minimum panel that is required, result: disapprove
            $apprvl = false;
        } 
        if(!is_null($apprvl)) {
            if($apprvl==true) {
                $this->approve_sched($group,$schedstatus);
            } else {
                $this->disapprove_sched($group,$schedstatus,$stage->current($groupID));
            }
        }
    }

    private function disapprove_sched(Group $group,Schedule $schedstatus,$type) {
        $schedstatus->schedStatus = "Not Ready";
        $group->groupStatus = 'Waiting for Submission';
        $pRes = new Project;
        $pRes->resetSchedApp($group->groupID,'-1',0);
        $notify = new Notification;
        $notify->NotifyStudentOnSchedDisapproved($group);
        $schedstatus->save();
        $group->save();
    }

    private function approve_sched(Group $group,Schedule $schedstatus) {
        $schedstatus->schedStatus = 'Ready';
        $group->groupStatus = 'Waiting for Final Schedule';
        $notify = new Notification;
        $notify->NotifyCoordOnSchedFinalize($group);
        $schedstatus->save();
        $group->save();
    }

    public function schedFinalize($groupID) {
        $update = DB::table('group')
        ->where('group.groupID','=',$groupID)
        ->update([
            'group.groupStatus' => '0'
        ]);
        $grp = Group::find($groupID);
        return redirect()->action('QuickViewController@index')->with('success','Schedule of the group of ' . $grp->groupName . 'was finalized successfully.');
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return redirect()->action('SchedAppController@index');
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
