<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\models\Project;
use App\models\Group;
use App\models\Stage;
use App\models\Notification;
use App\models\AccessControl;
use App\models\ApplicationSetting;
use App\User;
use Auth;
use Illuminate\Validation\Rule;
use App\Events\eventTrigger;
use Mail;
use Exception;
use Session;
use Illuminate\Support\Facades\Validator;


class MyProjController extends Controller
{
    public function __construct()
    {
        $ac = new AccessControl;
        $accesscontrol = $ac->status; 
        if($accesscontrol == true) {
            $this->middleware('auth');
            $this->middleware('roles', ['roles'=> ['Student']]);
            //$this->middleware('permission:edit-posts',   ['only' => ['edit']]);
        }
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($success = null)
    {  
        $data = $this->getIndexData(); 
        if($data==false) {
            return view('pages.index')->withErrors('Project not found.');
        }
        return view('pages.my_project.index')->with('data',$data);
    } 

    private function getIndexData() {
        $user_id = Auth::user()->getId(); 
        $Projectmodel = new Project();
        $proj = $Projectmodel->projectInfoByAccount($user_id);
        if(is_null($proj)) {
            return false;
        }
    
        $group = DB::table('account')
            ->join('group', 'account.accgroupID', '=', 'group.groupID')
            ->select('account.*')
            ->where('group.groupID','=',$proj->groupID)
            ->get();

        $stage = new Stage;
        $pgroup = DB::table('panel_group')
        ->join('account', 'account.accID', '=', 'panel_group.panelAccID')
        ->join('group', 'panel_group.panelCGroupID', '=', 'group.groupID')
        ->join('project_approval', 'project_approval.projAppPanelGroupID', '=', 'panel_group.panelGroupID')
        ->select('account.*','project_approval.*','panel_group.*')
        ->where('account.isActivePanel','=','1')
        ->where('panel_group.panelCGroupID','=',$proj->groupID)
        ->where('panel_group.panelGroupType','=',$stage->current($proj->groupID))
        ->get();

        $schedApp = DB::table('panel_group')
        ->join('account', 'account.accID', '=', 'panel_group.panelAccID')
        ->join('group', 'panel_group.panelCGroupID', '=', 'group.groupID')
        ->join('schedule_approval', 'schedule_approval.schedPanelGroupID', '=', 'panel_group.panelGroupID')
        ->join('schedule','schedule.schedGroupID','=','group.groupID')
        ->select('account.*','schedule_approval.*','panel_group.*','schedule.*')
        ->where('account.isActivePanel','=','1')
        ->where('panel_group.panelCGroupID','=',$proj->groupID)
        ->where('panel_group.panelGroupType','=',$stage->current($proj->groupID))
        ->get();
        $adviser = DB::table('account')
        ->where('account.accID','=',$proj->groupCAdviserID)
        ->first();
        return $data = ['proj' => $proj, 'group' => $group,'adviser'=>$adviser,'projApp'=>$pgroup,'schedApp'=>$schedApp];
    }

    public function submitProjectArchive($id) {
        $data = Group::find($id);
        $settings = ApplicationSetting::first();
        return view('pages.my_project.project-archive')
        ->with('data',$data)
        ->with('settings',$settings);
    }

    public function submitProjectArchiveStore($id,Request $request) {
        try {
            DB::beginTransaction();
            $validator = Validator::make($request->all(), [
                'document_link' => ['required','max:255','active_url'],
            ]);
            if ($validator->fails()) {
                return redirect()->back()->withInput($request->all)->withErrors($validator);
            }
            $group = Group::find($id);
            $project = DB::table('project')
            ->where('project.projGroupID','=',$group->groupID)
            ->first();
            $project = Project::find($project->projID);
            $project->projDocumentLink = $request->input('document_link');
            $project->save();
            $notify = new Notification;
            $notify->NotifyCoordOnProjectArchive($group);
            DB::commit();
            Session::flash('success', 'The document was submitted to your Capstone Coordinator!' ); 
        } catch(Exception $e) {
            DB::rollback();
            //return dd($e);
            return redirect()->back()->withInput($request->all)->withErrors('The document was not submitted!');
        }
        $data = $this->getIndexData();
        return view('pages.my_project.index')->with('data',$data);      
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
        $group = DB::table('project')
        ->join('group','group.groupID','=','project.projGroupID')
        ->select('project.*','group.*')
        ->where('group.groupID','=',$id)
        ->first();
        $settings = DB::table('application_setting')->first();
        if(!in_array($group->groupStatus,['Waiting for Submission','Corrected by Panel Members','Corrected by Content Adviser'])) {
            return redirect()->action('MyProjController@index');
        }

        $data = ['group'=>$group,'settings'=>$settings];
        return view('pages.my_project.edit')->with('data', $data);
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
        $this->validate($request, [
            'document_link' => ['required','max:255','active_url'],
        ]);
            /*'project_name' => ['required','max:150','unique:project,projName',
            Rule::notIn(['sprinkles', 'cherries'])],*/
        
        try {
            DB::beginTransaction();
            $proj = Project::find($id); 
            $group = Group::find($proj->projGroupID);
            $proj->projDocumentLink = $request->input('document_link');
            $proj->save();
            $group->groupStatus = 'Submitted to Content Adviser';
            $notify = new Notification;
            $notify->NotifyAdviserOnSubmission($group);
               
            $group->save();
            DB::commit();
            
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->withInput($request->all)->withErrors( 'The document was not submitted to your Content Adviser!');
        }
        Session::flash('success', 'The document was submitted to your Content Adviser!' );  
        $data = $this->getIndexData();
        return view('pages.my_project.index')->with('data',$data);
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
