<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Auth;
use App\models\Stage;
use App\models\Project;
use App\models\Group;
use App\models\AccountGroup;
use App\models\Schedule;
use App\models\ScheduleApproval;
use App\models\PanelGroup;
use App\models\Notification;
use App\models\AccessControl;
use Illuminate\Support\Facades\Validator;
use DB;
use Illuminate\Support\Facades\Input;
use Exception;

class AdvisedGroupsController extends Controller
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
        $groups = $this->getIndex();
        $q = Input::get('status');
        $msg = Input::get('statusMsg');

        if(!is_null($q) && $q==1) {
            return view('pages.advised_groups.index')->with('data',$groups)->with('success2',$msg);
        } elseif(!is_null($q) && $q==0) {
            return view('pages.advised_groups.index')->with('data',$groups)->withErrors($msg);
        }
        return view('pages.advised_groups.index')->with('data',$groups);
    }

    private function getIndex() {
        $substatus = ['Submitted to Content Adviser'];
        $user_id = Auth::user()->getId(); 
        $groups = DB::table('group')
        ->join('project','project.projGroupID','=','group.groupID')
        ->join('panel_verdict','panel_verdict.panelVerdictNo','=','project.projPVerdictNo')
        ->join('schedule','schedule.schedGroupID','=','group.groupID')
        ->join('account','account.accID','=','group.groupCAdviserID')
        ->whereIn('account.accType',['1','2'])
        ->where('group.groupCAdviserID','=',$user_id)
        ->paginate(5); 
        return $groups;
    }

    public function search() {
        $q = Input::get('q');
        $user_id = Auth::user()->getId(); 
        $validGroupStatus = ['Waiting for Submission','Corrected by Content Adviser','Corrected by Panel Members'];
        $validGroupStatus2 = ['Waiting for Submission','Corrected by Content Adviser','Corrected by Panel Members','Submitted to Content Adviser'];
        if($q=='') {
            return redirect()->action('AdvisedGroupsController@index');
        } elseif($q != '') {
            $data = DB::table('group')
            ->join('project','project.projGroupID','=','group.groupID')
            ->join('panel_verdict','panel_verdict.panelVerdictNo','=','project.projPVerdictNo')
            ->join('schedule','schedule.schedGroupID','=','group.groupID')
            ->join('account','account.accID','=','group.groupCAdviserID')
            ->whereIn('account.accType',['1','2'])
            ->where('group.groupCAdviserID','=',$user_id)
            ->when(($q=='Unsubmitted'), function ($query) use ($validGroupStatus,$validGroupStatus2) {
                return $query->whereIn('group.groupStatus',$validGroupStatus);
            }, function ($query) use ($validGroupStatus2,$q) {
                $query->when((in_array($q,$validGroupStatus2)), function ($query) use ($q) {
                    return $query->where('group.groupStatus','LIKE', "%".$q."%");
                }, function ($query) use ($q) {
                    $query->where('group.groupName','LIKE', "%".$q."%")
                    ->orWhere('project.projName','LIKE', "%".$q."%");
                });
            }) 
            ->paginate(5);
        }

        $data->appends(array(
            'q' => Input::get('q')
        ));
           
        return view('pages.advised_groups.index')->with('data',$data);
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
    public function edit(Request $request, $id)
    {
        $group = DB::table('group')
        ->join('project','project.projGroupID','=','group.groupID')
        ->where('group.groupID','=',$id)
        ->first();
        return view('pages.advised_groups.corrections')->with('data',$group);
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

    public function contentAdvApproval(Request $request){
        try{
            DB::beginTransaction();
        if (is_null($request->input('submit'))){
            return redirect()->back()->withErrors('Approval failed.');
        } 
        $pRes = new Project;
        $stage = new Stage;
        $group = Group::find($request->input('groupID'));
        $project = DB::table('project')
        ->join('group','group.groupID','=','project.projGroupID')
        ->where('group.groupID','=',$group->groupID)
        ->first();
        if(in_array($project->projPVerdictNo,['2','3']) && $group->groupStatus=='Submitted to Content Adviser') {
            $group->groupStatus = 'Waiting for Project Approval';
            $pRes->resetProjApp($group->groupID,'0',1);
            $group->save();
            $notify = new Notification;
            $notify->NotifyPanelOnProjectApproval($group);
        } elseif($group->groupStatus=='Submitted to Content Adviser') {
            $group->groupStatus = 'Waiting for Schedule Request';     
            $group->save();  
            $notify = new Notification;
            $notify->NotifyCoordOnSchedRequest($group);
        }
            $proj2 = Project::find($project->projID);
            $proj2->projCAdvCorrectionLink = ''; //Set content adviser's correction link to empty string when the group's document is approved
            $proj2->save();
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->action('AdvisedGroupsController@index', ['status' => 0,'statusMsg'=>['The document of group : ' . $group->groupName . ' was not approved.']]);
        }
        $msg = 'The document of group : ' . $group->groupName . ' was approved.';
        $g = $this->getIndex();
        return redirect()->action('AdvisedGroupsController@index', ['status' => 1,'statusMsg'=>[$msg]]);
    } 

    public function contentAdvCorrections(Request $request){
        try{
        DB::beginTransaction(); 
     
        $group = Group::find($request->input('groupID'));
        $project = DB::table('project')->where('project.projGroupID','=',$group->groupID)
        ->first();
        $project = Project::find($project->projID);
        $ValidStatus = ['Waiting for Submission','Corrected by Panel Members','Corrected by Content Adviser','Submitted to Content Adviser'];
        if(!in_array($group->groupStatus,$ValidStatus)){
            DB::rollback();
            return redirect()->back()->withErrors( 'The document of group : ' . $group->groupName . ' was not corrected.');
        }     
            $group->groupStatus = 'Corrected by Content Adviser'; 
            $group->save();
            //when given corrections the submitted document link will be stored in the adviser's corrected document link
            $project->projCAdvCorrectionLink = $project->projDocumentLink;
            $project->save();
            $notify = new Notification;
            $notify->NotifyStudentOnAdvCorrected($group);

            DB::commit();
            return redirect()->action('AdvisedGroupsController@index', ['status' => 1,'statusMsg'=>['The document of group : ' . $group->groupName . ' was corrected.']]);
            
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->action('AdvisedGroupsController@index', ['status' => 0,'statusMsg'=>['The document of group : ' . $group->groupName . ' was not corrected.']]);
        }
    }
}
