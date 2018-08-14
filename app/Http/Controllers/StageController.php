<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\models\Group;
use App\models\Stage;
use App\models\AccessControl;
use DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Auth;
use Exception;

class StageController extends Controller
{
    public function __construct()
    {
        $ac = new AccessControl;
        $accesscontrol = $ac->status; 
        if($accesscontrol == true) {
            $this->middleware('auth');
            $this->middleware('roles', ['roles'=> ['Capstone Coordinator']]);
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
        $data = DB::table('stage')
            ->paginate(10);
        return view('pages.stages.index')->withData($data);    
    }

    public function search(Request $request)
    {
        $q = Input::get('q');
      
        if($q != '') {
            $data = DB::table('stage')
            ->select('stage.*')
            ->where('stage.stageName', 'LIKE', "%".$q."%")
            ->orWhere('stage.StageNo','LIKE', "%".$q."%")
            ->paginate(10);
        } else {
            $data = DB::table('stage')
            ->paginate(10);
        }
        $data->appends(array(
            'q' => Input::get('q')
        ));
           
        return view('pages.stages.index')->withData($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $next = DB::table('stage')->max('stageNo');
        if($next >= 1) {
            $next = (int)$next + 1;
        } else {
            $next = 1;
        }
        $pgroup = DB::table('account')
        ->where('account.accType','=','2')
        ->get();

        $data = ['next'=> $next,'pgroup'=>$pgroup];
        return view('pages.stages.create')->with('data',$data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
        DB::beginTransaction();
        $validStagePanel = ['All','Custom'];
        $validator = Validator::make($request->all(), [
            'stage_number' => ['required','unique:stage,stageNo','min:1','max:9','Integer'],
            'stage_name' => ['required','max:50'],
            'stage_defense_duration' => ['Integer','required'],
            'stage_panel' => ['required',Rule::In($validStagePanel)],
          	'minimum_panel_members_for_schedule_approval' => ['required','Integer','min:1'],
        ]);
        if($validator->fails()) {
            return redirect()->back()->withInput($request->all)->withErrors($validator);
        }
        $stage = new Stage;        
        $stage->stageNo = $request->input('stage_number');
        $stage->stageName = trim(ucwords(strtolower($request->input('stage_name'))));
        $stage->stageDefDuration = $request->input('stage_defense_duration');
        $stage->stagePanel = $request->input('stage_panel');
        if($request->input('stage_link') != '') {
            $validator = Validator::make($request->all(), [
                'stage_link' => ['max:150','active_url'],
            ]);
            if($validator->fails()) {
                return redirect()->back()->withInput($request->all)->withErrors($validator);
            }
            $stage->stageRefLink = $request->input('stage_link');
        } else {
            $stage->stageRefLink = '';
        }
          
        if(!is_null($request->input('EditGroupPanel'))) {
          $stage->requireChairSched = '1';
        } else {
          $stage->requireChairSched = '0';
        }
        if(!is_null($request->input('minimum_panel_members_for_schedule_approval'))) {
          $stage->minSchedPanel = $request->input('minimum_panel_members_for_schedule_approval');
        } else {
          $stage->minSchedPanel = '1';
        }
        $stage->save();
        DB::commit();
        } catch(Exception $e) {
            DB::rollback();
          	//return dd($e);
            return redirect()->back()->withInput($request->all)->withErrors('Stage Information was not Updated!');
        }
        return redirect()->back()->withInput($request->all)->with('success','Stage Information was Updated!');
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
        $stage = Stage::find($id);
        $pgroup = DB::table('account')
        ->where('account.accType','=','2')
        ->get();
        $data = ['stage'=>$stage,'pgroup'=>$pgroup];
        return view('pages.stages.edit')->with('data',$data); 
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
        try {
            DB::beginTransaction();
            $validCount = DB::table('account')->where('account.accType','=','2')->count();
            $validStagePanel = ['All','Custom'];
            $validator = Validator::make($request->all(), [
                'stage_number' => ['required','min:1','max:9','Integer'],
                'stage_name' => ['required','max:50'],
                'stage_defense_duration' => ['Integer','required'],
                'stage_panel' => ['required',Rule::In($validStagePanel)],
                'minimum_panel_members_for_schedule_approval' => ['required',"max:{$validCount}"]
            ]);
            if(is_null($request->input('EditGroupPanel'))) {
                $request['EditGroupPanel'] = 'off';
            }

            $stage = Stage::find($id);
            if($stage->stageNo != $request->input('stage_number')) {
                $validator = Validator::make($request->all(), [
                    'stage_number' => ['unique:stage,stageNo','min:1','max:9','Integer'],
                ]);
                $stage->stageNo = $request->input('stage_number');
            }
            if ($validator->fails()) {
                return redirect()->back()->withInput($request->all)->withErrors($validator);
            } 
            $stage->stageName = trim(ucwords(strtolower($request->input('stage_name'))));
            $stage->stageDefDuration = $request->input('stage_defense_duration');
            $stage->stagePanel = $request->input('stage_panel'); 
            if($request->input('stage_link') != '') {
                $validator = Validator::make($request->all(), [
                    'stage_link' => ['max:150','active_url'],
                ]);
                if($validator->fails()) {
                    return redirect()->back()->withInput($request->all)->withErrors($validator);
                }
                $stage->stageRefLink = $request->input('stage_link');
            } else {
                $stage->stageRefLink = '';
            }
            if(!is_null($request->input('EditGroupPanel'))) {
              $stage->requireChairSched = '1';
            } else {
              $stage->requireChairSched = '0';
            }
            if(!is_null($request->input('minimum_panel_members_for_schedule_approval'))) {
              $stage->minSchedPanel = $request->input('minimum_panel_members_for_schedule_approval');
            } else {
              $stage->minSchedPanel = '1';
            }
            $stage->save();
            DB::commit();
        } catch(Exception $e) {
            DB::rollback();
          	//return dd($e);
            return redirect()->back()->withInput($request->all)->withErrors('Stage Information was not Updated!');
        }
        return redirect()->action('StageController@edit',[
            'id'=>$stage->stageNo
        ])->with('success','Stage Information was Updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $delete = Stage::find($id);
        $delete->delete();
        return redirect()->back()->with('success', 'Stage Information has been Deleted!');
    }
}
