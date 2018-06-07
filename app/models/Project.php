<?php

namespace App\models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model as Eloquent;

class Project extends Eloquent
{
    protected $table = "project";
    public $primaryKey = "projNo";
    public $timestamps = false;

    public function group() {
        return $this->belongsTo('App\models\Group');
    }

}