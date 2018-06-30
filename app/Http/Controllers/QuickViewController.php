<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Auth;
use App\models\Project;
use App\models\ProjectApproval;
use App\models\AccountGroup;
use App\models\Schedule;
use App\models\ScheduleApproval;
use Illuminate\Support\Facades\Validator;
use DB;
use Illuminate\Support\Facades\Input;

class QuickViewController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $groups = DB::table('group')
        ->join('project','project.projGroupNo','=','group.groupNo')
        ->join('panel_verdict','panel_verdict.panelVerdictNo','=','project.projPVerdictNo')
        ->join('stage','stage.stageNo','=','project.projStageNo')
        ->join('account','group.groupCAdviserNo','=','account.accNo')
        ->select('group.*','project.*','account.*','stage.*','panel_verdict.*')
        ->paginate(3); 
        return view('pages.quick_view.index')->with('data',$groups);
    }

    public function search()
    {
        $q = Input::get('q');
      
        if($q != '') {
            $data = DB::table('group')
            ->join('project','project.projGroupNo','=','group.groupNo')
            ->join('panel_verdict','panel_verdict.panelVerdictNo','=','project.projPVerdictNo')
            ->join('stage','stage.stageNo','=','project.projStageNo')
            ->join('account','group.groupCAdviserNo','=','account.accNo')
            ->select('group.*','project.*','account.*','stage.*','panel_verdict.*')
            ->where('group.groupName','LIKE', "%".$q."%")
            ->orWhere(DB::raw('CONCAT(account.accFName," ",account.accMInitial," ",account.accLName," ",account.accTitle)'), 'LIKE', "%".$q."%")
            ->orWhere('group.groupStatus','LIKE', "%".$q."%")
            ->orWhere('panel_verdict.pVerdictDescription','LIKE', "%".$q."%")
            ->orWhere('stage.stageName','LIKE', "%".$q."%")
            ->paginate(3);
        } else {
            return redirect()->action('QuickViewController@index');
        }

        $data->appends(array(
            'q' => Input::get('q')
        ));
           
        return view('pages.quick_view.index')->with('data',$data);
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
        $data = DB::table('group')
        ->join('schedule','schedule.schedGroupNo','=','group.groupNo')
        ->join('panel_group','panel_group.panelCGroupNo','=','group.groupNo')
        ->join('schedule_approval','schedule_approval.schedPGroupNo','=','panel_group.panelGroupNo')
        ->join('account','account.accNo','=','panel_group.panelAccNo')
        ->select('group.*','schedule.*','schedule_approval.*','panel_group.*','account.*')
        ->where('group.groupNo','=',$id)
        ->first();
        //return dd($data);
        return view('pages.quick_view.modify-schedule')->with('data',$data);
    }

    public function modifyProjApp($id) 
    {
        $data = DB::table('group')
        ->join('panel_group','panel_group.panelCGroupNo','=','group.groupNo')
        ->join('project_approval','project_approval.projAppPGroupNo','=','panel_group.panelGroupNo')
        ->join('account','account.accNo','=','panel_group.panelAccNo')
        ->select('account.*','project_approval.*','panel_group.*','group.*')
        ->where('group.groupNo','=',$id)
        ->get();
        //return dd($data);
        return view('pages.quick_view.modify-projApp')->with('data',$data);
    }

    public function modifyProjAppUpdate(Request $request)
    {
        $data = DB::table('group')
        ->join('panel_group','panel_group.panelCGroupNo','=','group.groupNo')
        ->join('project_approval','project_approval.projAppPGroupNo','=','panel_group.panelGroupNo')
        ->join('account','account.accNo','=','panel_group.panelAccNo')
        ->select('account.*','project_approval.*','panel_group.*','group.*')
        ->where('group.groupNo','=',$request->input('groupNo'))
        ->get();

        try {
            DB::beginTransaction();
            foreach($data as $pmembers) {
                $pj1 = ProjectApproval::find($pmembers->projAppNo);
                $x = 'proj_app_' . $pmembers->accNo;
                $y = 'proj_rlink_' . $pmembers->accNo;
                $v = $request->input($x);
                $v2 = $request->input($y);
                $pj1->isApproved = $v;
                if(is_null($v2)) {
                    $pj1->revisionLink = '';
                } else {
                    $pj1->revisionLink = $v2;
                }        
                $pj1->save();
            }
            DB::commit();
            $request->session()->flash('alert-success', 'Project Approval Information was Updated!');
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->withInput()->with('error', 'Project Approval Information was not Updated!');
        }
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
        //return dd($request->all());
 
        $validator = Validator::make($request->all(), [
            'date' => ['required','date_format:Y-m-d'],
            'starting_time' => ['required','date_format:H:i'],
            'ending_time' => ['required','date_format:H:i'],
            'place' => ['required','max:100'],
            'schedule_type' => ['required','max:20'],
            'schedule_status' => ['required','max:20'],
        ]);
        if ($validator->fails()) {
			return redirect()->back()->withInput()->withErrors($validator);
        } 

        $data = DB::table('group')
        ->join('schedule','schedule.schedGroupNo','=','group.groupNo')
        ->join('panel_group','panel_group.panelCGroupNo','=','group.groupNo')
        ->join('schedule_approval','schedule_approval.schedPGroupNo','=','panel_group.panelGroupNo')
        ->join('account','account.accNo','=','panel_group.panelAccNo')
        ->select('group.*','schedule.*','schedule_approval.*','panel_group.*','account.*')
        ->where('group.groupNo','=',$id)
        ->get();
        
        try {
            DB::beginTransaction();
            $sc0 = Schedule::find($data[0]->schedNo);
            $sc0->schedDate = $request->input('date');
            $sc0->schedTimeStart = $request->input('starting_time');
            $sc0->schedTimeEnd = $request->input('ending_time');
            $sc0->schedPlace = $request->input('place');
            $sc0->schedType = $request->input('schedule_type');
            $sc0->schedStatus = $request->input('schedule_status');
            $sc0->save();
            foreach($data as $pmembers) {
                $sc1 = ScheduleApproval::find($pmembers->schedAppNo);
                $x = 'sched_app_' . $pmembers->accNo;
                $v = $request->input($x);
                $sc1->isApproved = $v;
                $sc1->save();
            }
            DB::commit();
            $request->session()->flash('alert-success', 'Schedule Information was Updated!');
            return redirect()->back();
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Schedule Information was not Updated!');
            DB::rollback();
        }
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
