<?php

namespace App\models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use App\models\Stage;
use App\models\Project;
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
        try {
            DB::beginTransaction();
            $stage = new Stage;
            $update = DB::table('project_approval')
            ->join('panel_group','panel_group.panelGroupID','=','project_approval.projAppPanelGroupID')
            ->where('panel_group.panelCGroupID','=',$groupID)
            ->when(($type > 0), function ($query) use ($stage,$groupID) {
                return $query->where('panel_group.panelGroupType','=',$stage->current($groupID));
            })
            ->update([
                'project_approval.isApproved' => $num
            ]);
            DB::commit();
        } catch(Exception $e) {
            DB::rollback();
        }
    }

    public function resetSchedApp($groupID,$num,$type=0) {
        try {
            DB::beginTransaction();
            $stage = new Stage;
            $update = DB::table('schedule_approval')
            ->join('panel_group','panel_group.panelGroupID','=','schedule_approval.schedPanelGroupID')
            ->where('panel_group.panelCGroupID','=',$groupID)
            ->when(($type > 0), function ($query) use ($stage,$groupID) {
                return $query->where('panel_group.panelGroupType','=',$stage->current($groupID));
            })
            ->update([
                'schedule_approval.isApproved' => $num,
                'schedule_approval.schedAppMsg' => ''
            ]);
            DB::commit();
        } catch(Exception $e) {
            DB::rollback();
        }
    }

    public function processDocLink($groupID,$option) {
        try {
            DB::beginTransaction();
            $project = DB::table('project')
            ->where('project.projGroupID','=',$groupID)
            ->first();
            $project = Project::find($project->projID);
            switch($option) {
                case 1:
                case "adviser_correction":
                //when given corrections the submitted document link will be stored in the adviser's corrected document link
                $project->projCAdvCorrectionLink = $project->projDocumentLink;
                break;
                case 2:
                case "adviser_reset":
                //Set content adviser's correction link to empty string when the group's document is approved
                $proj2->projCAdvCorrectionLink = '';
                break;
                case 3:
                case "panel_correction":
                //When all panel members are done evaluating a project approval and atleast one (1) panel members gave corrections, the group's current submitted document link will be stored to the panel member's correction link
                $project->projPCorrectionLink = $project->projDocumentLink;
                break;
                case 4:
                case "panel_reset":
                //When all panel members are done evaluating a project approval and no panel members gave corrections, the panel member's correction link will be set to an empty string
                $project->projPCorrectionLink = '';
                break;
            }
            $project->save();
            DB::commit();
        } catch(Exception $e) {
            DB::rollback();
        }
    }
    
}