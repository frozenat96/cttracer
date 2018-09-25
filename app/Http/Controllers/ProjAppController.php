<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\models\ProjectApproval;
use App\models\Project;
use App\models\Group;
use App\models\Stage;
use App\models\Notification;
use App\models\AccessControl;
use App\models\RevisionHistory;
use Auth;
use DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Webpatser\Uuid\Uuid;
use Exception;
use Carbon\Carbon;

class ProjAppController extends Controller
{
    public function __construct()
    {
        $ac = new AccessControl;
        $accesscontrol = $ac->status; 
        if($accesscontrol == true) {
            $this->middleware('auth');
            $this->middleware('roles', ['roles'=> ['Panel Member']]);
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
        $ValidGroupStatus = ['Waiting for Project Approval'];

        $data = DB::table('group')
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
        ->orderBy('group.groupName')
        ->paginate(5); 
        //return dd($data);
        //return $this->calcSchedStatus($sched[0]->panelCGroupID);
        $q = Input::get('status');
        $msg = Input::get('statusMsg');

        if(!is_null($q) && $q==1) {
            return view('pages.approve_projects.index')->with('data',$data)->with('success2',$msg);
        } elseif(!is_null($q) && $q==0) {
            return view('pages.approve_projects.index')->with('data',$data)->withErrors($msg);
        }
        return view('pages.approve_projects.index')->with('data',$data);
    }

    
    public function search(Request $request)
    {
        $user_id = Auth::user()->getId();
        $q = Input::get('q');
      
        $ValidGroupStatus = ['Waiting for Project Approval'];
        if($q != '') {
            $data = DB::table('group')
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
            ->where(function ($query) use ($q){
                $query->where('group.groupName','LIKE', "%".$q."%")
                ->orWhere('project.projName','LIKE', "%".$q."%");
            })
            ->orderBy('group.groupName')
            ->paginate(5); 
 
        } else {
            return redirect()->action('ProjAppController@index');
        }

        $data->appends(array(
            'q' => Input::get('q')
        ));
            
        return view('pages.approve_projects.index')->with('data',$data);
    }

    public function projApprovalStatus(Request $request) {
        if (is_null($request->input('opt'))){
            return redirect()->back()->withInput($request->all)->withErrors( 'Project approval failed.');
        }
        $stage = new Stage;
        $q = DB::table('project_approval')
        ->join('panel_group','panel_group.panelGroupID','=','project_approval.projAppPanelGroupID')
        ->join('group','group.groupID','=','panel_group.panelCGroupID')
        ->join('account','account.accID','=','panel_group.panelAccID')
        ->select('project_approval.*','account.*','panel_group.*','group.*')
        ->where('account.accID','=',$request->input('acc'))
        ->where('panel_group.panelCGroupID','=',$request->input('grp'))
        ->where('panel_group.panelGroupType','=',$stage->current($request->input('grp')))
        ->first();
        $project = DB::table('project')
        ->where('project.projGroupID','=',$request->input('grp'))
        ->first();
        $project = Project::find($project->projID);

        $approval = ProjectApproval::find($q->projAppID);
        if($request->input('opt')=='1') {
            $approval->isApproved = 1;
            $approval->projAppComment = '';
            $approval->revisionLink = $project->projDocumentLink;
            $approval->projAppTimestamp = Carbon::now();
            $msg = 'The project document of group : ' . $q->groupName . ' was approved.';
        } else { 
            //When a panel member has given a correction, the current document link of the group will be stored to the revision link of the current project approval
            $approval->revisionLink = $project->projDocumentLink;
            $approval->isApproved = 2;
            $approval->projAppTimestamp = Carbon::now();
            $msg = 'The project document of group : ' . $q->groupName . ' was given corrections.';
            $approval->projAppComment = !is_null($request->input('comments')) ? $request->input('comments') : '';
        }

        //revision history
        $q2 = DB::table('panel_group')
        ->join('group','group.groupID','=','panel_group.panelCGroupID')
        ->join('project','project.projGroupID','=','group.groupID')
        ->join('account','account.accID','=','panel_group.panelAccID')
        ->where('account.accID','=',$request->input('acc'))
        ->where('panel_group.panelCGroupID','=',$request->input('grp'))
        ->where('panel_group.panelGroupType','=',$stage->current($request->input('grp')))
        ->first();
        $res1 = 1;
        $revNoTest = DB::table('revision_history')
        ->join('account','account.accID','=','revision_history.revPanelAccID')
        ->where('revision_history.revPanelAccID','=',$request->input('acc'))
        ->where('revision_history.revGroupID','=',$request->input('grp'))
        ->where('revision_history.revStageNo','=',$q2->projStageNo)
        ->max('revision_history.revNo');
        //check the maximum value of revision number for a particular stage
        if(is_null($revNoTest)) { //if null, set to 1
            $res1 = 1;
        } else { //if not null, add the current maximum value by 1
            $res1 = $revNoTest + 1;
        }

        $revHistory = new RevisionHistory;
        if($revHistory->status==true) {
        $revHistory->revID = $rvID = Uuid::generate()->string;
        $revHistory->revStageNo = $q2->projStageNo;
        $revHistory->revNo = $res1;
        $revHistory->revPanelAccID = $request->input('acc');
        $revHistory->revPanelIsChair = $q2->panelIsChair;
        $revHistory->revGroupID = $request->input('grp');
        $revHistory->revGroupName = $q2->groupName;
        $revHistory->revProjName = $q2->projName;
        $revHistory->revComment = $approval->projAppComment;
        $revHistory->revLink = $approval->revisionLink;
        $revHistory->revStatus = $approval->isApproved;
        $revHistory->revTimestamp = Carbon::now();
        }

        try{
            DB::beginTransaction();
            $approval->save();
            if($revHistory->status==true) {
                $revHistory->save();
            }
            $this->calcProjStatus($request->input('grp'),$stage);
            DB::commit();
        } catch (Exception $e) {
            //return dd($e);
            $request->session()->flash('alert-danger', 'Project approval failed.');
            DB::rollback();
            return view('pages.approve_projects.index');
        }
        
        $request->session()->flash('alert-success', $msg);
        if(!Auth::user()) {
            return view('auth.login');
        }
        return view('pages.approve_projects.index');
    }

    private function calcProjStatus($groupID,Stage $stage){
        $pMembersTotal = DB::table('project_approval')
        ->join('panel_group','panel_group.panelGroupID','=','project_approval.projAppPanelGroupID')
        ->join('account','account.accID','=','panel_group.panelAccID')
        ->select('project_approval.isApproved')
        ->where('account.isActivePanel','=','1')
        ->where('panel_group.panelCGroupID','=',$groupID)
        ->where('panel_group.panelGroupType','=',$stage->current($groupID))
        ->count();

        $pMembersWaiting = DB::table('project_approval')
        ->join('panel_group','panel_group.panelGroupID','=','project_approval.projAppPanelGroupID')
        ->join('account','account.accID','=','panel_group.panelAccID')
        ->select('project_approval.isApproved')
        ->where('account.isActivePanel','=','1')
        ->where('panel_group.panelCGroupID','=',$groupID)
        ->where('panel_group.panelGroupType','=',$stage->current($groupID))
        ->where('project_approval.isApproved','=','0')
        ->count();

        if($stage->current($groupID)=='Custom') {
        $chairPanelApp = DB::table('project_approval')
        ->join('panel_group','panel_group.panelGroupID','=','project_approval.projAppPanelGroupID')
        ->join('account','account.accID','=','panel_group.panelAccID')
        ->select('project_approval.isApproved')
        ->where('account.isActivePanel','=','1')
        ->where('panel_group.panelCGroupID','=',$groupID)
        ->where('panel_group.panelGroupType','=',$stage->current($groupID))
        ->where('panel_group.panelIsChair','=','1')
        ->where('project_approval.isApproved','=','1')
        ->count();
        $chairDisapproved = DB::table('project_approval')
        ->join('panel_group','panel_group.panelGroupID','=','project_approval.projAppPanelGroupID')
        ->join('account','account.accID','=','panel_group.panelAccID')
        ->select('project_approval.isApproved')
        ->where('account.isActivePanel','=','1')
        ->where('panel_group.panelCGroupID','=',$groupID)
        ->where('panel_group.panelGroupType','=',$stage->current($groupID))
        ->where('panel_group.panelIsChair','=','1')
        ->where('project_approval.isApproved','=','2')
        ->count();
        } elseif($stage->current($groupID)=='All') {
        $chairPanelApp = DB::table('project_approval')
        ->join('panel_group','panel_group.panelGroupID','=','project_approval.projAppPanelGroupID')
        ->join('account','account.accID','=','panel_group.panelAccID')
        ->select('project_approval.isApproved')
        ->where('account.isActivePanel','=','1')
        ->where('panel_group.panelCGroupID','=',$groupID)
        ->where('panel_group.panelGroupType','=',$stage->current($groupID))
        ->where('account.isChairPanelAll','=','1')
        ->where('project_approval.isApproved','=','1')
        ->count();

        $chairDisapproved = DB::table('project_approval')
        ->join('panel_group','panel_group.panelGroupID','=','project_approval.projAppPanelGroupID')
        ->join('account','account.accID','=','panel_group.panelAccID')
        ->select('project_approval.isApproved')
        ->where('account.isActivePanel','=','1')
        ->where('panel_group.panelCGroupID','=',$groupID)
        ->where('panel_group.panelGroupType','=',$stage->current($groupID))
        ->where('account.isChairPanelAll','=','1')
        ->where('project_approval.isApproved','=','2')
        ->count();
        }

        $panelMembersApp = DB::table('project_approval')
        ->join('panel_group','panel_group.panelGroupID','=','project_approval.projAppPanelGroupID')
        ->join('account','account.accID','=','panel_group.panelAccID')
        ->select('project_approval.isApproved')
        ->where('account.isActivePanel','=','1')
        ->where('panel_group.panelCGroupID','=',$groupID)
        ->where('panel_group.panelGroupType','=',$stage->current($groupID))
        ->where('panel_group.panelIsChair','=','0')
        ->where('project_approval.isApproved','=','1')
        ->count();

        $panelDisapproved = DB::table('project_approval')
        ->join('panel_group','panel_group.panelGroupID','=','project_approval.projAppPanelGroupID')
        ->join('account','account.accID','=','panel_group.panelAccID')
        ->select('project_approval.isApproved')
        ->where('account.isActivePanel','=','1')
        ->where('panel_group.panelCGroupID','=',$groupID)
        ->where('panel_group.panelGroupType','=',$stage->current($groupID))
        ->where('project_approval.isApproved','=','2')
        ->count();

        $proj = DB::table('project')
        ->join('group','group.groupID','=','project.projGroupID')
        ->select('project.*')
        ->where('project.projGroupID','=',$groupID)
        ->first();
        $group = Group::find($groupID);
        $stg = DB::table('stage')->where('stage.stagePanel','=',$stage->current($groupID))
        ->first();
        $apprvl = null;
        if(!$pMembersWaiting) {
            if($proj->requireChairProj && $chairDisapproved) {
                $apprvl = false;//chair is required and chair has disapproved, result: disapprove
            } else if(($panelMembersApp + $chairPanelApp) >= $proj->minProjPanel) {
                if($proj->requireChairProj && $chairPanelApp) {  
            //chair required, the total chair and panel members who has approved is 
            //greater or equal to minimum, result: approve 
                $apprvl = true;              
                } elseif(!$proj->requireChairProj) { //chair not required, minimum panel is met, result: approve 
                    $apprvl = true;
                }
            } elseif($panelDisapproved > ($pMembersTotal - $proj->minProjPanel)) {
            //total disapproval is greater than the total panel required 
            //less the minimum panel that is required
            //if there are no panel members that had not made a decision, result: disapprove
                $apprvl = false;   
            } 
        }
        if(!is_null($apprvl)) {
            if($apprvl==true) {
                $this->approve_proj($group);
            } else {
                $this->disapprove_proj($group,$stage->current($groupID));
            }
        }
    }

    private function disapprove_proj(Group $group,$type) {
        $group->groupStatus = 'Corrected by Panel Members';
        //$this->resetProjApp($group->groupID,$type);
        $project = DB::table('project')
        ->where('project.projGroupID','=',$group->groupID)
        ->first();
        $project = Project::find($project->projID);
        //When all panel members are done evaluating a project approval and atleast one (1) panel members gave corrections, the group's current submitted document link will be stored to the panel member's correction link
        $project->projPCorrectionLink = $project->projDocumentLink;
        $project->save();
        $notify = new Notification;
        $notify->NotifyStudentOnPanelCorrected($group);
        $group->save();
    }

    private function approve_proj(Group $group) {
        $group->groupStatus = 'Ready for Next Stage';
        $project = DB::table('project')
        ->where('project.projGroupID','=',$group->groupID)
        ->first();
        $project = Project::find($project->projID);
        //When all panel members are done evaluating a project approval and no panel members gave corrections, the panel member's correction link will be set to the current document link
        $project->projPCorrectionLink = $project->projDocumentLink;
        $project->save();
        $notify = new Notification;
        $notify->NotifyCoordOnNextStage($group);
        $group->save();
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
        $user_id = Auth::user()->getId();
        $stage = new Stage;
        $data = DB::table('group')
        ->join('panel_group','panel_group.panelCGroupID','=','group.groupID')
        ->join('project_approval','project_approval.projAppPanelGroupID','panel_group.panelGroupID')
        ->join('project','project.projGroupID','=','group.groupID')
        ->where('group.groupID','=',$id)
        ->where('panel_group.panelAccID','=',$user_id)
        ->where('panel_group.panelGroupType','=',$stage->current($id))
        ->first();
        return view('pages.approve_projects.edit')->with('data',$data);
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
