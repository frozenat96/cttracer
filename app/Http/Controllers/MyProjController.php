<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\models\Project;
use App\models\Group;
use Auth;
use Illuminate\Validation\Rule;

class MyProjController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user_id = Auth::id(); 
        $Projectmodel = new Project();
        $proj = $Projectmodel->projectInfoByAccount($user_id);
        if(!count($proj)) {
            return view('pages.projects.view');
        }
    
        $group = DB::table('account')
            ->join('group', 'account.accGroupNo', '=', 'group.groupNo')
            ->select('account.*')
            ->where('group.groupNo','=',$proj[0]->groupNo)
            ->get();
        
        $pgroup = DB::table('panel_group')
        ->join('account', 'account.accNo', '=', 'panel_group.panelAccNo')
        ->join('group', 'panel_group.panelCGroupNo', '=', 'group.groupNo')
        ->join('project_approval', 'project_approval.projAppPGroupNo', '=', 'panel_group.panelGroupNo')
        ->select('account.*','project_approval.*','panel_group.*')
        ->where('panel_group.panelCGroupNo','=',$proj[0]->groupNo)
        ->get();
        $schedApp = DB::table('panel_group')
        ->join('account', 'account.accNo', '=', 'panel_group.panelAccNo')
        ->join('group', 'panel_group.panelCGroupNo', '=', 'group.groupNo')
        ->join('schedule_approval', 'schedule_approval.schedPGroupNo', '=', 'panel_group.panelGroupNo')
        ->join('schedule','schedule.schedGroupNo','=','group.groupNo')
        ->select('account.*','schedule_approval.*','panel_group.*','schedule.*')
        ->where('panel_group.panelCGroupNo','=',$proj[0]->groupNo)
        ->get();
        $adviser = DB::table('account')
        ->where('account.accNo','=',$proj[0]->groupCAdviserNo)
        ->get();
        $data = ['proj' => $proj, 'group' => $group,'adviser'=>$adviser,'projApp'=>$pgroup,'schedApp'=>$schedApp];
        return view('pages.my_project.index')->with('data', $data);
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
        ->join('group','group.groupNo','=','project.projGroupNo')
        ->select('project.*','group.*')
        ->where('group.groupNo','=',$id)
        ->first();
        return view('pages.my_project.edit')->with('data', $group);
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
            'document_link' => ['required','max:255'],
        ]);
            /*'project_name' => ['required','max:150','unique:project,projName',
            Rule::notIn(['sprinkles', 'cherries'])],*/
        
        try {
            DB::beginTransaction();
            $proj = Project::find($id);
            $group = Group::find($proj->projGroupNo);
            $proj->projDocumentLink = $request->input('document_link');
            $proj->save();
            $group->groupStatus = 'Submitted to Content Adviser';
            $group->save();
            $request->session()->flash('alert-success', 'The document was submitted to your Content Adviser!');
            DB::commit();
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->withInput()->with('error', 'The document was not submitted to your Content Adviser!');
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
