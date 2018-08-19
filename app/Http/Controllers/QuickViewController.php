<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Auth;
use App\models\Stage;
use App\models\Group;
use App\models\Project;
use App\models\ProjectApproval;
use App\models\AccountGroup;
use App\models\Schedule;
use App\models\ScheduleApproval;
use App\models\PanelVerdict;
use App\models\Notification;
use App\models\AccessControl;
use App\models\GroupHistory;
use Illuminate\Support\Facades\Validator;
use DB;
use Illuminate\Support\Facades\Input;
use Exception;
use Illuminate\Validation\Rule;
use Session;
use Spatie\GoogleCalendar\Event;
use Carbon\Carbon;

class QuickViewController extends Controller
{
    public function __construct()
    {
        $ac = new AccessControl;
        $accesscontrol = $ac->status; 
        if($accesscontrol == true) {
            $this->middleware('auth');
            $this->middleware('roles', ['roles'=> ['Capstone Coordinator']]);
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
        $groups = $this->getIndex();                
        return view('pages.quick_view.index')->with('data',$groups);
    }

    private function getIndex() {
        $user_id = Auth::user()->getId();
        $user = User::find($user_id);
        return $groups = DB::table('group')
        ->join('project','project.projGroupID','=','group.groupID')
        ->join('panel_verdict','panel_verdict.panelVerdictNo','=','project.projPVerdictNo')
        ->join('stage','stage.stageNo','=','project.projStageNo')
        ->join('account','group.groupCAdviserID','=','account.accID')
        ->when(($user->accType == 1), function ($query) use ($user_id) {
            return $query->where('group.groupCoordID','=',$user_id);
        })
        ->orderBy('group.groupName')
        ->paginate(5); 
    }

    public function search($query = null)
    {
        $user_id = Auth::user()->getId();
        $user = User::find($user_id);
        $q = Input::get('q');
        if(!is_null($query)) {
        $q = $query;
        }

        if($q != '') {
            $data = DB::table('group')
            ->join('project','project.projGroupID','=','group.groupID')
            ->join('panel_verdict','panel_verdict.panelVerdictNo','=','project.projPVerdictNo')
            ->join('stage','stage.stageNo','=','project.projStageNo')
            ->join('account','group.groupCAdviserID','=','account.accID')
            ->select('group.*','project.*','account.*','stage.*','panel_verdict.*')
            ->where(function ($query) use ($q){
                $query->where('group.groupName','LIKE', "%".$q."%")
                ->orWhere(DB::raw('CONCAT(account.accFName," ",account.accMInitial," ",account.accLName," ",account.accTitle)'), 'LIKE', "%".$q."%")
                ->orWhere('group.groupStatus','LIKE', "%".$q."%")
                ->orWhere('project.projName','LIKE', "%".$q."%")
                ->orWhere('panel_verdict.pVerdictDescription','LIKE', "%".$q."%")
                ->orWhere('stage.stageName','LIKE', "%".$q."%");
            })
            ->when(($user->accType == 1), function ($query) use ($user_id) {
                return $query->where('group.groupCoordID','=',$user_id);
            })
            ->orderBy('group.groupName')
            ->paginate(5);
        } else {
            //return dd(0);
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
        ->join('schedule','schedule.schedGroupID','=','group.groupID')
        ->join('panel_group','panel_group.panelCGroupID','=','group.groupID')
        ->join('schedule_approval','schedule_approval.schedPanelGroupID','=','panel_group.panelGroupID')
        ->join('account','account.accID','=','panel_group.panelAccID')
        ->select('group.*','schedule.*','schedule_approval.*','panel_group.*','account.*')
        ->where('group.groupID','=',$id)
        ->first();
        //return dd($data);
        return view('pages.quick_view.modify-schedule')->with('data',$data);
    }

    public function modifyProjApp($id) 
    {
        $stage = new Stage;
        $data = DB::table('group')
        ->join('panel_group','panel_group.panelCGroupID','=','group.groupID')
        ->join('project_approval','project_approval.projAppPanelGroupID','=','panel_group.panelGroupID')
        ->join('account','account.accID','=','panel_group.panelAccID')
        ->select('account.*','project_approval.*','panel_group.*','group.*')
        ->where('account.isActivePanel','=','1')
        ->where('panel_group.panelCGroupID','=',$id)
        ->where('panel_group.panelGroupType','=',$stage->current($id))
        ->get();
        //return dd($data);
        return view('pages.quick_view.modify-projApp')->with('data',$data);
    }

    public function modifyProjAppUpdate(Request $request)
    {
        $stage = new Stage;
        $data = DB::table('group')
        ->join('panel_group','panel_group.panelCGroupID','=','group.groupID')
        ->join('project_approval','project_approval.projAppPanelGroupID','=','panel_group.panelGroupID')
        ->join('account','account.accID','=','panel_group.panelAccID')
        ->select('account.*','project_approval.*','panel_group.*','group.*')
        ->where('account.isActivePanel','=','1')
        ->where('panel_group.panelGroupType','=',$stage->current($request->input('groupID')))
        ->where('group.groupID','=',$request->input('groupID'))
        ->get();

        try {
            DB::beginTransaction();
            foreach($data as $pmembers) {
                $pj1 = ProjectApproval::find($pmembers->projAppID);
                $approval = $request->input('proj_app_' . $pmembers->accID);
                $documentLink = $request->input('proj_rlink_' . $pmembers->accID);
                $comment = $request->input('proj_comment_' . $pmembers->accID);

                $pj1->isApproved = !is_null($approval) ? $approval : '3';      
                if(!is_null($documentLink)) {
                    $validator = Validator::make($request->all(), [
                        "proj_rlink_{$pmembers->accID}" => ['max:150','active_url'],
                    ]);
                    if($validator->fails()) {
                        return redirect()->back()->withInput($request->all)->withErrors($validator);
                    }
                }
                $pj1->revisionLink = !is_null($documentLink) ? $documentLink : '';
                $pj1->projAppComment = !is_null($comment) ? $comment : '';    
                $pj1->save();
            }
            DB::commit();
            $request->session()->flash('alert-success', 'Project Approval Information was Updated!');
            return redirect()->back();
        } catch (Exception $e) {
            return dd($e);
            DB::rollback();
            return redirect()->back()->withErrors( 'Project Approval Information was not Updated!');
        }
    }

    private function getNextStage($groupID) {
            $stage = new Stage;
            $group = Group::find($groupID);
            $data = DB::table('group')
            ->join('project','project.projGroupID','=','group.groupID')
            ->join('schedule','schedule.schedGroupID','=','group.groupID')
            ->join('stage','stage.stageNo','=','project.projStageNo')
            ->where('group.groupID','=',$groupID)
            ->first();
            $schedule_approval = DB::table('schedule_approval')
            ->join('panel_group','panel_group.panelGroupID','=','schedule_approval.schedPanelGroupID')
            ->join('group','group.groupID','=','panel_group.panelCGroupID')
            ->where('panel_group.panelCGroupID','=',$groupID)
            ->update([
                'schedule_approval.isApproved' => '3'
            ]); //Set the schedule approval to 3 (disabled)

            $project_approval = DB::table('project_approval')
            ->join('panel_group','panel_group.panelGroupID','=','project_approval.projAppPanelGroupID')
            ->join('group','group.groupID','=','panel_group.panelCGroupID')
            ->where('panel_group.panelCGroupID','=',$groupID)
            ->update([
                'project_approval.isApproved' => '3'
            ]); //Set the project approval to 3 (disabled)

            $project = Project::find($data->projID);
            $schedule = Schedule::find($data->schedID);
            $maxStage = Stage::max('stageNo');
            $stgName = Stage::find($data->projStageNo);
            $msg ='';
            $notify = new Notification;

            //Add to group history
            $grpHist = new GroupHistory;
            if($data->projStageNo < $maxStage) {
                $group->groupStatus = 'Waiting for Submission';
                $project->projStageNo = $data->stageNo + 1;
                $project->projPVerdictNo = '1';
                $schedule->schedStatus = 'Not Ready';
                $msg = 'The group of ' . $group->groupName . ' is now ready for the next stage.';
                $notify->NotifyStudentOnNextStage($group); 
                $grpHistActivity = "The {$stgName->stageName} of the group was approved";
                $grpHist->add($group,$grpHistActivity);
            } else {
                $group->groupStatus = 'Waiting for Project Completion';
                $project->projPVerdictNo = '7';
                $schedule->schedStatus = 'Not Ready';
                $msg = 'The group of ' . $group->groupName . ' is now waiting for project completion.'; 
                $notify->NotifyStudentOnCompletion($group);         
            }
            $group->save();
            $project->save();
            $schedule->save();
            return $msg;
    }

    public function nextStage(Request $request) {
        try {
            DB::beginTransaction();
            $msg = $this->getNextStage($request->input('grp'));
            DB::commit();
        } catch(Exception $e) {
            DB::rollback();
            return redirect()->back()->withErrors('The group information was not updated.');
        }
        //return dd($msg);
        $g = $this->getIndex();
        return view('pages.quick_view.index')->with('data',$g)->with('success2',['The group information was updated!',$msg]);
        //return redirect()->back()->with('success',['The group information was updated!',$msg]);
    }

    public function setToProjComplete(Request $request) {
        try {
            DB::beginTransaction();
            $group = Group::find($request->input('grp'));
            $group->groupStatus = 'Finished';
            $project = DB::table('project')
            ->where('project.projGroupID','=',$request->input('grp'))
            ->first();
            $project = Project::find($project->projID);
            $project->projPVerdictNo = '7';
            $project->save();
            $group->save();
            $notify = new Notification;
            $notify->NotifyStudentOnFinish($group);

            //Add to group history
            $grpHist = new GroupHistory;
            $grpHistActivity = "The project of the group was completed.";
            $grpHist->add($group,$grpHistActivity);
            DB::commit();
        } catch(Exception $e) { 
            DB::rollback();
            return redirect()->back()->withErrors('The group information was not updated.');
        }
        $g = $this->getIndex();
        return view('pages.quick_view.index')->with('data',$g)->with('success2',['The group information was updated.','The group of ' . $group->groupName . ' is now finished.']);
        //return redirect()->back()->with('success',['The group information was updated.','The group of ' . $group->groupName . ' is now finished.']);
    }

    public function finalizeSchedule(Request $request) {
        try {
            DB::beginTransaction();   
            $group = Group::find($request->input('grp'));
            $group->groupStatus = 'Ready for Defense';

            //find the schedule
            $sc0 = DB::table('schedule')
            ->where('schedule.schedGroupID','=',$request->input('grp'))
            ->first();
            $sc0 = Schedule::find($sc0->schedID);

            //find the stage of the group
            $stg = DB::table('project')
            ->join('stage','stageNo','=','project.projStageNo')
            ->where('project.projGroupID','=',$request->input('grp'))
            ->first();

            //add the event on google calendar
            $event = new Event;
            $event->addLocation($sc0->schedPlace);
            $event->startDateTime = $tstart = new Carbon("{$sc0->schedDate} {$sc0->schedTimeStart}");
            $event->endDateTime = $tend = $tstart->addMinutes($stg->stageDefDuration);
            $sc0->schedTimeEnd = $tend;
            $event->name = "{$sc0->schedType}({$group->groupName}); {$event->startDateTime->format("H:i A")} - {$tend->format("H:i A")} @ {$sc0->schedPlace};";
            $calendarEvent = $event->save();
            $sc0->schedEventID = $calendarEvent->id;
            $sc0->save();

            $group->save();
            $notify = new Notification;
            $notify->NotifyAllOnReady($group);
            DB::commit();
        } catch(Exception $e) {
            DB::rollback();
            return redirect()->back()->withErrors('The group information was not updated.');
        }
        $g = $this->getIndex();
        return view('pages.quick_view.index')->with('data',$g)->with('success2',['The group information was updated.','The group of ' . $group->groupName . ' is now ready for defense.']);
        
    }

    public function setProjectVerdictIndex($groupID) {
        $data = $this->getProjectVerdictIndex($groupID);
        return view('pages.quick_view.set-panel-verdict')->with('data',$data);
    }

    private function getProjectVerdictIndex($groupID) {
        $group = DB::table('group')
        ->join('project','project.projGroupID','=','group.groupID')
        ->join('account','account.accID','=','groupCAdviserID')
        ->select('group.*','project.*','account.*')
        ->where('group.groupID','=',$groupID)
        ->first();
        $pverdict = DB::table('panel_verdict')->get();
        $data = [
            'group'=>$group,
            'panel_verdict'=>$pverdict
        ];
        return $data;
    }

    public function setProjectVerdict(Request $request) {
        try {
            $groupID = $request->input('grp');
            $grpHist = new GroupHistory;
            DB::beginTransaction();
            $validPanelVerdict = DB::table('panel_verdict')
            ->pluck('panelVerdictNo');
            $validator = Validator::make($request->all(), [
                'panel_verdict' => ['required','Integer',Rule::In($validPanelVerdict->all())],
            ]);
            if ($validator->fails()) {
                return redirect()->back()->withInput($request->all)->withErrors($validator);
            } 
            $group = Group::find($groupID);
            $project = DB::table('project')
            ->where('project.projGroupID','=',$groupID)
            ->first();
            $schedule = DB::table('schedule')
            ->where('schedule.schedGroupID','=',$groupID)
            ->first();
            $msg = '';
            $project = Project::find($project->projID);
            $schedule = Schedule::find($schedule->schedID);
            if(in_array($request->input('panel_verdict'),['1','2','3','4','5','6'])) {
                $group->groupStatus = 'Waiting for Submission';
                $project->projPVerdictNo = $request->input('panel_verdict');
                $schedule->schedStatus = 'Finished';
                $group->save();
                $project->save();
                $schedule->save();
                DB::commit();
                $pv = PanelVerdict::find($request->input('panel_verdict'));
                $msg = 'The group\'s panel verdict is now set to \'' . $pv->pVerdictDescription . '\'.';

                if($request->input('panel_verdict')=='4') {
                    $project->projStageNo = 1;
                    $grpHistActivity = "The group was advised to re-propose a new project.";
                    $grpHist->add($group,$grpHistActivity);
                }
                
            } elseif(in_array($request->input('panel_verdict'),['7'])) {
                $msg = $this->getNextStage($groupID);
                $grp = Group::find($groupID);
            } elseif(in_array($request->input('panel_verdict'),['8'])) {
                //check the stage if equal to 1
                if($project->projStageNo == 1) {

                } elseif($project->projStageNo > 1) {
                    //decrement
                    $project->projStageNo = $project->projStageNo - 1;
                }
                $group->groupStatus = 'Waiting for Submission';
                $project->projPVerdictNo = '1';
                $project->save();
                $group->save();
                //store in group history
            }
            DB::commit();
        } catch(Exception $e) {
            DB::rollback();
            return redirect()->back()->withInput($request->all)->withErrors('The group information was not updated!');
        }
        $d = $this->getProjectVerdictIndex($groupID);
        return view('pages.quick_view.set-panel-verdict')->with('data',$d)->with('success2',['The group information was updated!',$msg]);
        //return redirect()->back()->with('success2',['The group information was updated!',$msg]);
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    //Modify Schedule function
    public function update(Request $request, $id)
    {
        try {
            DB::beginTransaction();  
            $validStatus = ['Ready','Not Ready','Finished'];
            $validType = ['Oral Defense','Round Table'];
            $validator = Validator::make($request->all(), [
                'date' => ['required','date_format:Y-m-d'],
                'starting_time' => ['required','date_format:H:i'],
                'ending_time' => ['required','date_format:H:i','after:starting_time'],
                'place' => ['required','max:100'],
                'schedule_type' => ['required','max:20',Rule::In($validType)],
                'schedule_status' => ['required','max:20',Rule::In($validStatus)],
            ]);
            if ($validator->fails()) {
                return redirect()->back()->withInput($request->all)->withErrors($validator);
            } 
            $stg = new Stage;
            $data = DB::table('group')
            ->join('schedule','schedule.schedGroupID','=','group.groupID')
            ->join('panel_group','panel_group.panelCGroupID','=','group.groupID')
            ->join('schedule_approval','schedule_approval.schedPanelGroupID','=','panel_group.panelGroupID')
            ->join('account','account.accID','=','panel_group.panelAccID')
            ->select('group.*','schedule.*','schedule_approval.*','panel_group.*','account.*')
            ->where('group.groupID','=',$id)
            ->where('panel_group.panelGroupType','=',$stg->current($id))
            ->get();

            $sc0 = Schedule::find($data[0]->schedID);
            $sc0->schedDate = $request->input('date');
            $sc0->schedTimeStart = $request->input('starting_time');
            $sc0->schedTimeEnd = $request->input('ending_time');
            $sc0->schedPlace = $request->input('place');
            $sc0->schedType = $request->input('schedule_type');
            $sc0->schedStatus = $request->input('schedule_status');
            $sc0->save();

            //update the google calendar
            $event = Event::find($sc0->schedEventID);
            if(!is_null($event)) {
                $event->startDateTime = $tstart = new Carbon("{$sc0->schedDate} {$sc0->schedTimeStart}");
                $event->endDateTime = $tend = new Carbon("{$sc0->schedDate} {$sc0->schedTimeEnd}");
                $event->name = "{$sc0->schedType} for the group of {$data[0]->groupName}, {$sc0->schedPlace} -- {$sc0->schedTimeStart} - {$sc0->schedTimeEnd}";
                $event->addLocation($sc0->schedPlace);
                $event->save();
            }
         
            foreach($data as $pmembers) {
                $sc1 = ScheduleApproval::find($pmembers->schedAppID);
                $approval = $request->input('sched_app_' . $pmembers->accID);
                $short_message = $request->input('sched_comment_' . $pmembers->accID);
                $sc1->isApproved = !is_null($approval) ? $approval : '-1';
                $sc1->schedAppMsg = !is_null($short_message) ? $short_message : '';
                $sc1->save();
            }
            DB::commit();
            $request->session()->flash('alert-success', 'Schedule Information was Updated!');
            return redirect()->back();
        } catch (Exception $e) {
            return dd($e);
            return redirect()->back()->withInput($request->all)->withErrors( 'Schedule Information was not Updated!');
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
