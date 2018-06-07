<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\models\Project;
use Auth;

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

        $proj = DB::table('account')
            ->join('account_group', 'account.accNo', '=', 'account_group.accNo')
            ->join('group', 'account_group.grpNo', '=', 'group.groupNo')
            ->join('project', 'group.groupProjNo', '=', 'project.projNo')
            ->select('project.*','account.*','group.*','account_group.*')
            ->where('account.accNo','=',$user_id)
            ->get();
        //return dd($users);
        $group = DB::table('account')
            ->join('account_group', 'account.accNo', '=', 'account_group.accNo')
            ->get();
        $data = ['proj' => $proj, 'group' => $group];
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
            'project_name' => 'required',
        ]);

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
