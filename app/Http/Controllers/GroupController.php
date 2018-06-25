<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\models\Group;
use App\models\Project;
use DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Auth;

class GroupController extends Controller
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
        ->join('account','group.groupCAdviserNo','=','account.accNo')
        ->select('group.*','project.*','account.*')
        ->paginate(10); 
        return view('pages.groups.index')->with('data',$groups);
    }

    public function search()
    {
        $q = Input::get('q');
      
        if($q != '') {
            $data = DB::table('group')
            ->join('account','account.accNo','=','group.groupCAdviserNo')
            ->join('project','project.projGroupNo','=','group.groupNo')
            ->select('account.*','group.*','project.*')
            ->where('group.groupName','LIKE', "%".$q."%")
            ->orWhere(DB::raw('CONCAT(account.accFName," ",account.accMInitial," ",account.accLName," ",account.accTitle)'), 'LIKE', "%".$q."%")
            ->orWhere('group.groupStatus','LIKE', "%".$q."%")
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
        $proj = DB::table('group')
        ->join('project','project.projGroupNo','=','group.groupNo')
        ->select('group.*','project.*')
        ->get();

        $content_adviser = DB::table('account')
        ->where('account.accType','=','2')
        ->get();
        $data = ['proj'=>$proj,'content_adviser'=>$content_adviser];
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
        $group = DB::table('group')
        ->join('project','project.projGroupNo','=','group.groupNo')
        ->join('account','account.accNo','=','groupCAdviserNo')
        ->select('group.*','project.*','account.*')
        ->where('group.groupNo','=',$id)
        ->get();

        $pgroup = DB::table('account')
        ->join('panel_group','panel_group.panelAccNo','=','account.accNo')
        ->select('account.*','panel_group.*')
        ->where('panel_group.panelCGroupNo','=',$id)
        ->get();

        $panel_members = DB::table('account')
        ->where('account.accType','=','2')
        ->get();

        $stage = DB::table('stage')->get();
        $pverdict = DB::table('panel_verdict')->get();
        $data = [
            'group'=>$group,
            'panel_members'=>$panel_members,
            'pgroup'=>$pgroup,
            'stage'=>$stage,
            'panel_verdict'=>$pverdict
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
        return dd($request->all());
        $valid_group_types = ["Capstone","Thesis"];
        $valid_panel_members= DB::table('account')
        ->where('account.accType','=','2')
        ->pluck('accNo');
        $valid_stages = DB::table('stage')->pluck('stageNo');
        $valid_panel_verdict = DB::table('panel_verdict')
        ->pluck('panelVerdictNo');

        $validator = Validator::make($request->all(), [
            'group_name' => ['required','max:100'],
            'group_type' => ['required',Rule::In($valid_group_types)],
            'content_adviser' => ['required',Rule::In($valid_panel_members->all())],
            'group_project_name' => ['required','max:100'],
            'stage_no' => ['Integer','required',Rule::In($valid_stages->all())],
            'panel_verdict' => ['Integer','required',Rule::In($valid_panel_verdict->all())],
            'document_link' => ['max:255'],
        ]);
        if ($validator->fails()) {
			return redirect()->back()->withInput()->withErrors($validator);
        }

        $group = Group::find($id);
        $project = DB::table('project')->where('project.projGroupNo','=',$group->groupNo)->get();
        $project = Project::find($project[0]->projNo);
        $group->grpType = $request->input('group_type');
        $group->groupCAdviserNo = $request->input('content_adviser');
        $project->projStageNo = $request->input('stage_no');
        $project->projPVerdictNo = $request->input('panel_verdict');
        $project->projDocumentLink = $request->input('document_link');

        if($group->groupName != $request->input('group_name')) {
            $validator = Validator::make($request->all(), [
                'group_name' => ['unique:group,groupName'],
            ]);
            if ($validator->fails()) {
                return redirect()->back()->withInput()->withErrors($validator);
            }
            $group->groupName = $request->input('group_name');
        }
        if($project->projName != $request->input('group_project_name')) {
            $validator = Validator::make($request->all(), [
                'group_project_name' => ['unique:project,projName'],
            ]);
            if ($validator->fails()) {
                return redirect()->back()->withInput()->withErrors($validator);
            }
            $project->projName = $request->input('group_project_name');
        }

        $pgroup = DB::table('panel_group')
        ->select('panel_group.panelGroupNo')
        ->where('panel_group.panelCGroupNo','=',$id)
        ->get();

        if(!is_null($request->input('EditGroupPanel')) && !($pgroup === $request->input('panel_select'))) {
            $validator = Validator::make($request->all(), [
                'panel_group' => ['required'],
            ]);
            if ($validator->fails()) {
                return redirect()->back()->withInput()->withErrors($validator);
            }
    
                $x = $this->modifyPanelDelete($id);
                $y = $this->modifyPanelAdd($id,$request->input('panel_select'));
            
            if(!$x || !$y) {
                return redirect()->back()->withInput()->withErrors('Cannot save information.');
            }
        }
       
        if($group->save() && $project->save()) {
            $request->session()->flash('alert-success', 'Group Information was Updated!');
            return redirect()->back();
        } else {
            $request->session()->flash('alert-danger', 'Account Information was not Updated!');
            return redirect()->back()->withInput()->withErrors('Cannot save information.');
        }
    }

    public function modifyPanelDelete($id) {

        $x = DB::table('schedule_approval')
        ->join('panel_group','panel_group.panelGroupNo','=','schedule_approval.schedPGroupNo')
        ->where('panel_group.panelCGroupNo','=',$id)
        ->delete();

        $y = DB::table('project_approval')
        ->join('panel_group','panel_group.panelGroupNo','=','project_approval.projAppPGroupNo')
        ->where('panel_group.panelCGroupNo','=',$id)
        ->delete();

        $z = DB::table('panel_group')
        ->where('panel_group.panelCGroupNo','=',$id)
        ->delete();

        if($x && $y && $z) {
            return 1;
        } else {
            return 0;
        }
    }

    public function modifyPanelAdd($id,$panel) {
        foreach($panel as $key => $value) {
            if($key == 0) {
                DB::beginTransaction();
                try {
                $x = DB::table('panel_group')->insert([
                    [
                    'panelCGroupNo' => $id, 
                    'panelAccNo' => $value,
                    'panelIsChair' => '1'
                    ]
                ]);
                DB::commit();
                } catch (\Exception $e) {
                    DB::rollback();
                }
                if(!$x) {
                    return false;
                }
            } else {
                DB::beginTransaction();
                try {
                $y = DB::table('panel_group')->insert([
                    [
                    'panelCGroupNo' => $id, 
                    'panelAccNo' => $value,
                    'panelIsChair' => '0'
                    ]
                ]);
                DB::commit();
                } catch (\Exception $e) {
                    DB::rollback();
                }
                if(!$y) {
                    return 0;
                }
            }
        }

        $pgroup = DB::table('panel_group')
        ->where('panel_group.panelCGroupNo','=',$id)
        ->pluck('panelGroupNo');

        foreach($pgroup as $pg) {
            DB::beginTransaction();
            try {
            $x = DB::table('schedule_approval')->insert([
                [
                'schedPGroupNo' => "'{$pg}'",
                'isApproved' => '0'
                ]
            ]);
            $y = DB::table('project_approval')->insert([
                [
                'projAppPGroupNo' => "'{$pg}'",
                'isApproved' => '0',
                'revisionLink' => ''
                ]
            ]);
            DB::commit();
            } catch (\Exception $e) {
                DB::rollback();
            }
            if(!$x || !$y) {
                return 0;
            }
        }
        return 1;
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
