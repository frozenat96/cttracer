<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\models\Group;
use App\models\GroupHistory;
use App\models\Project;
use App\models\PanelGroup;
use App\models\Schedule;
use App\models\ScheduleApproval;
use App\models\ProjectApproval;
use App\models\AccessControl;
use DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Webpatser\Uuid\Uuid;
use Auth;
use Exception;

class GroupController extends Controller
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
        $groups = DB::table('group')
        ->join('project','project.projGroupID','=','group.groupID')
        ->join('account','group.groupCAdviserID','=','account.accID')
        ->select('group.*','project.*','account.*')
        ->orderBy('group.groupName')
        ->paginate(10); 
        $q = Input::get('status');
        $msg = Input::get('statusMsg');

        if(!is_null($q) && $q==1) {
            return view('pages.groups.index')->with('data',$groups)->with('success2',$msg);
        } elseif(!is_null($q) && $q==0) {
            return view('pages.groups.index')->with('data',$groups)->with('error',$msg);
        }
        return view('pages.groups.index')->with('data',$groups);
    }

    public function search()
    {
        $q = Input::get('q');
      
        if($q != '') {
            $data = DB::table('group')
            ->join('account','account.accID','=','group.groupCAdviserID')
            ->join('project','project.projGroupID','=','group.groupID')
            ->select('account.*','group.*','project.*')
            ->where('group.groupName','LIKE', "%".$q."%")
            ->orWhere(DB::raw('CONCAT(account.accFName," ",account.accMInitial," ",account.accLName," ",account.accTitle)'), 'LIKE', "%".$q."%")
            ->orWhere('group.groupStatus','LIKE', "%".$q."%")
            ->orWhere('project.projName','LIKE', "%".$q."%")
            ->orderBy('group.groupName')
            ->paginate(10);
        } else {
            return redirect()->action('GroupController@index');
        }

        $data->appends(array(
            'q' => Input::get('q')
        ));
           
        return view('pages.groups.index')->withData($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $panel_members = DB::table('account')
        ->where('account.accType','=','2')
        ->get();

        $stage = DB::table('stage')->get();
        $pverdict = DB::table('panel_verdict')->get();
        $data = [
            'panel_members'=>$panel_members,
            'stage'=>$stage,
            'panel_verdict'=>$pverdict
        ]; 
        //return(dd($data));
        return view('pages.groups.create')->with('data',$data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
        DB::beginTransaction();
        $user_id = Auth::user()->getId();
        $user = User::find($user_id);
        if($user->accType!='1') {
            return redirect()->back()->withInput($request->all)->withErrors(['Cannot save information.','Only Capstone Coordinators can create groups.']);
        }
        $valid_group_types = ["Capstone","Thesis"];
        $valid_panel_members= DB::table('account')
        ->whereIn('account.accType',['1','2'])
        ->pluck('accID');
        
        $validator = Validator::make($request->all(), [
            'group_name' => ['required','max:100','unique:group,groupName','regex:/^[A-Za-z:,; -]+$/'],
            'group_type' => ['required',Rule::In($valid_group_types)],
            'content_adviser' => ['required',Rule::In($valid_panel_members->all())],
            'group_project_name' => ['required','max:150','unique:project,projName','regex:/^[0-9A-Za-z: -]+$/'],
          	'minimum_panel_members_for_project_approval' => ['required'],
        ]);
        if ($validator->fails()) {
			return redirect()->back()->withInput($request->all)->withErrors($validator);
        }
            
            $group = new Group;
            $project = new Project;
            $group->groupID = $grpID = Uuid::generate()->string;
            $group->groupName = $request->input('group_name');
            $group->groupStatus = 'Waiting for Submission';
            $group->groupType = $request->input('group_type');
            $group->groupCAdviserID = $request->input('content_adviser');
            $group->groupCoordID = $user_id;
            $group->save(); 
            $project->projID = Uuid::generate()->string;
            $project->projName = $request->input('group_project_name');
            $project->projGroupID = $grpID;
            $project->projStageNo = '1';
            $project->projPVerdictNo = '1';
          	$project->minProjPanel = $request->input('minimum_panel_members_for_project_approval');
          	if(!is_null($request->input('EditGroupPanelApp'))) {
              $project->requireChairProj = '1';
            } else {
              $project->requireChairProj = '0';
            }
            if(!is_null($request->input('document_link'))) {
                $validator = Validator::make($request->all(), [
                    'document_link' => ['max:150','active_url'],
                ]);
                if ($validator->fails()) {
                    return redirect()->back()->withInput($request->all)->withErrors($validator);
                }
                $project->projDocumentLink = $request->input('document_link');
            } else {
                $project->projDocumentLink = '';
            }
            
            $project->projCAdvCorrectionLink = '';
          	$project->save();
            $this->createAllPanelGroupDepedencies($valid_panel_members,$grpID);
            
            $sched = new Schedule;
            $sched->schedID = Uuid::generate()->string;
            $sched->schedDate = date('Y-m-d'); 
            $sched->schedTimeStart = Carbon::now();
            $sched->schedTimeEnd = Carbon::now();
            $sched->schedPlace = '';
            $sched->schedEventID = null;
            $sched->schedGroupID = $grpID;
            $sched->schedType = 'Oral Defense';
            $sched->schedStatus = 'Not Ready';
            $sched->save();

            if(is_null($request->input('EditGroupPanelApp'))) {
                $request['EditGroupPanelApp'] = 'off';
            }

            $grpHist = new GroupHistory;
            $grpHistActivity = "The project of the group was created.";
            $grpHist->add($group,$grpHistActivity);
            DB::commit();
        } catch(Exception $e){
            //return dd($e);
            DB::rollback();
            return redirect()->back()->withInput($request->all)->withErrors('Cannot save information.');
        }
        return redirect()->back()->with('success','Group Information was Created!');
        
    }

    public function createAllPanelGroupDepedencies($ids,$grpID) {
        //return dd($group);
        foreach($ids as $id) {
            //insert new panel_group to database
            $user = User::find($id);
            $panel = new PanelGroup;
            $x = (string) Uuid::generate();
            $panel->panelGroupID = $x;
            $panel->panelCGroupID = $grpID;
            $panel->panelAccID = $id;
            if($user->isChairPanelAll=='1') {
                $panel->panelIsChair = '1';
            } else {
                $panel->panelIsChair = '0';
            }
            $panel->panelGroupType = 'All';
           $panel->save(); 
           //return dd($panel->panelGroupID);
           //project_approval and schedule_approval dependencies
           
            DB::table('schedule_approval')->insert([
                [
                'schedAppID' => Uuid::generate()->string,
                'schedPanelGroupID' => $x,
                'isApproved' => '3',
                'schedAppMsg' => ''
                ]
            ]);
            DB::table('project_approval')->insert([
                [
                'projAppID' => Uuid::generate()->string,
                'projAppPanelGroupID' => $x,
                'isApproved' => '3',
                'revisionLink' => '',
                'projAppComment' => ''
                ]
            ]);
        }
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
        $group = DB::table('group')
        ->join('project','project.projGroupID','=','group.groupID')
        ->join('account','account.accID','=','groupCAdviserID')
        ->select('group.*','project.*','account.*')
        ->where('group.groupID','=',$id)
        ->first();

        $pgroup = DB::table('account')
        ->join('panel_group','panel_group.panelAccID','=','account.accID')
        ->select('account.*','panel_group.*')
        ->where('panel_group.panelCGroupID','=',$id)
        ->where('panel_group.panelGroupType','=','Custom')
        ->get();

        $panel_members = DB::table('account')
        ->where('account.isActivePanel','=','1')
        ->whereIn('account.accType',['1','2'])
        ->get();

        $capstone_coordinator = DB::table('account')
        ->whereIn('account.accType',['1'])
        ->get();

        $stage = DB::table('stage')->get();
        $pverdict = DB::table('panel_verdict')->get();
        $data = [
            'group'=>$group,
            'panel_members'=>$panel_members,
            'pgroup'=>$pgroup,
            'stage'=>$stage,
            'panel_verdict'=>$pverdict,
            'capstone_coordinator'=>$capstone_coordinator
        ];    
        //return(dd($data));
        return view('pages.groups.edit')->with('data',$data);
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
        if(!is_null($request->input('EditGroupPanel')) ) {
            if(is_null($request->input('panel_select'))) {
                //return dd($request->input('panel_select'));
                return redirect()->back()->withInput($request->all)->withErrors('No panel members selected');
            } else {
                $panel_sel = explode(',',$request->input('panel_select'));
            }
        } 
        if(is_null($request->input('EditGroupPanelApp'))) {
            $request['EditGroupPanelApp'] = 'off';
        }

        $valid_group_types = ["Capstone","Thesis"];
        $valid_panel_members= DB::table('account')
        ->whereIn('account.accType',['1','2'])
        ->pluck('accID');
        $valid_stages = DB::table('stage')->pluck('stageNo');
        $valid_panel_verdict = DB::table('panel_verdict')
        ->pluck('panelVerdictNo');
        $valid_group_status = [
            "Waiting for Submission", 
            "Submitted to Content Adviser", 
            "Corrected by Content Adviser", 
            "Waiting for Schedule Request", 
            "Waiting for Schedule Approval", 
            "Waiting for Final Schedule", 
            "Ready for Defense", 
            "Waiting for Project Approval", 
            "Corrected by Panel Members", 
            "Ready for Next Stage", 
            "Waiting for Project Completion",
            "Submitted to Capstone Coordinator",
            "Finished" 
        ];

        $validator = Validator::make($request->all(), [
            'group_name' => ['required','max:100','regex:/^[A-Za-z:,; -]+$/'],
            'group_type' => ['required',Rule::In($valid_group_types)],
            'content_adviser' => ['max:36','required',Rule::In($valid_panel_members->all())],
            'group_project_name' => ['required','max:150','regex:/^[0-9A-Za-z: -]+$/'],
            'stage_no' => ['Integer','required',Rule::In($valid_stages->all())],
            'panel_verdict' => ['Integer','required',Rule::In($valid_panel_verdict->all())],
            'group_status' => ['required',Rule::In($valid_group_status)],
            'minimum_panel_members_for_project_approval' => ['required','min:1','max:9','integer'],
        ]);
        if ($validator->fails()) {
			return redirect()->back()->withInput($request->all)->withErrors($validator);
        }

        $grpHist = new GroupHistory;
        $group = Group::find($id);
        $project = DB::table('project')->where('project.projGroupID','=',$group->groupID)->get();
        $project = Project::find($project[0]->projID);
        $group->groupType = $request->input('group_type');
        $group->groupCAdviserID = $request->input('content_adviser');
        $group->groupStatus = $request->input('group_status');
        $pRes = new Project;
        if($project->projStageNo != $request->input('stage_no')) {
            $pRes->resetProjApp($id,'3',0);
            $pRes->resetSchedApp($id,'3',0);
        }
        $project->projStageNo = $request->input('stage_no');
        if($request->input('group_status')=='Finished') {
            $project->projPVerdictNo = '7';
        } else {
            $project->projPVerdictNo = $request->input('panel_verdict');
        }
        
        if(!is_null($request->input('document_link'))) {
            $validator = Validator::make($request->all(), [
                'document_link' => ['max:150','active_url'],
            ]);
            if ($validator->fails()) {
                return redirect()->back()->withInput($request->all)->withErrors($validator);
            }
            $project->projDocumentLink = $request->input('document_link');
        } else {
            $project->projDocumentLink = '';
        }
        if(!is_null($request->input('content_adviser_correction_link'))) {
            $validator = Validator::make($request->all(), [
                'content_adviser_correction_link' => ['max:150','active_url'],
            ]);
            if ($validator->fails()) {
                return redirect()->back()->withInput($request->all)->withErrors($validator);
            }
            $project->projCAdvCorrectionLink = $request->input('content_adviser_correction_link');
        } else {
            $project->projCAdvCorrectionLink = '';
        }
        if(is_null($request->input('EditGroupPanelApp')) || $request->input('EditGroupPanelApp')=='off') {
            $project->requireChairProj = '0';
        } else {
            $project->requireChairProj = '1';
        } 
        if(!is_null($request->input('minimum_panel_members_for_project_approval'))) {
            $project->minProjPanel = $request->input('minimum_panel_members_for_project_approval');
        } else {
            $project->minProjPanel = '1';
        }
        if($group->groupName != $request->input('group_name')) {
            $validator = Validator::make($request->all(), [
                'group_name' => ['unique:group,groupName'],
            ]);
            if ($validator->fails()) {
                return redirect()->back()->withInput($request->all)->withErrors($validator);
            }
            $group->groupName = $request->input('group_name');
        }

        //If Panel Verdict is re-propose;
        if(in_array($request->input('panel_verdict'),['4'])) {
            $project->projStageNo = 1;
            $group->groupStatus = 'Waiting for Submission';
            $project->projPVerdictNo = '1';
            
            //update group history
            $grpHistActivity = "The group was advised to re-propose a new project.";
            $grpHist->add($group,$grpHistActivity);
        }
        /* This field cannot be edited
        if($project->projName != $request->input('group_project_name')) {
            $validator = Validator::make($request->all(), [
                'group_project_name' => ['unique:project,projName'],
            ]);
            if ($validator->fails()) {
                return redirect()->back()->withInput($request->all)->withErrors($validator);
            }
            $project->projName = $request->input('group_project_name');
        }*/

        try {
            DB::beginTransaction();
            if(!is_null($request->input('EditGroupPanel'))) {
                $validator = Validator::make($request->all(), [
                    'panel_group' => ['required'],
                ]);
                if ($validator->fails()) {
                    return redirect()->back()->withInput($request->all)->withErrors($validator);
                }

                $x = $this->modifyPanelDelete($id);
                $y = $this->modifyPanelAdd($id,$panel_sel);
            }
            $group->save();
            $project->save();
            if(($request->input('group_status')=='Waiting for Schedule Approval') && !in_array($request->input('panel_verdict'),['2','3','7'])) {
                $pRes->resetSchedApp($id,'0',1);
            } elseif(($request->input('group_status')=='Waiting for Project Approval') && in_array($request->input('panel_verdict'),['2','3'])) {
                $pRes->resetProjApp($id,'0',1);
            } else {
                $pRes->resetSchedApp($id,'3',0);
                $pRes->resetProjApp($id,'3',0);
            }
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            //return dd($e);
            return redirect()->back()->withInput($request->all)->withErrors('Group Information was not Updated!');
        }
        
        return redirect()->back()->withSuccess('Group Information was Updated!');
    }

    public function modifyPanelDelete($id) {

        $x = DB::table('schedule_approval')
        ->join('panel_group','panel_group.panelGroupID','=','schedule_approval.schedPanelGroupID')
        ->where('panel_group.panelCGroupID','=',$id)
        ->where('panel_group.panelGroupType','=','Custom')
        ->delete();

        $y = DB::table('project_approval')
        ->join('panel_group','panel_group.panelGroupID','=','project_approval.projAppPanelGroupID')
        ->where('panel_group.panelCGroupID','=',$id)
        ->where('panel_group.panelGroupType','=','Custom')
        ->delete();

        $z = DB::table('panel_group')
        ->where('panel_group.panelCGroupID','=',$id)
        ->where('panel_group.panelGroupType','=','Custom')
        ->delete();
    }

    public function modifyPanelAdd($id,$panel) {
        foreach($panel as $key => $value) {
            if($key == 0) {
                $x = DB::table('panel_group')->insert([
                    [
                    'panelGroupID' => Uuid::generate()->string,
                    'panelCGroupID' => $id, 
                    'panelAccID' => $value,
                    'panelIsChair' => '1',
                    'panelGroupType' => 'Custom'
                    ]
                ]);
            } else {
                $y = DB::table('panel_group')->insert([
                    [
                    'panelGroupID' => Uuid::generate()->string,
                    'panelCGroupID' => $id, 
                    'panelAccID' => $value,
                    'panelIsChair' => '0',
                    'panelGroupType' => 'Custom'
                    ]
                ]);
            }
        }

        $pgroup = DB::table('panel_group')
        ->where('panel_group.panelCGroupID','=',$id)
        ->where('panel_group.panelGroupType','=','Custom')
        ->pluck('panelGroupID');

        foreach($pgroup as $pg) {
            DB::table('schedule_approval')->insert([
                [
                'schedAppID' => Uuid::generate()->string,
                'schedPanelGroupID' => $pg,
                'isApproved' => '3',
                'schedAppMsg' => ''
                ]
            ]);
            DB::table('project_approval')->insert([
                [
                'projAppID' => Uuid::generate()->string,
                'projAppPanelGroupID' => $pg,
                'isApproved' => '3',
                'revisionLink' => '',
                'projAppComment' => ''
                ]
            ]);
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
        try {
            DB::beginTransaction();
            $this->deleteGroupExecute($id);
            DB::commit();
        } catch(Exception $e) {
            DB::rollback();
            return redirect()->action('GroupController@index', ['status' => 0,'statusMsg'=>['Deletion of the group has failed!']]);
        }
        return redirect()->action('GroupController@index', ['status' => 1,'statusMsg'=>['Deletion of the group was successful!']]);
    }

    private function deleteGroupExecute($id) {
        //delete the project approval associated with the group
        DB::table('project_approval')
        ->join('panel_group','panel_group.panelGroupID','=','project_approval.projAppPanelGroupID')
        ->where('panel_group.panelCGroupID','=',$id)
        ->delete();
        
        //delete the schedule approval associated with the group
        DB::table('schedule_approval')
        ->join('panel_group','panel_group.panelGroupID','=','schedule_approval.schedPanelGroupID')
        ->where('panel_group.panelCGroupID','=',$id)
        ->delete();

        //delete the panel group associated with the group
        DB::table('panel_group')
        ->where('panel_group.panelCGroupID','=',$id)
        ->delete();

        //delete the schedule associated with the group
        DB::table('schedule')
        ->join('group','group.groupID','=','schedule.schedGroupID')
        ->where('group.groupID','=',$id)
        ->delete();
      
      	//delete the project associated with the group
        DB::table('project')
        ->join('group','group.groupID','=','project.projGroupID')
        ->where('group.groupID','=',$id)
        ->delete();

        //delete the group itself
        DB::table('group')
        ->where('group.groupID','=',$id)
        ->delete();
    }

    public function deleteFinishedGroups() {
        try {
            DB::beginTransaction();
            $user_id = Auth::user()->getId();
            $user = User::find($user_id);
            $onlyAllowCCs = true;
            if($onlyAllowCCs==true) {
                if($user->accType!='1') {
                    return redirect()->back()->withErrors(['Only Capstone Coordinators can use this function']);
                }
            }

            //delete the project approval associated with the group
            DB::table('project_approval')
            ->join('panel_group','panel_group.panelGroupID','=','project_approval.projAppPanelGroupID')
            ->join('group','group.groupID','=','panel_group.panelCGroupID')
            ->where('group.groupStatus','=','Finished')
            ->where('group.groupCoordID','=',$user_id)
            ->delete();
            
            //delete the schedule approval associated with the group
            DB::table('schedule_approval')
            ->join('panel_group','panel_group.panelGroupID','=','schedule_approval.schedPanelGroupID')
            ->join('group','group.groupID','=','panel_group.panelCGroupID')
            ->where('group.groupStatus','=','Finished')
            ->where('group.groupCoordID','=',$user_id)
            ->delete();

            //find the settings
            $settings = DB::table('application_setting')
            ->where('application_setting.settingCoordID','=',$user_id)
            ->first();
            //delete the revision history associated with the group
            if(!is_null($settings) && $settings->settingAutoRHDelete=='1') {
                DB::table('revision_history')
                ->join('group','group.groupID','=','revision_history.revGroupID')
                ->where('group.groupStatus','=','Finished')
                ->where('group.groupCoordID','=',$user_id)
                ->delete();
            }

            //delete the group history associated with the group
            if(!is_null($settings) && $settings->settingAutoGHDelete=='1') {
                DB::table('group_history')
                ->join('group','group.groupID','=','group_history.groupHGroupID')
                ->where('group.groupStatus','=','Finished')
                ->where('group.groupCoordID','=',$user_id)
                ->delete();
            }

            //delete the panel group associated with the group
            DB::table('panel_group')
            ->join('group','group.groupID','=','panel_group.panelCGroupID')
            ->where('group.groupStatus','=','Finished')
            ->where('group.groupCoordID','=',$user_id)
            ->delete();

            //delete the schedule associated with the group
            DB::table('schedule')
            ->join('group','group.groupID','=','schedule.schedGroupID')
            ->where('group.groupStatus','=','Finished')
            ->where('group.groupCoordID','=',$user_id)
            ->delete();
            //delete all accounts associated with the group
            DB::table('account')
            ->join('group','group.groupID','=','account.accGroupID')
            ->where('group.groupStatus','=','Finished')
            ->where('group.groupCoordID','=',$user_id)
            ->delete();

            //delete the group itself
          	$totalGroups = DB::table('group')
            ->where('group.groupStatus','=','Finished')
            ->where('group.groupCoordID','=',$user_id)
            ->count();
            
            DB::commit();
          	if($totalGroups>0) {
                DB::table('group')
                ->where('group.groupStatus','=','Finished')
                ->where('group.groupCoordID','=',$user_id)
                ->delete();
                return redirect()->back()->withSuccess(['Deletion of group information succeeded.','All groups that are finished are already deleted.']);
            } else {
                return redirect()->back()->withErrors(['No groups to be deleted']);
            }
            
        } catch(Exception $e) {
            //return dd($e);
            DB::rollback();
            return redirect()->back()->withErrors('Deletion of group information failed.');
        }
        
    }
}
