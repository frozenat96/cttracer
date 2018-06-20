<?php

namespace App\models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use DB;
use Auth;

class Project extends Model
{
    protected $table = "project";
    public $primaryKey = "projNo";
    public $timestamps = false;

    public function projectInfoByAccount($id) {
        return DB::table('account')
            ->join('group', 'group.groupNo', '=', 'account.accGroupNo')
            ->join('project','project.projGroupNo','=','group.groupNo')
            ->join('panel_verdict', 'panel_verdict.panelVerdictNo', '=', 'project.projPVerdictNo')
            ->join('stage', 'stage.stageNo', '=', 'project.projStageNo')
            ->select('project.*','account.*','group.*','panel_verdict.*','stage.*')
            ->where('account.accNo','=',$id)
            ->get();
    }

    public function projectInfoByGroup($id) {
        return DB::table('account')
            ->join('group', 'group.groupNo', '=', 'account.accGroupNo')
            ->join('project', 'group.groupProjNo', '=', 'project.projNo')
            ->join('panel_verdict', 'panel_verdict.panelVerdictNo', '=', 'project.projPVerdictNo')
            ->join('stage', 'stage.stageNo', '=', 'project.projStageNo')
            ->select('project.*','account.*','group.*','panel_verdict.*','stage.*')
            ->where('group.groupNo','=',$id)
            ->get();
    }

    
}