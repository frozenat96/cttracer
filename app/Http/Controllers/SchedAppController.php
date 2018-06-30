<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\models\ScheduleApproval;
use App\models\Schedule;
use App\models\Group;
use Auth;
use DB;
use Illuminate\Support\Facades\Input;
use App\Events\eventTrigger;
use App\mail\SendMail;
use Mail;
use App\Notifications\NotifyCoordOnSchedFinalize;

class SchedAppController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // 
        $user_id = Auth::id();

        $sched = DB::table('schedule_approval')
        ->join('panel_group','panel_group.panelGroupNo','=','schedule_approval.schedPGroupNo')
        ->join('group','group.groupNo','=','panel_group.panelCGroupNo')
        ->join('project','project.projGroupNo','=','group.groupNo')
        ->join('panel_verdict','panel_verdict.panelVerdictNo','=','project.projPVerdictNo')
        ->join('schedule','schedule.schedGroupNo','=','group.groupNo')
        ->join('account','account.accNo','=','panel_group.panelAccNo')
        ->select('schedule_approval.*','schedule.*','panel_group.*','account.*','project.*','group.*','panel_verdict.*')
        ->where('panel_group.panelAccNo','=',$user_id)
        ->whereIn('group.groupStatus', ['Approved by Content Adviser'])
        ->whereNotIn('project.projPVerdictNo',['2','3'])
        ->where('schedule.schedStatus','!=','Finished')
        ->paginate(3); 
        //return $this->calcSchedStatus($sched[0]->panelCGroupNo);
        return view('pages.approve_schedules.index')->with('data',$sched);
    }

    public function search() {
        $user_id = Auth::id();
        $q = Input::get('q');
      
        if($q != '') {
            $data = DB::table('schedule_approval')
            ->join('panel_group','panel_group.panelGroupNo','=','schedule_approval.schedPGroupNo')
            ->join('group','group.groupNo','=','panel_group.panelCGroupNo')
            ->join('project','project.projGroupNo','=','group.groupNo')
            ->join('panel_verdict','panel_verdict.panelVerdictNo','=','project.projPVerdictNo')
            ->join('schedule','schedule.schedGroupNo','=','group.groupNo')
            ->join('account','account.accNo','=','panel_group.panelAccNo')
            ->select('schedule_approval.*','schedule.*','panel_group.*','account.*','project.*','group.*','panel_verdict.*')
            ->whereIn('group.groupStatus', ['Approved by Content Adviser'])
            ->where('panel_group.panelAccNo','=',$user_id)
            ->where('schedule.schedStatus','!=','Finished')
            ->whereNotIn('project.projPVerdictNo',['2','3'])
            ->where('group.groupName','LIKE', "%".$q."%")
            ->paginate(3); 
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
            return redirect()->back()->with('error', 'Schedule approval failed.');
        } 
        $q = DB::table('schedule_approval')
        ->join('panel_group','panel_group.panelGroupNo','=','schedule_approval.schedPGroupNo')
        ->join('group','group.groupNo','=','panel_group.panelCGroupNo')
        ->join('account','account.accNo','=','panel_group.panelAccNo')
        ->select('schedule_approval.*','account.*','panel_group.*','group.*')
        ->where('account.accNo','=',$request->input('acc'))
        ->where('panel_group.panelCGroupNo','=',$request->input('grp'))
        ->first();
        $approval = ScheduleApproval::find($q->schedAppNo);
        if($request->input('opt')=='1') {
            $approval->isApproved = 1;
            $msg = 'The schedule of group : ' . $q->groupName . ' was approved.';
        } else {
            $approval->isApproved = 2;
            $msg = 'The schedule of group : ' . $q->groupName . ' was disapproved.';
        }
        if(!is_null($request->input('shortmsg'))) {
            $approval->schedAppMsg = $request->input('shortmsg');
        } else {
            $approval->schedAppMsg = '';
        }
        try{
            DB::beginTransaction();
            if($approval->save() && $this->calcSchedStatus($request->input('grp'))) {
                DB::commit();
                return redirect()->back()->with('success', $msg);
            } else {
                DB::rollback();
                return redirect()->back()->with('error', 'Schedule approval failed.');
            }

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Schedule approval failed.');
        }
        
        
    }

    public function calcSchedStatus($groupNo){
        $pMembersWaiting = DB::table('schedule_approval')
        ->join('panel_group','panel_group.panelGroupNo','=','schedule_approval.schedPGroupNo')
        ->select('schedule_approval.isApproved')
        ->where('panel_group.panelCGroupNo','=',$groupNo)
        ->where('schedule_approval.isApproved','=','0')
        ->count();

        $chairPanelApp = DB::table('schedule_approval')
        ->join('panel_group','panel_group.panelGroupNo','=','schedule_approval.schedPGroupNo')
        ->select('schedule_approval.isApproved')
        ->where('panel_group.panelCGroupNo','=',$groupNo)
        ->where('panel_group.panelIsChair','=','1')
        ->where('schedule_approval.isApproved','=','1')
        ->count();
        $panelMembersApp = DB::table('schedule_approval')
        ->join('panel_group','panel_group.panelGroupNo','=','schedule_approval.schedPGroupNo')
        ->select('schedule_approval.isApproved')
        ->where('panel_group.panelCGroupNo','=',$groupNo)
        ->where('panel_group.panelIsChair','=','0')
        ->where('schedule_approval.isApproved','=','1')
        ->count();

        $sched = DB::table('schedule')
        ->join('group','group.groupNo','=','schedule.schedGroupNo')
        ->select('schedule.*')
        ->where('schedule.schedGroupNo','=',$groupNo)
        ->first();
        $schedstatus = Schedule::find($sched->schedNo);
        $group = Group::find($groupNo);
        if($pMembersWaiting <= 1) {
            if($chairPanelApp && $panelMembersApp) {
                $schedstatus->schedStatus = 'Ready';
                $group->groupStatus = 'Waiting for Final Schedule';
                $cc = DB::table('account')
                ->where('account.accType','=','1')
                ->first();
                $x = User::find($cc->accNo);
                $x->notify(new NotifyCoordOnSchedFinalize($group));
                event(new eventTrigger('trigger'));
                $z = ['grp'=>$group->groupNo,'acc'=>$cc->accNo,'to'=>$cc->accEmail];

            } elseif(!$panelMembersApp) {
                $schedstatus->schedStatus = "Not Ready";
                $group->groupStatus = 'Waiting';
                $this->resetSchedApp($groupNo);
            }
            if(!$pMembersWaiting) {
                if((!$chairPanelApp) || !$panelMembersApp) {
                    $schedstatus->schedStatus = "Not Ready";
                    $group->groupStatus = 'Waiting';
                    $this->resetSchedApp($groupNo);
                }
            }    
        }
        if($schedstatus->save() && $group->save()) {
            return 1;
        } else {
            return 0;
        }
    }

    public function resetSchedApp($groupNo) {
        $update = DB::table('schedule_approval')
        ->join('panel_group','panel_group.panelGroupNo','=','schedule_approval.schedPGroupNo')
        ->where('panel_group.panelCGroupNo','=',$groupNo)
        ->update([
            'schedule_approval.isApproved' => '0'
        ]);
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

    public function schedApprovalStatus_e(Request $request) {
        if (is_null($request->input('opt'))){
            return redirect()->back()->with('error', ['Schedule approval failed.']);
        } 
        $q = DB::table('schedule_approval')
        ->join('panel_group','panel_group.panelGroupNo','=','schedule_approval.schedPGroupNo')
        ->join('group','group.groupNo','=','panel_group.panelCGroupNo')
        ->join('account','account.accNo','=','panel_group.panelAccNo')
        ->select('schedule_approval.*','account.*','panel_group.*','group.*')
        ->where('account.accNo','=',$request->input('acc'))
        ->where('panel_group.panelCGroupNo','=',$request->input('grp'))
        ->first();
        $approval = ScheduleApproval::find($q->schedAppNo);
        if($request->input('opt')=='1') {
            $approval->isApproved = 1;
            $msg = 'The schedule of group : ' . $q->groupName . ' was approved.';
        } else {
            $approval->isApproved = 2;
            $msg = 'The schedule of group : ' . $q->groupName . ' was disapproved.';
        }
        if(!is_null($request->input('shortmsg'))) {
            $approval->schedAppMsg = $request->input('shortmsg');
        }
        try{
            DB::beginTransaction();
            if($approval->save() && $this->calcSchedStatus($request->input('grp'))) {
                DB::commit();
                return redirect('/')->with('success', $msg);
            } else {
                DB::rollback();
                return redirect('/')->with('error', 'Schedule approval failed.');
            }

        } catch (\Exception $e) {
            DB::rollback();
            return redirect('/')->with('error', 'Schedule approval failed.');
        }
        
        
    }
}
