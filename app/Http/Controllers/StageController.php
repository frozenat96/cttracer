<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\models\Group;
use App\models\Stage;
use DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Auth;

class StageController extends Controller
{
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
        $data = ['next'=> $next];
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
        $validStagePanel = ['All','Custom'];
        $validator = Validator::make($request->all(), [
            'stage_number' => ['required','unique:stage,stageNo'],
            'stage_name' => ['required','max:50'],
            'stage_defense_duration' => ['Integer','required'],
            'stage_panel' => ['required',Rule::In($validStagePanel)],
            'stage_link' => ['max:255'],
        ]);
        
        $stage = new Stage;        
        $stage->stageNo = $request->input('stage_number');
        $stage->stageName = trim(ucwords(strtolower($request->input('stage_name'))));
        $stage->stageDefDuration = $request->input('stage_defense_duration');
        $stage->stagePanel = $request->input('stage_panel');
        if($request->input('stage_link') != '') {
            $stage->stageRefLink = $request->input('stage_link');
        } else {
            $stage->stageRefLink = '';
        }
        if($stage->save()) {
            $request->session()->flash('alert-success', 'Stage Information was Updated!');
        } else {
            $request->session()->flash('alert-danger', 'Stage Information was not Updated!');
        }
        return redirect()->back();
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
        $data = ['stage'=>$stage];
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
        $validStagePanel = ['All','Custom'];
        $validator = Validator::make($request->all(), [
            'stage_number' => ['required'],
            'stage_name' => ['required','max:50'],
            'stage_defense_duration' => ['Integer','required'],
            'stage_panel' => ['required',Rule::In($validStagePanel)],
            'stage_link' => ['max:255'],
        ]);
        
        $stage = Stage::find($id);
        if($stage->stageNo != $request->input('stage_number')) {
            $validator = Validator::make($request->all(), [
                'stage_number' => ['unique:stage,stageNo'],
            ]);
            $stage->stageNo = $request->input('stage_number');
        }
        if ($validator->fails()) {
			return redirect()->back()->withInput()->withErrors($validator);
        } 
        $stage->stageName = trim(ucwords(strtolower($request->input('stage_name'))));
        $stage->stageDefDuration = $request->input('stage_defense_duration');
        $stage->stagePanel = $request->input('stage_panel');
        if($request->input('stage_link') != '') {
            $stage->stageRefLink = $request->input('stage_link');
        } else {
            $stage->stageRefLink = '';
        }
        if($stage->save()) {
            $request->session()->flash('alert-success', 'Stage Information was Updated!');
            } else {
            $request->session()->flash('alert-danger', 'Stage Information was not Updated!');
            }
        return redirect()->action(
            'StageController@edit', ['id' => $id]
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
        $delete = Stage::find($id);
        $delete->delete();
        return redirect()->back()->with('success', 'Stage Information has been Deleted!');
    }
}
