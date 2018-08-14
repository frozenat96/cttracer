<?php

namespace App\models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use DB;

class Stage extends Model
{
    protected $table = "stage";
    public $primaryKey = "stageNo";
    public $timestamps = false;

    public function current($groupID) {
        return $result = DB::table('group')
        ->join('project','project.projGroupID','=','group.groupID')
        ->join('stage','stage.stageNo','=','project.projStageNo')
        ->where('group.groupID','=',$groupID)
        ->pluck('stagePanel')
        ->first();
        
    }
}
