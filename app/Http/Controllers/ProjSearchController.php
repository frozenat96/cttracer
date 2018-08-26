<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\models\Project;
use App\models\AccessControl;
use Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Webpatser\Uuid\Uuid;
use Exception;

class ProjSearchController extends Controller
{
    public function __construct()
    {
        $ac = new AccessControl;
        $accesscontrol = $ac->status; 
        if($accesscontrol == true) {
            $this->middleware('auth');
            $this->middleware('roles', ['roles'=> ['Panel Member','Capstone Coordinator','Student']]);
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
        $data = DB::table('project')
        ->select('project.*')
        ->where('project.projPVerdictNo','=','7')
        ->paginate(10);

        $q = Input::get('status');
        $msg = Input::get('statusMsg');

        if(!is_null($q) && $q==1) {
            return view('pages.project_search.index')->with('data',$data)->with('success2',$msg);
        } elseif(!is_null($q) && $q==0) {
            return view('pages.project_search.index')->with('data',$data)->with('error',$msg);
        }
        return view('pages.project_search.index')->withData($data);
    }

    public function search(Request $request)
    {
        /*
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
        */
        $q = Input::get('q');
        if($q != '') {
            $data = DB::table('project')
            ->select('project.*')
            ->where('project.projPVerdictNo','=','7')
            ->where('project.projName','LIKE', "%".$q."%") 
            ->paginate(10)
            ->setpath('');
        } else {
            return redirect()->action('ProjSearchController@index');
        }

        $data->appends(array(
            'q' => Input::get('q')
        ));
        
        return view('pages.project_search.index')->with('data',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pages.project_search.create');
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
            $validator = Validator::make($request->all(), [
                'project_name' => ['required','max:150','unique:project,projName'],
                'document_link' => ['required','max:150','active_url'],
            ]);
            if ($validator->fails()) {
                return redirect()->back()->withInput($request->all)->withErrors($validator);
            } 
            
            $project = new Project;
            $project->projID = Uuid::generate()->string;
            $project->projName = $request->input('project_name');
            $project->projDocumentLink = $request->input('document_link');
            $project->projCAdvCorrectionLink = '';
            $project->projStageNo = '1';
            $project->projGroupID = '1';
            $project->projPVerdictNo = '7';
            $project->minProjPanel = '1';
            $project->requireChairProj = '1';
            $project->save();
            DB::commit();
        } catch(Exception $e) {
            DB::rollback();
            return redirect()->back()->withErrors('The project archive was not added!');
        }
        return redirect()->back()->with('success','The project archive has been added successfully!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = Project::find($id);
        return view('pages.project_search.edit')->with('data',$data);
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
            $validator = Validator::make($request->all(), [
                'project_name' => ['required','max:150'],
                'document_link' => ['required','max:150','active_url'],
            ]);
            if ($validator->fails()) {
                return redirect()->back()->withInput($request->all)->withErrors($validator);
            } 

            $project = Project::find($id); 
            if($request->input('project_name') != $project->projName) {
                $validator = Validator::make($request->all(), [
                    'project_name' => ['unique:project,projName'],
                ]);
                if ($validator->fails()) {
                    return redirect()->back()->withInput($request->all)->withErrors($validator);
                } 
                $project->projName = $request->input('project_name');   
            }
            $project->projDocumentLink = $request->input('document_link');
            $project->save();
            DB::commit();
        } catch(Exception $e) {
            DB::rollback();
            return redirect()->back()->withErrors('The project archive was not updated!');
        }
        return redirect()->back()->with('success','The project archive has been updated successfully!');
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
            DB::table('project')
            ->where('project.projID','=',$id)
            ->delete();
            DB::commit();
        } catch(Exception $e) {
            DB::rollback();
            return redirect()->action('ProjSearchController@index', ['status' => 0,'statusMsg'=>['Project Information was not deleted.']]);
        }
        return redirect()->action('ProjSearchController@index', ['status' => 1,'statusMsg'=>['Project Information was deleted.']]);
    }
}
