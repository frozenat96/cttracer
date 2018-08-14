<?php

namespace App\models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;

class ProjectApproval extends Model
{
    protected $table = "project_approval";
    public $primaryKey = "projAppID";
    public $timestamps = false;
    public $incrementing = false;
}
