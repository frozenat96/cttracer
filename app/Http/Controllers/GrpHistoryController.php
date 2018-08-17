<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\models\GroupHistory;
use Exception;
use Illuminate\Support\Facades\Input;
use Session;

class GrpHistoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    
    public function index()
    {
        $data = GroupHistory::
        orderBy('group_history.groupHGroupName')
        ->orderBy('group_history.groupHProjName')
        ->paginate(10); 
        return view('pages.group_history.index')->with('data',$data);
    }

    public function search()
    {
        $q = Input::get('q');
        if($q != '') {
            $data = DB::table('group_history')
            ->where('group_history.groupHGroupName','LIKE', "%".$q."%")
            ->orWhere('group_history.groupHProjName','LIKE', "%".$q."%")
            ->orderBy('group_history.groupHGroupName')
            ->orderBy('group_history.groupHProjName')
            ->paginate(10);
        } else {
            return redirect()->action('GrpHistoryController@index');
        }

        $data->appends(array(
            'q' => Input::get('q')
        ));
           
        return view('pages.group_history.index')->with('data',$data)->with('q',$q);
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
        try {
            DB::beginTransaction();
            DB::table('group_history')
            ->where('groupHistID','=',$id)
            ->delete();
            DB::commit();
        } catch(Exception $e) {
            DB::rollback();
            
            return redirect()->back()->withErrors('Group History data was not deleted!');
        }
        return redirect()->back()->withSuccess('Group History data was deleted!');
    }

    public function deleteAll() {
        try {
            DB::beginTransaction();
            DB::table('group_history')
            ->truncate();
            DB::commit();
        } catch(Exception $e) {
            DB::rollback();
            Session::flash('danger','All Group History Information was not deleted!');
            return view('pages.index');
        }
        Session::flash('success','All Group History Information was deleted!');
        return view('pages.index');
    }
}
