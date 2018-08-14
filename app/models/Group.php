<?php

namespace App\models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    protected $table = "group";
    public $primaryKey = "groupID";
    public $timestamps = false;
    public $incrementing = false;

    public function projects() {
        return $this->hasOne('App\models\Project','projGroupID');
    }

    public function account() {
        return $this->belongsToMany('App\User','account_group','grpNo','accID');
    }
        
    public function schedule() {
        return $this->hasOne('App\models\Schedule','schedGroupID');
    }

    public function initials($str) {
        $ret = '';
        foreach (explode(' ', $str) as $word)
            $ret .= (strtoupper($word[0]) . '.');
        return $ret;
    }
}