<?php

namespace App\models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model as Eloquent;

class Group extends Eloquent
{
    protected $table = "group";
    public $primaryKey = "groupNo";
    public $timestamps = false;

    public function projects() {
        return $this->hasMany('App\models\Project','groupProjNo');
    }

    public function account() {
        return $this->belongsToMany('App\User','account_group','grpNo','accNo');
    }

}