<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\models\Group;
use DB;
use Illuminate\Support\Facades\Input;

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
