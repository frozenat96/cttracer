<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Auth;
use DB;
use Illuminate\Support\Facades\Input;

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
        ->join('schedule','schedule.schedNo','=','schedule_approval.schedAppSchedNo')
        ->join('account','account.accNo','=','panel_group.panelAccNo')
        ->select('schedule_approval.*','schedule.*','panel_group.*','account.*','project.*','group.*','account.*')
        ->where('group.groupStatus','=','Submitted For Panel Approval')
        ->where('panel_group.panelAccNo','=',$user_id)
        ->where('schedule.schedStatus','!=','Finished')
        ->paginate(3); 
        //return $this->calcSchedStatus($sched[0]->panelCGroupNo);
        //return dd($sched);
        return view('pages.approve_schedules.index')->with('data',$sched);
    }

    public function calcSchedStatus($groupNo){
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
        if($chairPanelApp && $panelMembersApp) {
            return 1;
        } else {
            return 0;
        }
    }

    public function approve()
    {
        
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
