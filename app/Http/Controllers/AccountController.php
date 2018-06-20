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
            $acc->accTitle = $request->input('title');
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
        return redirect('/accounts');
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
        $this->validate($request, [
            'given_name' => ['required','max:50'],
            'middle_initial' => ['required','max:2'],
            'last_name' => ['required','max:50'],
            'title' => ['max:20'],
            'email' => ['E-mail','required','max:191'],
            'role' => ['Integer','required',Rule::In($acc_type->all())],
            'group' => ['Integer','nullable',Rule::In($group->all())],
        ]);

        $acc = User::find($id);
        $acc->accFName = trim(ucwords(strtolower($request->input('given_name'))));
        if(strlen($request->input('middle_initial')) != 2){
            $acc->accMInitial = trim(ucwords(strtolower($request->input('middle_initial')))) . '.';
        } else {
            $acc->accMInitial = trim(ucwords(strtolower($request->input('middle_initial'))));
        }
            
        $acc->accLName = trim(ucwords(strtolower($request->input('last_name'))));
        if(in_array($acc->accType,['1','2'])) {
            $acc->accTitle = $request->input('title');
            $acc->accGroupNo = null;
        } elseif($acc->accType=='3') {
            $acc->accTitle = '';
            $acc->accGroupNo = $request->input('group');
        }
        if($acc->accEmail != $request->input('email')) {
            $this->validate($request, [
                'email' => ['unique:account,accEmail'],
            ]);
            $acc->accEmail = $request->input('email');
        } 
        $acc->accType = $request->input('role');
        if($acc->save()) {
        $request->session()->flash('alert-success', 'Account Information was Updated!');
        } else {
        $request->session()->flash('alert-danger', 'Account Information was not Updated!');
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
        $user_id = Auth::id(); 

    }
}
