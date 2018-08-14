<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;
use App\models\Stage;
use Auth;
use DB;

class Dashboard extends Model 
{
    public function getDashboardPanel() {
        $user_id = Auth::user()->getId();
        $ValidGroupStatus = ['Waiting for Schedule Approval','Waiting for Project Approval'];
        $stage = new Stage;

        $proj = DB::table('group')
        ->join('project','project.projGroupID','=','group.groupID')
        ->join('stage','stage.stageNo','=','project.projStageNo')
        ->join('panel_group','panel_group.panelCGroupID','=','group.groupID')
        ->join('project_approval','project_approval.projAppPanelGroupID','=','panel_group.panelGroupID')
        ->join('account','account.accID','=','panel_group.panelAccID')
        ->join('panel_verdict','panel_verdict.panelVerdictNo','=','project.projPVerdictNo')
        ->where('panel_group.panelAccID','=',$user_id)
        ->whereIn('group.groupStatus', $ValidGroupStatus)
        ->whereIn('project.projPVerdictNo',['2','3'])
        ->where('project_approval.isApproved','=','0')
        ->count();

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
        ->count();
  
        $substatus = ['Submitted to Content Adviser'];
        $adv = DB::table('group')
        ->join('project','project.projGroupID','=','group.groupID')
        ->join('panel_verdict','panel_verdict.panelVerdictNo','=','project.projPVerdictNo')
        ->join('schedule','schedule.schedGroupID','=','group.groupID')
        ->join('account','account.accID','=','group.groupCAdviserID')
        ->select('schedule.*','account.*','project.*','group.*','panel_verdict.*')
        ->where('account.accType','=','2')
        ->where('group.groupCAdviserID','=',$user_id)
        ->whereIn('group.groupStatus',$substatus)
        ->count();
        $data = ['sched'=>$sched,'proj'=>$proj,'adv'=>$adv];

        return $data;
    }

    public function getDashboardCoord() {
        /*
        this.NotifyCoordOnSchedRequest = ko.observableArray([]).extend({ notify: 'always' });
        this.NotifyCoordOnNextStage = ko.observableArray([]).extend({ notify: 'always' });
        this.NotifyCoordOnSchedFinalize = ko.observableArray([]).extend({ notify: 'always' });
        */
        $status = ['Waiting for Schedule Request'];
        $verdict = ['2','3','7'];
        $SchedRequest = DB::table('group')
        ->join('project','projGroupID','=','group.groupID')
        ->whereIn('group.groupStatus',$status)
        ->whereNotIn('project.projPVerdictNo',$verdict)
        ->count();
        
        $status = ['Ready for Next Stage'];
        $NextStage = DB::table('group')
        ->whereIn('group.groupStatus',$status)
        ->count();

        $status = ['Waiting for Final Schedule'];
        $SchedFinalize = DB::table('group')
        ->whereIn('group.groupStatus',$status)
        ->count();

        $data = ['SchedRequest'=>$SchedRequest,'NextStage'=>$NextStage,'SchedFinalize'=>$SchedFinalize];
        return $data;
    }

}
