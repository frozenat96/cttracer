<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\models\PanelGroup;
use App\models\ScheduleApproval;
use App\models\Schedule;
use App\models\Group;
use App\models\Project;
use App\models\Stage;
use Auth;
use DB;
use Illuminate\Support\Facades\Input;
use App\Events\eventTrigger;
use App\Http\Controllers\MailController;
use App\models\Notification;
use App\models\AccessControl;
use Mail;
use App\Notifications\NotifyCoordOnSchedFinalize;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Spatie\GoogleCalendar\Event;
use Carbon\Carbon;

class ScheduleController extends Controller
{
    public function __construct()
    {
        $ac = new AccessControl;
        $accesscontrol = $ac->status; 
        if($accesscontrol == true) {
            $this->middleware('auth');
            $this->middleware('roles', ['roles'=> ['Capstone Coordinator'],'only' =>['coordRequestForSchedForm','coordRequestForSched']]);
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
        $sched = DB::table('project')
        ->join('stage','stage.stageNo','=','project.projStageNo')
        ->join('group','group.groupID','=','project.projGroupID')
        ->join('schedule','schedule.schedGroupID','=','group.groupID')
        ->join('panel_verdict','panel_verdict.panelVerdictNo','=','project.projPVerdictNo')
        ->select('schedule.*','project.*','group.*','panel_verdict.*')
        ->whereIn('group.groupStatus', ['Ready for Defense'])
        ->whereNotIn('project.projPVerdictNo',['2','3','7'])
        ->where('schedule.schedStatus','=','Ready')
        ->where('schedule.schedDate','>=',Carbon::now())
        ->orderBy('schedule.schedDate')
        ->orderBy('schedule.schedTimeStart')
        ->paginate(5); 
        //return $this->calcSchedStatus($sched[0]->panelCGroupID);
        return view('pages.final_schedule_list.index')->with('data',$sched);
    }

    public function search() {
        $user_id = Auth::user()->getId();
        $q = Input::get('q');
      
        if($q != '') {
            $data = DB::table('project')
            ->join('stage','stage.stageNo','=','project.projStageNo')
            ->join('group','group.groupID','=','project.projGroupID')
            ->join('schedule','schedule.schedGroupID','=','group.groupID')
            ->join('panel_verdict','panel_verdict.panelVerdictNo','=','project.projPVerdictNo')
            ->select('schedule.*','project.*','group.*','panel_verdict.*')
            ->whereIn('group.groupStatus', ['Ready for Defense'])
            ->whereNotIn('project.projPVerdictNo',['2','3','7'])
            ->where('schedule.schedStatus','=','Ready')
            ->where(function ($query) use ($q){
                $query->where('group.groupName','LIKE', "%".$q."%")
                ->orWhere('project.projName','LIKE', "%".$q."%");
            })
            ->orderBy('schedule.schedDate')
            ->orderBy('schedule.schedTimeStart')
            ->paginate(5); 
        } else {
            return redirect()->action('ScheduleController@index');
        }

        $data->appends(array(
            'q' => Input::get('q')
        ));
           
        return view('pages.final_schedule_list.index')->withData($data);
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

    public function coordRequestForSchedForm($id) {
        $data = DB::table('group')
        ->where('group.groupID','=',$id)
        ->first();
        //return dd($data);
        return view('pages.quick_view.create-schedule')->with('data',$data);
    }

    public function coordRequestForSched(Request $request) {
        try {
            DB::beginTransaction();
            $validType = ['Oral Defense','Round Table'];
            $validator = Validator::make($request->all(), [
                'date' => ['required','date_format:Y-m-d'],
                'starting_time' => ['required','date_format:H:i'],
                'place' => ['required','max:100','regex:/^[0-9A-Za-z: \'-]+$/'],
                'schedule_type' => ['required','max:20',Rule::In($validType)],
                'grp' =>  ['required']
            ]);
            if ($validator->fails()) {
                return redirect()->back()->withInput($request->all)->withErrors($validator);
            } 
           


            $now = date("H:i");
            $today = date("Y-m-d");
            $pRes = new Project;
            $now_format = date_format(new \Datetime($now),"H:i A");
            if(($now > $request->input('starting_time')) && ($today == $request->input('date'))) {
                return redirect()->back()->withInput($request->all)->withErrors(['Schedule Information was not created.',"The starting time must greater than {$now_format}"]);   
            }

            $id = $request->input('grp');
            $stg = new Stage;
            $data = DB::table('group')
            ->join('schedule','schedule.schedGroupID','=','group.groupID')
            ->join('panel_group','panel_group.panelCGroupID','=','group.groupID')
            ->join('schedule_approval','schedule_approval.schedPanelGroupID','=','panel_group.panelGroupID')
            ->join('account','account.accID','=','panel_group.panelAccID')
            ->where('group.groupID','=',$id)
            ->where('panel_group.panelGroupType','=',$stg->current($id))
            ->get();
            if(is_null($data) || !count($data)) {
                DB::rollback();
                return redirect()->back()->withInput($request->all)->withErrors(['Schedule Information was not created.','The group does not have any panel members.']);  
            }

            //find the stage of the group
            $stg1 = DB::table('project')
            ->join('stage','stageNo','=','project.projStageNo')
            ->where('project.projGroupID','=',$id)
            ->first();
            
            $sc0 = Schedule::find($data[0]->schedID);
            $sc0->schedDate = $request->input('date');
            $sc0->schedTimeStart = $request->input('starting_time');
            $tstart = new Carbon("{$sc0->schedDate} {$sc0->schedTimeStart}");
            $sc0->schedTimeEnd = $tstart->addMinutes($stg1->stageDefDuration);
            $sc0->schedPlace = $request->input('place');
            $sc0->schedType = $request->input('schedule_type');
            $sc0->schedStatus = 'Not Ready';

            $stage = new Stage;
            $group = Group::find($id);
            $group->groupStatus = 'Waiting for Schedule Approval';
            $group->save(); 
            $notify = new Notification;
            $pRes->resetSchedApp($group->groupID,'0',1);
            $sc0->save(); 
          	$notify->NotifyPanelOnSchedRequest($group);
            DB::commit();    
            } catch (Exception $e) {
                DB::rollback();
                return redirect()->action('QuickViewController@search', ['q'=>$group->groupName,'status' => 0,'statusMsg'=>'Schedule Information was not created.']);   
            }
              return redirect()->action('QuickViewController@search', ['q'=>$group->groupName,'status' => 1,'statusMsg'=>'Schedule Information was created successfully!']);

    }

}
