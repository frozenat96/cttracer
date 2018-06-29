<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use DB;
use Auth;
use App\models\AccountType;

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

    public function roles() {
        return $this->hasOne('App\models\AccountType','accTypeNo');
    }
    
    public function hasAnyRole($roles) {
        if(is_array($roles)) {
            foreach($roles as $role) {
                if($this->hasRole($role)) {
                    return true;
                }
            }
        } else {
            if($this->hasRole($roles)) {
                return true;
            }
        }
        return false;
    }

    public function hasRole($role) {
        $user_id = Auth::id(); 
        $user = DB::table('account')
        ->join('account_type','account_type.accTypeNo','=','account.accType')
        ->select('account.*','account_type.*')
        ->where('account.accNo','=',$user_id)
        ->first();
        $x = $this->roles()->where('accTypeDescription',$role)->first();
        if($user->accTypeDescription == $role) {
            return true;
        }
        return false;

    }

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
