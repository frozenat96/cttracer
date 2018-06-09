<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\models\Project;
use Auth;

class ProjSearchController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        $data = DB::table('project')
        ->select('project.*')
        ->paginate(1);
        return view('pages.project_search.index')->withData($data);
    }

    public function search(Request $request)
    {
        if($request->search != '') {
        $data=Project::where('projName','LIKE','%'.$request->search.'%')->paginate(1);
        //return redirect()->action('ProjSearchController@index')->with('data',$data)->send();
        $data->appends(array(
            'search' => $request->search
        ));
        } else {
            $data=Project::paginate(1);
        }
        return response()->json($data);
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
    public function show($id,$input)
    {
        switch($id) {
            case 1:
            case '1': $data=Project::where('projName','LIKE','%'.$input."%")->paginate(1);
        }
        return view('pages.project_search.index')->with('data', $id);
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
        /*
           <ul>
            <li v-for="n in pagination.total">{{ n }}>
                <a v-on:click="fetchPaginateProjects(pagination.path+'?search='+search+'&page='+n)">{{n}}</a>
            </li>
        </ul>
        */
    }
}
