<?php

namespace App\models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    protected $table = "group";
    public $primaryKey = "groupNo";
    public $timestamps = false;

    public function projects() {
        return $this->hasOne('App\models\Project','projGroupNo');
    }

    public function account() {
        return $this->belongsToMany('App\User','account_group','grpNo','accNo');
    }
        
    public function schedule() {
        return $this->hasOne('App\models\Schedule','schedGroupNo');
    }

    public function initials($str) {
        $ret = '';
        foreach (explode(' ', $str) as $word)
            $ret .= (strtoupper($word[0]) . '.');
        return $ret;
    }
}