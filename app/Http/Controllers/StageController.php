<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use DB;
use Illuminate\Support\Facades\Input;

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
        $validPanel = ["All","Custom"];
        $this->validate($request, [
            'stage_number' => ['required','unique:stage,stageNo'],
            'stage_name' => ['required','max:50','unique:stage,stageName'],
            'stage_defense_duration' => ['required','min:0'],
            'stage_defense_duration' => ['required','min:0'],
            'stage_panel' => ['required',Rule::In($validPanel)],
        ]);
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
}
