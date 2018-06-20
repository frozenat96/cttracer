<?php

namespace App\models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;

class Stage extends Model
{
    protected $table = "stage";
    public $primaryKey = "stageNo";
    public $timestamps = false;
}
