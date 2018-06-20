<?php

namespace App\models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;

class PanelGroup extends Model
{
    protected $table = "panel_group";
    public $primaryKey = "panelGroupNo";
    public $timestamps = false;
}
