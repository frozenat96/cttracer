<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\models\Group;
use App\models\PanelGroup;
use App\models\AccountType;
use App\models\AccessControl;
use DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Webpatser\Uuid\Uuid;
use Auth;
use Exception;
use Session;

class AccountController extends Controller
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
        $q = Input::get('status');
        $msg = Input::get('statusMsg');
        $accounts = $this->getIndex();
        if(!is_null($q) && $q==1) {
            return view('pages.accounts.index')->with('data',$accounts)->with('success2',$msg);
        } elseif(!is_null($q) && $q==0) {
            return view('pages.accounts.index')->with('data',$accounts)->with('error',$msg);
        }
        return view('pages.accounts.index')->with('data',$accounts);
    }

    private function getIndex() {
        return $accounts = DB::table('account')
        ->join('account_type','account_type.accTypeNo','=','account.accType')
        ->select('account.*','account_type.*')
        ->orderBy('account.accType')
        ->orderBy('account.accLName')
        ->paginate(10);
    }

    public function search(Request $request)
    {
        $q = Input::get('q');
      
        if($q != '') {
            $data = DB::table('account')
            ->join('account_type','account_type.accTypeNo','=','account.accType')
            ->select('account.*','account_type.*')
            ->where(DB::raw('CONCAT(account.accFName," ",account.accMInitial," ",account.accLName," ",account.accTitle)'), 'LIKE', "%".$q."%")
            ->orWhere('account_type.accTypeDescription','LIKE',"%".$q."%")
            ->orderBy('account.accLName')
            ->paginate(10);
        } else {
            return redirect()->action('AccountController@index');
        }
        $data->appends(array(
            'q' => Input::get('q')
        ));
           
        return view('pages.accounts.index')->withData($data);
    }

    public function transfer(Request $request) {
        return redirect()->action('PagesController@transferRole');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $acc_type = DB::table('account_type')
        ->get();
        $group = DB::table('group')
        ->select('group.*')
        ->whereNotIn('group.groupStatus', ['Finished'])->get();
        $data = ['acc_type' => $acc_type, 'group' => $group];
        return view('pages.accounts.create')->with('data',$data);
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
        $group = Group::pluck('groupID');
        $acc_type = AccountType::pluck('accTypeNo');
        $isPanelAll = DB::table('account')->where('account.isChairPanelAll','=','1')->count();
        if(!is_null($request->input('chair_panel')) &&  $isPanelAll) {
            return redirect()->back()->withInput($request->all)->withErrors('There can only be one (1) chair panel member for all panel members.');
        }
        $validTitle = ['','Mr.','Ms.','Mrs.','Asst. Prof.','Prof.','Engr.','Dr.'];
        $validator = Validator::make($request->all(), [
            'given_name' => ['required','max:50','regex:/^[A-Za-zñÑ -\']+$/'],
            'last_name' => ['required','max:50','regex:/^[A-Za-zñÑ. -\']+$/'],
            'title' => ['max:20',Rule::In($validTitle)],
            'email' => ['E-mail','required','max:191','unique:account,accEmail'],
            'role' => ['Integer','required',Rule::In($acc_type->all())],
            'group' => ['nullable'],
        ]);
        if ($validator->fails()) {
			return redirect()->back()->withInput($request->all)->withErrors($validator);
        } 
        $acc = new User;
        $acc->accID = $accID1 = Uuid::generate()->string;
        $acc->accFName = trim(ucwords(strtolower($request->input('given_name'))));
        if(!is_null($request->input('middle_initial'))) {
          $validator = Validator::make($request->all(), [
            'middle_initial' => ['max:2','regex:/^[A-Za -zñÑ.]+$/'],
          ]);
          if ($validator->fails()) {
            return redirect()->back()->withInput($request->all)->withErrors($validator);
          }  

          if(strlen($request->input('middle_initial')) != 2){
            $acc->accMInitial = trim(ucwords(strtolower($request->input('middle_initial')))) . '.';
          } else {
            $acc->accMInitial = trim(ucwords(strtolower($request->input('middle_initial'))));
          }   
        } else {
          $acc->accMInitial = '';
        }
            
        $acc->accLName = trim(ucwords(strtolower($request->input('last_name'))));
        if(in_array($request->input('role'),['1','2'])) {
            $acc->isChairPanelAll = !is_null($request->input('chair_panel')) ? '1' : '0';
            $acc->isActivePanel = '1';
            if($request->input('title') != '') {
                $acc->accTitle = $request->input('title');
            } else {
                $acc->accTitle = '';
            }
            $acc->accgroupID = null;
        } elseif($request->input('role')=='3') {
            $acc->isChairPanelAll = '0';
            $acc->isActivePanel = '0';
            $acc->accTitle = '';
            if($request->input('group')=='none') {
                $acc->accgroupID = null;
            } else {
                $acc->accgroupID = $request->input('group');
            }
        }
        //panel dependencies
        $acc->accEmail = $request->input('email');
        $acc->accType = $request->input('role');
        if ($validator->fails()) {
			return redirect()->back()->withInput($request->all)->withErrors($validator);
		} else {
            $acc->save();
            if(in_array($request->input('role'),['1','2'])) {
                $this->addpanel($accID1);
            }
            DB::commit();
        }  
    } catch (Exception $e) {
        DB::rollback();
        //return dd($e);
        return redirect()->back()->withInput($request->all)->withErrors('Account Information was not Created!');
    }
        return redirect()->back()->with('success','Account Information was Created!');
    }

    private function addpanel($id) {
        $group = Group::all();
        if(!is_null($group)) {
            foreach($group as $grp) {
                $this->addpaneldependencies($grp->groupID,$id);
            }
        }
    }

    private function addpaneldependencies($grpID,$id) {
        //insert new panel_group to database
        $user = User::find($id);
        $panel = new PanelGroup;
        $x = (string) Uuid::generate();
        $panel->panelGroupID = $x;
        $panel->panelCGroupID = $grpID;
        $panel->panelAccID = $id;
        if($user->isChairPanelAll=='1') {
            $panel->panelIsChair = '1';
        } else {
            $panel->panelIsChair = '0';
        }
        $panel->panelGroupType = 'All';
        $panel->save(); 
        //return dd($panel->panelGroupID);
        //project_approval and schedule_approval dependencies
        
        DB::table('schedule_approval')->insert([
            [
            'schedAppID' => Uuid::generate()->string,
            'schedPanelGroupID' => $x,
            'isApproved' => '3',
            'schedAppMsg' => ''
            ]
        ]);
        DB::table('project_approval')->insert([
            [
            'projAppID' => Uuid::generate()->string,
            'projAppPanelGroupID' => $x,
            'isApproved' => '3',
            'revisionLink' => '',
            'projAppComment' => ''
            ]
        ]);
        
    }

    private function deletepaneldependencies($accid) {
        $x = DB::table('schedule_approval')
        ->join('panel_group','panel_group.panelGroupID','=','schedule_approval.schedPanelGroupID')
        ->where('panel_group.panelAccID','=',$accid)
        ->delete();

        $y = DB::table('project_approval')
        ->join('panel_group','panel_group.panelGroupID','=','project_approval.projAppPanelGroupID')
        ->where('panel_group.panelAccID','=',$accid)
        ->delete();

        $z = DB::table('panel_group')
        ->where('panel_group.panelAccID','=',$accid)
        ->delete();
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
        $acc = DB::table('account')->where('account.accID','=',$id)->first();
        $acc_type = DB::table('account_type')
        ->get();
        $group = DB::table('group')
        ->select('group.*')
        ->whereNotIn('group.groupStatus', ['Finished'])->get();
        $data = ['acc_type' => $acc_type, 'group' => $group,'account'=>$acc];
        return view('pages.accounts.edit')->with('data',$data);
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
        $acc = User::find($id);
        $ccCount = DB::table('account')
        ->where('accType','=','1')
        ->count();
        if(in_array($request->input('role'),['3','2']) && $acc->accType=='1' && ($ccCount==1)){
        	return redirect()->back()->withInput($request->all)->withErrors(['Cannot modify the role of this capstone coordinator account.']);
        }
        $group = Group::pluck('groupID');
        $acc_type = AccountType::pluck('accTypeNo');
        $isPanelAll = DB::table('account')->where('account.isChairPanelAll','=','1')->count();
        if(!is_null($request->input('chair_panel')) &&  $isPanelAll && $acc->isChairPanelAll!='1') {
            return redirect()->back()->withInput($request->all)->withErrors('There can only be one (1) chair panel member for all panel members.');
        } else if(is_null($request->input('chair_panel'))) {
            $request['chair_panel'] = 'off';
        }
        if(is_null($request->input('active_panel_member'))) {
            $request['active_panel_member'] = 'off';
        }
        

        $validTitle = ['','Mr.','Ms.','Mrs.','Asst. Prof.','Prof.','Engr.','Dr.'];
        $validator = Validator::make($request->all(), [
            'given_name' => ['required','max:50','regex:/^[A-Za-zñÑ -\']+$/'],  
            'last_name' => ['required','max:50','regex:/^[A-Za-zñÑ. -\']+$/'],
            'title' => ['max:20',Rule::In($validTitle)],
            'email' => ['E-mail','required','max:70'],
            'role' => ['Integer','required',Rule::In($acc_type->all())],
            'group' => ['nullable'],
        ]);
            
            $acc->accFName = trim(ucwords(strtolower($request->input('given_name'))));
          
            if(!is_null($request->input('middle_initial'))) {
              $validator = Validator::make($request->all(), [
                'middle_initial' => ['max:2','regex:/^[A-Za -zñÑ.]+$/'],
              ]);
              if ($validator->fails()) {
                  return redirect()->back()->withInput($request->all)->withErrors($validator);
              }  
          
              if(strlen($request->input('middle_initial')) != 2){
                $acc->accMInitial = trim(ucwords(strtolower($request->input('middle_initial')))) . '.';
              } else {
                  $acc->accMInitial = trim(ucwords(strtolower($request->input('middle_initial'))));
              }   
            } else {
          		 $acc->accMInitial = '';
        	}
            
            $acc->accLName = trim(ucwords(strtolower($request->input('last_name'))));
            /* //should allow many capstone coordinator accounts
            if($request->input('role')=='1' && $acc->accType!='1') {
                $x = DB::table('account')->where('account.accType','=','1')->count();
                if($x) {
                    return redirect()->back()->withErrors( ['Account Information was not Updated.','Only one (1) Capstone Coordinator account is allowed.']);
                }
            }
            */
            if(in_array($request->input('role'),['1','2'])) {
                if($request->input('title') != '') {
                    $acc->accTitle = $request->input('title');
                } else {
                    $acc->accTitle = '';
                }
              	if($request->input('chair_panel')=='on') {
                	$acc->isChairPanelAll = '1';
                } else {
                  	$acc->isChairPanelAll = '0';
                }
              	if($request->input('active_panel_member')=='on') {
                	$acc->isActivePanel = '1';
                } else {
                  	$acc->isActivePanel = '0';
                }
                $acc->accgroupID = null;
            } elseif($request->input('role')=='3') {
                $acc->accTitle = '';
                $acc->accgroupID = !is_null($request->input('group')) ? $request->input('group') : '';
                $acc->isChairPanelAll = '0';
                $acc->isActivePanel = '0';
            }
            if(in_array($request->input('role'),['1','2']) && !in_array($acc->accType,['1','2'])) {
                $this->addpanel($acc->accID);
            } elseif(!in_array($request->input('role'),['1','2']) && in_array($acc->accType,['1','2'])) {
                $this->deletepaneldependencies($acc->accID);
            }
            if(($request->input('role')=='3') && in_array($acc->accType,['1','2'])) {
                DB::table('project')
                ->join('group','group.groupID','=','project.projGroupID')
                ->join('panel_group','panel_group.panelCGroupID','=','group.groupID')
                ->where('panel_group.panelAccID','=',$acc->accID)
                ->decrement('project.minProjPanel',1);
            }
            if(in_array($request->input('role'),['2','3']) && in_array($acc->accType,['1'])) {
                DB::table('application_setting')
                ->where('application_setting.settingCoordID','=',$acc->accID)
                ->delete();
            }

            if($acc->accEmail != $request->input('email')) {
                $validator = Validator::make($request->all(), [
                    'email' => ['unique:account,accEmail'],
                ]);
                $acc->accEmail = $request->input('email');
            } 
            $acc->accType = $request->input('role');
            if ($validator->fails()) {
                return redirect()->back()->withInput($request->all)->withErrors($validator);
            } 
            
            
            $acc->save();
            DB::commit();
        } catch(Exception $e) {
            //return dd($e);
            DB::rollback();
            return redirect()->back()->withInput($request->all)->withErrors('Account Information was not Updated!');
        }
        //return view('/my-project/{id}/edit',['id'=>$id])->with('success','updated');
        return redirect()->back()->with('success','Account Information was Updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        
       
    }

    public function transferRole() {
        $data = DB::table('account')
        ->where('account.accType','=','2')
        ->get();
        return view('pages.accounts.transfer-role')->with('data',$data);
    }

    public function transferExecute(Request $request) {
        try {
            $user_id = Auth::user()->getId(); 
            DB::beginTransaction();
            $valid_panel_members= DB::table('account')
            ->whereIn('account.accType',['2'])
            ->pluck('accID');
            $validator = Validator::make($request->all(), [
                'transferee_account' => ['required',Rule::In($valid_panel_members->all())],
            ]);
            if ($validator->fails()) {
                return redirect()->back()->withInput($request->all)->withErrors($validator);
            }
            
            $transferer = User::find($user_id);
            $transferee = User::find($request->input('transferee_account'));
   
            $transferer->accType = '2';
            $transferee->accType = '1';
            
            $transferee->save();
            $transferer->save();
            DB::commit();
            Auth::logout();
            return view('auth.login')->with('success2','Transfer of account information was successful!');
        } catch (Exception $e) {
            DB::rollback();
            return dd($e);
            return redirect()->back()->withErrors( 'Transfer of account information failed!');
        }

    }

    public function deleteUpdate($id) {
        try{
        DB::beginTransaction();
        $user_id = Auth::user()->getId(); 
        $currentUser = User::find($user_id);
            $deleteAccount = User::find($id);
            if(($currentUser->accType == '1') && ($user_id == $id)) {
                //if trying to delete own account and account type is Capstone Coordinator
                return redirect()->back()->withErrors('Cannot delete capstone coordinator account.');
            } elseif(in_array($deleteAccount->accType,['1','2'])) {
                //if the account to be deleted is a Panel Member account
                $delete1 = DB::table('schedule_approval')
                ->join('panel_group','panel_group.panelGroupID','=','schedule_approval.schedPanelGroupID')
                ->where('panel_group.panelAccID','=',$id)
                ->delete();

                $delete2 = DB::table('project_approval')
                ->join('panel_group','panel_group.panelGroupID','=','project_approval.projAppPanelGroupID')
                ->where('panel_group.panelAccID','=',$id)
                ->delete();

                $delete3 = DB::table('panel_group')
                ->where('panel_group.panelAccID','=',$id)
                ->delete();
                if(!$delete1 || !$delete2 || !$delete3) {
                    DB::rollback();
                    return redirect()->action('AccountController@index', ['status' => 0,'statusMsg'=>['Deletion of account failed.','Rolled back changes']]);
                }
                $this->deleteUpdateDependencies($id);
                $delete4 = DB::table('account')->where('account.accID','=',$id)->delete();
            } else {
                $deleteAccount->delete();
                DB::commit();  
                return redirect()->action('AccountController@index', ['status' => 1,'statusMsg'=>['Account Information has been Deleted!']]);
            }
            if($deleteAccount->accType == '1') {
                //delete application settings
                $delete4 = DB::table('applications_settings')
                ->where('applications_settings.settingCoordID','=',$id)
                ->delete();
            }
            DB::commit();
            return redirect()->action('AccountController@index', ['status' => 1,'statusMsg'=>['Account Information has been Deleted!']]);
        } catch (Exception $e) {
            //return dd($e);
            DB::rollback();
            return redirect()->action('AccountController@index', ['status' => 0,'statusMsg'=>['Deletion of account failed.','Rolled back changes']]);
        } 
    }

    public function deleteUpdateDependencies($id) {
        $cc = DB::table('account')
        ->where('account.accType','=','1')
        ->first();

        $update = DB::table('group')
        ->where('group.groupCAdviserID','=',$id)
        ->update([
            'group.groupCAdviserID' => $cc->accID,
        ]);
    }
}
