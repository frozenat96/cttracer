<?php

namespace App\models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;

class AccountType extends Model
{
    protected $table = "account_type";
    public $primaryKey = "accTypeNo";
    public $timestamps = false;
}
