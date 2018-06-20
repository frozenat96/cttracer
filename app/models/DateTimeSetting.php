<?php

namespace App\models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;

class DateTimeSetting extends Model
{
    protected $table = "datetime_setting";
    public $primaryKey = "dtsSettingNo";
    public $timestamps = false;
}
