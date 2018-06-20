<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\models\Project;
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
            return view('pages.my_project.index');
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
        $adviser = DB::table('account')
        ->where('account.accNo','=',$proj[0]->groupCAdviserNo)
        ->get();
        $data = ['proj' => $proj, 'group' => $group,'adviser'=>$adviser,'pgroup'=>$pgroup];
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
        $proj = Project::find($id); 
        return view('pages.my_project.edit')->with('data', $proj);
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
            'project_name' => ['required','max:150','unique:project,projName'],
        ]);
            /*'project_name' => ['required','max:150','unique:project,projName',
            Rule::notIn(['sprinkles', 'cherries'])],*/
        

        $proj = Project::find($id);
        $proj->projName = $request->input('project_name');
        $proj->save();
        $request->session()->flash('alert-success', 'Project updated!');
        //return view('/my-project/{id}/edit',['id'=>$id])->with('success','updated');
        return redirect()->action(
            'MyProjController@edit', ['id' => $id]
        );
    
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
