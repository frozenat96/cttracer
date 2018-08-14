<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\models\Group;
use App\models\Project;
use App\models\Stage;
use App\models\AccessControl;
use DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Auth;
use Session;

class ProjectController extends Controller
{
    public function __construct()
    {
        $ac = new AccessControl;
        $accesscontrol = $ac->status; 
        if($accesscontrol == true) {
            $this->middleware('auth');
            $this->middleware('roles', ['except' => ['disableAllApprovals'], 'roles'=> ['Capstone Coordinator','Panel Member']]);
            $this->middleware('roles', ['only' => ['disableAllApprovals'], 'roles'=> ['Capstone Coordinator']]);
        }
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = DB::table('group')
        ->join('project','project.projGroupID','=','group.groupID')
        ->join('panel_verdict','panel_verdict.panelVerdictNo','=','project.projPVerdictNo')
        ->select('project.*','group.*','panel_verdict.*')
        ->whereNotIn('group.groupStatus',['Finished'])
        ->whereNotIn('project.projPVerdictNo',['7'])
        ->paginate(10);
        return view('pages.projects.index')->withData($data);
    }

    public function search(Request $request)
    {
        $q = Input::get('q');
        if($q != '') {
            $data = DB::table('group')
            ->join('project','project.projGroupID','=','group.groupID')
            ->join('panel_verdict','panel_verdict.panelVerdictNo','=','project.projPVerdictNo')
            ->select('project.*','group.*','panel_verdict.*')
            ->whereNotIn('group.groupStatus',['Finished'])
            ->whereNotIn('project.projPVerdictNo',['7'])
            ->where(function ($query) use ($q){
                $query->where('project.projName','LIKE', "%".$q."%") 
                ->orWhere('group.groupName','LIKE', "%".$q."%");
            })
            ->paginate(10)
            ->setpath('');
        } else {
            return redirect()->action('ProjectController@index');
        }

        $data->appends(array(
            'q' => Input::get('q')
        ));
        
        return view('pages.projects.index')->with('data',$data);
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
        $user_id = Auth::user()->getId(); 
        $Projectmodel = new Project();
        $proj = $Projectmodel->projectInfoByGroup($id);
        if(is_null($proj)) {
            return view('pages.projects.view');
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
       
        $data = ['proj' => $proj, 'group' => $group,'adviser'=>$adviser,'projApp'=>$pgroup,'schedApp'=>$schedApp];
        return view('pages.projects.view')->with('data', $data);
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

    public function disableAllApprovals() {
        try {
        DB::beginTransaction();
        $update = DB::table('project_approval')
        ->update([
            'project_approval.isApproved' => '3'
        ]);
        $update = DB::table('schedule_approval')
        ->update([
            'schedule_approval.isApproved' => '3'
        ]);
        DB::commit();
        } catch(Exception $e) {
        DB::rollback();
        }
        Session::flash('success','All approvals has been disabled!');
        return view('pages.index')->with('success','All approvals has been disabled!');
    }
}
