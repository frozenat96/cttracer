<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Auth;
use DB;
use Illuminate\Support\Facades\Input;

class SchedSettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user_id = Auth::id();
        $datetime_settings = DB::table('datetime_setting')
        ->join('account','account.accNo','=','datetime_setting.dtsAccNo')
        ->select('datetime_setting.*','account.*')
        ->where('account.accNo','=',$user_id)
        ->paginate(10); 
        return view('pages.schedule_settings.index')->with('data',$datetime_settings);
    }

    public function search()
    {
        $q = Input::get('q');
        $user_id = Auth::id();

        if($q != '') {
            $data = DB::table('datetime_setting')
            ->join('account','account.accNo','=','datetime_setting.dtsAccNo')
            ->select('datetime_setting.*','account.*')
            ->where('account.accNo','=',$user_id)
            ->where(function ($query) use ($q){
                $query->where('datetime_setting.dtsDate','LIKE', "%".$q."%")
                ->orWhere('datetime_setting.dtsStartTime','LIKE', "%".$q."%")
                ->orWhere('datetime_setting.dtsEndTime','LIKE', "%".$q."%")
                ->orWhere('datetime_setting.dtsGroupType','LIKE', "%".$q."%");
            })
            ->paginate(10);
        } else {
            return redirect()->action('SchedSettingController@index');
        }

        $data->appends(array(
            'q' => Input::get('q')
        ));
           
        return view('pages.schedule_settings.index')->withData($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pages.schedule_settings.create');
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
}
