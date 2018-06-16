<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use DB;
use Illuminate\Support\Facades\Input;

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
        $firstname = ucwords($request->input('given_name'));
        $middle_initial = ucwords($request->input('middle_initial')) . '.';
        $lastname = ucwords($request->input('last_name'));
        $this->validate($request, [
            'given_name' => 'required|max:50',
            'middle_initial' => 'required|max:2',
            'last_name' => 'required|max:50',

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
