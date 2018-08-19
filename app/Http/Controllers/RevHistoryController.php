<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Auth;
use App\models\Stage;
use App\models\Group;
use App\models\Project;
use App\models\ProjectApproval;
use App\models\RevisionHistory;
use App\models\AccountGroup;
use App\models\Schedule;
use App\models\ScheduleApproval;
use App\models\PanelVerdict;
use App\models\Notification;
use App\models\AccessControl;
use Illuminate\Support\Facades\Validator;
use DB;
use Illuminate\Support\Facades\Input;
use Exception;
use Illuminate\Validation\Rule;
use Session;

class RevHistoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $ac = new AccessControl;
        $rv = new RevisionHistory;
        $accesscontrol = $ac->status; 
        if($accesscontrol == true) {
            $this->middleware('auth');
            if($rv->status==true) {
            $this->middleware('roles', ['only' => ['update','destroy','truncateRevHistory'],'roles'=> ['Capstone Coordinator']]);
            $this->middleware('roles', ['only' => ['index','search','view','print'],'roles'=> ['Capstone Coordinator','Panel Member']]);
            } else {
                $this->middleware('roles', ['Admin']);
            }
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
        $groups = $this->getIndex();               
        return view('pages.revision_history.index')->with('data',$groups);
    }

    private function getIndex() {
        return $groups = DB::table('revision_history')
        ->join('account','account.accID','=','revision_history.revPanelAccID')
        ->orderBy('revision_history.revStageNo')
        ->orderBy('revision_history.revNo')
        ->orderBy('revision_history.revGroupName')
        ->paginate(10); 
    }

    public function search($query = null)
    {
        $q = Input::get('q');
        if(!is_null($query) && $query!='null') {
        $q = $query;
        }

        if($q != '') {
            $data = DB::table('revision_history')
            ->join('account','account.accID','=','revision_history.revPanelAccID')
            ->where('revision_history.revGroupName','LIKE', "%".$q."%")
            ->orWhere('revision_history.revProjName','LIKE', "%".$q."%")
            ->orderBy('revision_history.revStageNo')
            ->orderBy('revision_history.revNo')
            ->orderBy('revision_history.revGroupName')
            ->paginate(10);
        } else {
            return redirect()->action('RevHistoryController@index');
        }

        $data->appends(array(
            'q' => Input::get('q')
        ));
        return view('pages.revision_history.index')->with('data',$data);
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

    public function view(Request $request)
    {
        $grp = $request->input('grp');
        $stg = $request->input('stg');
        $rev_no = $request->input('rev_no');

        $d = DB::table('revision_history')
        ->join('account','account.accID','=','revision_history.revPanelAccID')
        ->join('stage','stage.stageNo','=','revision_history.revStageNo')
        ->where('revision_history.revStageNo','=',$stg)
        ->where('revision_history.revGroupID','=',$grp)
        ->where('revision_history.revNo','=',$rev_no)
        ->get();

        $data=['projApp'=>$d];
        //return dd($data);
        return view('pages.revision_history.view')->with('data',$data);
    }

    public function print(Request $request)
    {
        $grp = $request->input('grp');
        $stg = $request->input('stg');
        $rev_no = $request->input('rev_no');

        $d = DB::table('revision_history')
            ->join('account','account.accID','=','revision_history.revPanelAccID')
            ->join('stage','stage.stageNo','=','revision_history.revStageNo')
            ->where('revision_history.revStageNo','=',$stg)
            ->where('revision_history.revGroupID','=',$grp)
            ->where('revision_history.revNo','=',$rev_no)
            ->get();
        $data=['projApp'=>$d];
        //return dd($data);
        return view('pages.revision_history.print')->with('data',$data);
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $rev = DB::table('revision_history')
        ->join('account','account.accID','=','revision_history.revPanelAccID')
        ->where('revision_history.revID','=',$id)
        ->first();
        $stages = Stage::all();
        $data = $rev;
        return view('pages.revision_history.edit')->with('data',$data)->with('stage',$stages);
    }

    public function deleteAllByGroup($id) {
        try {
            DB::beginTransaction();
            DB::table('revision_history')
            ->where('revision_history.revGroupID','=',$id)
            ->delete();
            DB::commit();
        } catch(Exception $e) {
            DB::rollback();
            return redirect()->back()->withErrors('Revision History data of the group was not deleted!');
        }
        return redirect()->back()->withSuccess('Revision History data of the group was deleted!');
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
        //stage_no,comment,status,revision_link,
        try {
            DB::beginTransaction();
            $validator = Validator::make($request->all(), [
                'comment' => ['max:1600'],
                'status' => ['required','min:1','max:2','Integer'],
            ]);
            if ($validator->fails()) {
                return redirect()->back()->withInput($request->all)->withErrors($validator);
            } 
            $rev = RevisionHistory::find($id);
            if(!is_null($request->input('comment'))) {
                $rev->revComment = $request->input('comment');
            } else {
                $rev->revComment = '';
            }
            $rev->revStatus = $request->input('status');
            if($request->input('status')=='1') {
                $rev->revLink = '';
            } elseif($request->input('status')=='2') {
                $validator = Validator::make($request->all(), [
                    'revision_link' => ['max:150','active_url'],
                ]);
                if ($validator->fails()) {
                    return redirect()->back()->withInput($request->all)->withErrors($validator);
                } 
                $rev->revLink = $request->input('revision_link');
            }
            $rev->save();
            DB::commit();
        } catch(Exception $e) {
            return dd($e);
            DB::rollback();
            return redirect()->back()->withInput($request->all)->withErrors('Revision Information was not Updated!');
        }
        return redirect()->back()->with('success','Revision Information was Updated!');
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
            DB::table('revision_history')
            ->where('revID','=',$id)
            ->delete();
            DB::commit();
        } catch(Exception $e) {
            DB::rollback();
            return redirect()->back()->withErrors('Revision Information was not deleted!');
        }
        return redirect()->back()->withSuccess('Revision Information was deleted!');
    }

    public function truncateRevHistory() {
        try {
            DB::beginTransaction();
            DB::table('revision_history')->truncate();
            DB::commit();
        } catch(Exception $e) {
        DB::rollback();
        Session::flash('danger','Deletion of revision history failed!');
        return view('pages.index')->with('errors','Deletion of revision history failed!');
        }
        Session::flash('success','All revision history has been deleted!');
        return view('pages.index')->with('success','All revision history has been deleted!');
    }
}
