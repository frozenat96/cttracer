<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;
use App\models\Stage;
use Auth;
use DB;

class ApplicationSetting extends Model
{
    protected $table = "application_setting";
    protected $primaryKey = 'settingID';
    public $timestamps = false;
    public $incrementing = false;
}
