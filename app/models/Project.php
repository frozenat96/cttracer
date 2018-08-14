<?php

namespace App\models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use App\models\Stage;
use DB;
use Auth;

class Project extends Model
{
    protected $table = "project";
    public $primaryKey = "projID";
    public $timestamps = false;
    public $incrementing = false;

    public function projectInfoByAccount($id) {
        return DB::table('account')
            ->join('group', 'group.groupID', '=', 'account.accgroupID')
            ->join('project','project.projGroupID','=','group.groupID')
            ->join('panel_verdict', 'panel_verdict.panelVerdictNo', '=', 'project.projPVerdictNo')
            ->join('stage', 'stage.stageNo', '=', 'project.projStageNo')
            ->select('project.*','account.*','group.*','panel_verdict.*','stage.*')
            ->where('account.accID','=',$id)
            ->first();
    }

    public function projectInfoByGroup($id) {
        return DB::table('group')
            ->join('account','account.accgroupID','=','group.groupID')
            ->join('project','project.projGroupID','=','group.groupID')
            ->join('panel_verdict', 'panel_verdict.panelVerdictNo', '=', 'project.projPVerdictNo')
            ->join('stage', 'stage.stageNo', '=', 'project.projStageNo')
            ->select('project.*','account.*','group.*','panel_verdict.*','stage.*')
            ->where('group.groupID','=',$id)
            ->first();
    }

    public function resetProjApp($groupID,$num,$type=0) {
        $stage = new Stage;
        $update = DB::table('project_approval')
        ->join('panel_group','panel_group.panelGroupID','=','project_approval.projAppPanelGroupID')
        ->where('panel_group.panelCGroupID','=',$groupID)
        ->when($type, function ($query) use ($stage,$groupID) {
            return $query->where('panel_group.panelGroupType','=',$stage->current($groupID));
        })
        ->where('panel_group.panelGroupType','=',$stage->current($groupID))
        ->update([
            'project_approval.isApproved' => $num
        ]);
    }

    public function resetSchedApp($groupID,$num,$type=0) {
        $stage = new Stage;
        $update = DB::table('schedule_approval')
        ->join('panel_group','panel_group.panelGroupID','=','schedule_approval.schedPanelGroupID')
        ->where('panel_group.panelCGroupID','=',$groupID)
        ->when($type, function ($query) use ($stage,$groupID) {
            return $query->where('panel_group.panelGroupType','=',$stage->current($groupID));
        })
        ->update([
            'schedule_approval.isApproved' => $num,
            'schedule_approval.schedAppMsg' => ''
        ]);
    }
    
}