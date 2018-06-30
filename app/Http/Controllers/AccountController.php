<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\models\Group;
use App\models\AccountType;
use DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Auth;

class AccountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $accounts = DB::table('account')
        ->join('account_type','account_type.accTypeNo','=','account.accType')
        ->select('account.*','account_type.*')
        ->paginate(10); 
        return view('pages.accounts.index')->with('data',$accounts);
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
        $group = Group::pluck('groupNo');
        $acc_type = AccountType::pluck('accTypeNo');

        $validator = Validator::make($request->all(), [
            'given_name' => ['required','max:50'],
            'middle_initial' => ['required','max:2'],
            'last_name' => ['required','max:50'],
            'title' => ['max:20'],
            'email' => ['E-mail','required','max:191','unique:account,accEmail'],
            'role' => ['Integer','required',Rule::In($acc_type->all())],
            'group' => ['nullable'],
        ]);
        if ($validator->fails()) {
			return redirect()->back()->withInput()->withErrors($validator);
        } 
        
        $acc = new User;
        $acc->accFName = trim(ucwords(strtolower($request->input('given_name'))));
        if(strlen($request->input('middle_initial')) != 2){
            $acc->accMInitial = trim(ucwords(strtolower($request->input('middle_initial')))) . '.';
        } else {
            $acc->accMInitial = trim(ucwords(strtolower($request->input('middle_initial'))));
        }
            
        $acc->accLName = trim(ucwords(strtolower($request->input('last_name'))));
        if(in_array($request->input('role'),['1','2'])) {
            if($request->input('title') != '') {
                $acc->accTitle = $request->input('title');
            } else {
                $acc->accTitle = '';
            }
            $acc->accGroupNo = null;
        } elseif($request->input('role')=='3') {
            $acc->accTitle = '';
            if($request->input('group')=='none') {
                $acc->accGroupNo = null;
            } else {
                $validator = Validator::make($request->all(), [
                'group' => ['Integer',Rule::In($group->all())],
                ]);
                $acc->accGroupNo = $request->input('group');
            }
        }

        $acc->accEmail = $request->input('email');
        $acc->accType = $request->input('role');
        if ($validator->fails()) {
			return redirect()->back()->withInput()->withErrors($validator);
		} else {
            if($acc->save()) {
                $request->session()->flash('alert-success', 'Account Information was Created!');
            } else {
                $request->session()->flash('alert-danger', 'Account Information was not Created!');
            }
        }   
        //return view('/my-project/{id}/edit',['id'=>$id])->with('success','updated');
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
        $acc = User::find($id);
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
        $group = Group::pluck('groupNo');
        $acc_type = AccountType::pluck('accTypeNo');
        $validator = Validator::make($request->all(), [
            'given_name' => ['required','max:50'],
            'middle_initial' => ['required','max:2'],
            'last_name' => ['required','max:50'],
            'title' => ['max:20'],
            'email' => ['E-mail','required','max:191'],
            'role' => ['Integer','required',Rule::In($acc_type->all())],
            'group' => ['nullable'],
        ]);
        if ($validator->fails()) {
			return redirect()->back()->withInput()->withErrors($validator);
        } 

        try {
            DB::beginTransaction();
            $acc = User::find($id);
            $acc->accFName = trim(ucwords(strtolower($request->input('given_name'))));
            if(strlen($request->input('middle_initial')) != 2){
                $acc->accMInitial = trim(ucwords(strtolower($request->input('middle_initial')))) . '.';
            } else {
                $acc->accMInitial = trim(ucwords(strtolower($request->input('middle_initial'))));
            }
                
            $acc->accLName = trim(ucwords(strtolower($request->input('last_name'))));
            if($request->input('role')=='1') {
                $x = DB::table('account')->where('account.accType','=','1')->count();
                if($x) {
                    return redirect()->back()->with('error', ['Account Information was not Updated.','Only one (1) Capstone Coordinator account is allowed.']);
                }
            }
            if(in_array($acc->accType,['1','2'])) {
                if($request->input('title') != '') {
                    $acc->accTitle = $request->input('title');
                } else {
                    $acc->accTitle = '';
                }
                $acc->accGroupNo = null;
            } elseif($acc->accType=='3') {
                $acc->accTitle = '';
                $acc->accGroupNo = $request->input('group');
            }
            if($acc->accEmail != $request->input('email')) {
                $validator = Validator::make($request->all(), [
                    'email' => ['unique:account,accEmail'],
                ]);
                $acc->accEmail = $request->input('email');
            } 
            $acc->accType = $request->input('role');
            if ($validator->fails()) {
                return redirect()->back()->withInput()->withErrors($validator);
            } 
            $acc->save();
            DB::commit();
            $request->session()->flash('alert-success', 'Account Information was Updated!');
        } catch(\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Account Information was not Updated.');
        }
        //return view('/my-project/{id}/edit',['id'=>$id])->with('success','updated');
        return redirect()->action(
            'AccountController@edit', ['id' => $id]
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
        
       
    }

    public function transferExecute(Request $request) {
        $valid_panel_members= DB::table('account')
        ->where('account.accType','=','2')
        ->pluck('accNo');
        $validator = Validator::make($request->all(), [
            'transferee_account' => ['required',Rule::In($valid_panel_members->all())],
        ]);
        if ($validator->fails()) {
			return redirect()->back()->withInput()->withErrors($validator);
        }

        $cc = DB::table('account')->where('account.accType','=','1')->first();
        
        $transferer = User::find($cc->accNo);
        $transferee = User::find($request->input('transferee_account'));

        if($transferee->accType!='2') {
            return redirect()->back()->with('error', ['Transfer of account information failed!','The account to be transfered to is not a Panel Member.']);
        }
        $temp = User::find($transferer->accNo);
        $temp2 = User::find($transferee->accNo);
    
        $email1 = $transferer->accEmail;
        $email2 = $transferee->accEmail;

        try {
            DB::beginTransaction();

            $transferer->accFName = $transferee->accFName;
            $transferee->accFName = $temp->accFName;

            $transferer->accMInitial = $transferee->accMInitial;
            $transferee->accMInitial = $temp->accMInitial;

            $transferer->accLName = $transferee->accLName;
            $transferee->accLName = $temp->accLName;

            $transferer->accTitle = $transferee->accTitle;
            $transferee->accTitle = $temp->accTitle;

            $transferer->accEmail = $transferer->accEmail . '!';
            $transferee->accEmail = $transferee->accEmail . '!';

            $transferee->save();
            $transferer->save();

            $transferer->accEmail = $email2;
            $transferee->accEmail = $email1;
            $transferee->save();
            $transferer->save();

            DB::commit();
            return redirect()->back()->with('success', 'Transfer of account information was successful!');
        } catch (\Exception $e) {
            return dd($e);
            DB::rollback();
            return redirect()->back()->with('error', 'Transfer of account information failed!');
        }

    }

    public function deleteUpdate($id) {
        $user_id = Auth::id(); 
        $currentUser = User::find($user_id);
        try{
            DB::beginTransaction();
            $deleteAccount = User::find($id);
            if(($currentUser->accType == '1') && ($user_id == $id)) {
                //if trying to delete own account and acount type is Capstone Coordinator
                return redirect()->back()->with('error','Cannot delete capstone coordinator account.');
            } elseif($deleteAccount->accType == '2') {
                //if the account to be deleted is a Panel Member account
         
                $delete1 = DB::table('schedule_approval')
                ->join('panel_group','panel_group.panelGroupNo','=','schedule_approval.schedPGroupNo')
                ->where('panel_group.panelAccNo','=',$id)
                ->delete();

                $delete2 = DB::table('project_approval')
                ->join('panel_group','panel_group.panelGroupNo','=','project_approval.projAppPGroupNo')
                ->where('panel_group.panelAccNo','=',$id)
                ->delete();

                $delete3 = DB::table('panel_group')
                ->where('panel_group.panelAccNo','=',$id)
                ->delete();
                $this->deleteUpdateDependencies($id);
                $delete4 = DB::table('account')->where('account.accNo','=',$id)->delete();
            } elseif($deleteAccount->accType == '1') {
                return redirect()->back()->with('error','Cannot delete capstone coordinator account.');
            } else {
                $deleteAccount->delete();
                return redirect()->back()->with('success', 'Account Information has been Deleted!');
            }
            DB::commit();
            return redirect()->back()->with('success',['Account Information has been Deleted!','Depedency of accounts has been successfully updated']);
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error',['Deletion of account failed.','Rolled back changes']);
        }

          
    }

    public function deleteUpdateDependencies($id) {
        $update = DB::table('group')
        ->where('group.groupCAdviserNo','=',$id)
        ->update([
            'group.groupCAdviserNo' => '1',
        ]);
        return 1;
    }
}
