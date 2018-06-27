<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Auth;
use App\models\Project;
use App\models\Group;
use App\models\AccountGroup;
use App\models\Schedule;
use App\models\ScheduleApproval;
use Illuminate\Support\Facades\Validator;
use DB;
use Illuminate\Support\Facades\Input;

class AdvisedGroupsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $substatus = ['Submitted To Content Adviser'];
        $user_id = Auth::id(); 

        $groups = DB::table('group')
        ->join('panel_group','panel_group.panelGroupNo','=','group.groupNo')
        ->join('project','project.projGroupNo','=','group.groupNo')
        ->join('panel_verdict','panel_verdict.panelVerdictNo','=','project.projPVerdictNo')
        ->join('schedule','schedule.schedGroupNo','=','group.groupNo')
        ->join('account','account.accNo','=','group.groupCAdviserNo')
        ->select('schedule.*','panel_group.*','account.*','project.*','group.*','panel_verdict.*')
        ->where('account.accType','=','2')
        ->where('group.groupCAdviserNo','=',$user_id)
        ->whereIn('group.groupStatus', ['Submitted To Content Adviser'])
        ->paginate(3); 
        //return $this->calcSchedStatus($sched[0]->panelCGroupNo);
        return view('pages.advised_groups.index')->with('data',$groups);
    }

    public function search() {
        $q = Input::get('q');
        $user_id = Auth::id(); 
        if($q != '') {
            $data = DB::table('group')
            ->join('panel_group','panel_group.panelGroupNo','=','group.groupNo')
            ->join('project','project.projGroupNo','=','group.groupNo')
            ->join('panel_verdict','panel_verdict.panelVerdictNo','=','project.projPVerdictNo')
            ->join('schedule','schedule.schedGroupNo','=','group.groupNo')
            ->join('account','account.accNo','=','group.groupCAdviserNo')
            ->select('schedule.*','panel_group.*','account.*','project.*','group.*','panel_verdict.*')
            ->where('account.accType','=','2')
            ->where('group.groupCAdviserNo','=',$user_id)
            ->whereIn('group.groupStatus', ['Submitted To Content Adviser'])
            ->where('group.groupName','LIKE', "%".$q."%")
            ->paginate(3);
        } else {
            return redirect()->action('AdvisedGroupsController@index');
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
        $group = Group::find($id);
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

    public function contentAdvAppForSched(Request $request){
        if (is_null($request->input('submit'))){
            return redirect()->back()->with('error', 'Approval failed.');
        } 
        $group = Group::find($request->input('groupNo'));
        try{
            DB::beginTransaction();
            $group->groupStatus = 'Approved by Content Adviser';
            $group->save();
            DB::commit();
            return redirect()->back()->with('success', 'The document of group : ' . $group->groupName . ' was approved.');
            
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'The document of group : ' . $group->groupName . ' was not approved.');
        }
    }

    public function contentAdvCorrectForSched(Request $request){
        $validator = Validator::make($request->all(), [
            'document_link' => ['required','max:255'],
        ]);
        if ($validator->fails()) {
			return redirect()->back()->withInput()->withErrors($validator);
        } 

        $group = Group::find($request->input('groupNo'));
        $project = DB::table('project')->where('project.projGroupNo','=',$group->groupNo)
        ->pluck('projNo');
        $project = Project::find($project[0]);
        try{
            DB::beginTransaction();
            $group->groupStatus = 'Corrected by Content Adviser';
            $project->projDocumentLink = $request->input('document_link');
            $group->save();
            $project->save();
            DB::commit();
            return redirect()->back()->with('success', 'The document of group : ' . $group->groupName . ' was corrected.');
            
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'The document of group : ' . $group->groupName . ' was not corrected.');
        }
    }
}
