<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use DB;
use Auth;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $table = "account";
    protected $primaryKey = 'accNo';

    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function group() {
        return $this->belongsToMany('App\models\Group','account_group','accNo','grpNo');
    }

    public function current() {
        $user = DB::table('account')
        ->join('account_type','account_type.accTypeNo','=','account.accType')
        ->select('account.*','account_type.*')
        ->where('account.accNo','=',Auth::id())->get();  
        if(count($user)) {
            return $user;
        } else {
            return 0;
        }

    }
}
