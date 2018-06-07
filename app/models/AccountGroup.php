<?php

namespace App\models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model as Eloquent;

class AccountGroup extends Eloquent
{
    protected $table = "account_group";
    public $primaryKey = "accGroupNo";
    public $timestamps = false;

}