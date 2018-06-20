<?php

namespace App\models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;

class ScheduleApproval extends Model
{
    protected $table = "schedule_approval";
    public $primaryKey = "schedAppNo";
    public $timestamps = false;
}
