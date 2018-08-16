<?php

namespace App\models;
use App\models\Group;
use App\models\Project;
use App\models\GroupHistory;
use App\User;
use Auth;
use DB;
use Exception;
use Carbon\Carbon;
use Webpatser\Uuid\Uuid;
use Illuminate\Database\Eloquent\Model;

class GroupHistory extends Model
{
    protected $table = "group_history";
    public $primaryKey = "groupHistID";
    public $timestamps = false;
    public $incrementing = false;


    public function add(Group $group,$activity) {
        try {
            DB::beginTransaction();
            $user_id = Auth::user()->getId();
            $acc = User::find($user_id);
            
            $grp1 = new Group;
            $proj = DB::table('project')
            ->where('project.projGroupID','=',$group->groupID)
            ->first();
    
            $grpHist = new GroupHistory;
            $grpHist->groupHistID = Uuid::generate()->string;
            $grpHist->groupHGroupName = $group->groupName;
            $grpHist->groupHProjName = $proj->projName;
            $grpHist->groupHActivity = $activity;
            $grpHist->groupHTimestamp = Carbon::now();
            $grpHist->groupHAddedBy = "{$acc->accTitle} {$acc->accLName}, {$grp1->initials($acc->accFName)}";
            $grpHist->save();
            DB::commit();
        } catch(Exception $e) {
            return dd($e);
            DB::rollback();
        }
    }



}
