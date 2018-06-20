<?php

namespace App\models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;

class PanelVerdict extends Model
{
    protected $table = "panel_verdict";
    public $primaryKey = "panelVerdictNo";
    public $timestamps = false;
}
