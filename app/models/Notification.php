<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

use App\Notifications\NotifyAdviserOnSubmission;
use App\Mail\NotifyAdviserOnSubmission as NotifyAdviserOnSubmission_mail;
use App\Notifications\NotifyPanelOnSchedRequest;
use App\Mail\NotifyPanelOnSchedRequest as NotifyPanelOnSchedRequest_mail;
use App\Notifications\NotifyPanelOnProjectApproval;
use App\Mail\NotifyPanelOnProjectApproval as NotifyPanelOnProjectApproval_mail;

use App\Notifications\NotifyCoordOnSchedRequest;
use App\Mail\NotifyCoordOnSchedRequest as NotifyCoordOnSchedRequest_mail;
use App\Notifications\NotifyCoordOnNextStage;
use App\Mail\NotifyCoordOnNextStage as NotifyCoordOnNextStage_mail;
use App\Notifications\NotifyCoordOnSchedFinalize;
use App\Mail\NotifyCoordOnSchedFinalize as NotifyCoordOnSchedFinalize_mail;

use App\Notifications\NotifyStudentOnAdvCorrected;
use App\Mail\NotifyStudentOnAdvCorrected as NotifyStudentOnAdvCorrected_mail;
use App\Notifications\NotifyStudentOnPanelCorrected;
use App\Mail\NotifyStudentOnPanelCorrected as NotifyStudentOnPanelCorrected_mail;
use App\Notifications\NotifyStudentOnCompletion;
use App\Mail\NotifyStudentOnCompletion as NotifyStudentOnCompletion_mail;
use App\Notifications\NotifyStudentOnFinish;
use App\Mail\NotifyStudentOnFinish as NotifyStudentOnFinish_mail;
use App\Notifications\NotifyStudentOnSchedDisapproved;
use App\Mail\NotifyStudentOnSchedDisapproved as NotifyStudentOnSchedDisapproved_mail;
use App\Notifications\NotifyStudentOnNextStage;
use App\Mail\NotifyStudentOnNextStage as NotifyStudentOnNextStage_mail;

use App\Notifications\NotifyAllOnReady;
use App\Mail\NotifyAllOnReady as NotifyAllOnReady_mail;
//other
use Illuminate\Http\Request; 
use App\User;
use Auth;
use DB;
use Mail;
use App\models\Stage;
use App\Events\eventTrigger;
use Artisan; 

class Notification extends Model
{
    protected $table = "notification";
    public $primaryKey = "id";

    private function getPanel(Group $group) {
        $stage = new Stage;
        return $panel = DB::table('panel_group')
        ->join('account','account.accID','=','panel_group.panelAccID')
        ->where('account.isActivePanel','=','1')
        ->where('panel_group.panelCGroupID','=',$group->groupID)
        ->where('panel_group.panelGroupType','=',$stage->current($group->groupID))
        ->get();
    }

    private function getPanelEmailAll(Group $group) {
        $stage = new Stage;
        return $panelEmailAll = DB::table('panel_group')
        ->join('account','account.accID','=','panel_group.panelAccID')
        ->where('account.isActivePanel','=','1')
        ->where('panel_group.panelCGroupID','=',$group->groupID)
        ->where('panel_group.panelGroupType','=',$stage->current($group->groupID))
        ->pluck('accEmail');
    }

    private function getCoordinator() {
        return $cc = DB::table('account')
        ->where('account.accType','=','1')
        ->first();
    }
    private function getAdviser(Group $group) {
        return $adv = DB::table('group')
        ->join('account','account.accID','=','group.groupCAdviserID')
        ->where('group.groupID','=',$group->groupID)
        ->first();
    }
    private function getStudent(Group $group) {
        return $student = DB::table('group')
        ->join('account','account.accgroupID','=','group.groupID')
        ->where('group.groupID','=',$group->groupID)
        ->get();
    }

    //Panel
    public function NotifyPanelOnSchedRequest(Group $group) {
        $stage = new Stage;
        $panel = $this->getPanel($group);
        $panelEmailAll = $this->getPanelEmailAll($group);
        if(count($panel)<=3) {
            foreach($panel as $p){
                $x = User::find($p->panelAccID);
                $x->notify(new NotifyPanelOnSchedRequest($group));  
                $z = ['grp'=>$group->groupID,'acc'=>$p->panelAccID,'mailName'=>'New Schedule Approval Request','mail'=>'test','to'=>$x->accEmail]; 
                Mail::send(new NotifyPanelOnSchedRequest_mail($z));
            }
        } else {
            $z = ['grp'=>$group->groupID,'acc'=>'1','mailName'=>'New Schedule Approval Request','mail'=>'test2','to'=>$panelEmailAll->all()];
            Mail::send(new NotifyPanelOnSchedRequest_mail($z));
        }
        event(new eventTrigger('trigger'));
    }

    public function NotifyPanelOnProjectApproval(Group $group) {
        $panel = $this->getPanel($group);
        foreach($panel as $p){
            $x = User::find($p->panelAccID);
            $x->notify(new NotifyPanelOnProjectApproval($group));
            $z = ['grp'=>$group->groupID,'acc'=>$p,'mailName'=>'NotifyPanelOnProjectApproval','to'=>$x->accEmail];
            //email notification
            //Mail::send(new NotifyPanelOnProjectApproval_mail($z));
        }
        event(new eventTrigger('trigger')); 
    }

    public function NotifyAdviserOnSubmission(Group $group) {
        $adviser = $this->getAdviser($group);
        $x = User::find($adviser->accID);
        $x->notify(new NotifyAdviserOnSubmission($group));
        $z = ['grp'=>$group->groupID,'acc'=>$adviser,'mailName'=>'NotifyAdviserOnSubmission','to'=>$x->accEmail];
        //email notification
        //Mail::send(new NotifyAdviserOnSubmission_mail($z));
        event(new eventTrigger('trigger'));
    }

    //Coordinator
    public function NotifyCoordOnSchedRequest(Group $group) {
            $CC = $this->getCoordinator();
            $x = User::find($CC->accID);
            $x->notify(new NotifyCoordOnSchedRequest($group));
            $z = ['grp'=>$group->groupID,'acc'=>$CC,'mailName'=>'NotifyCoordOnSchedRequest','to'=>$x->accEmail];
            //email notification 
            //Mail::send(new NotifyCoordOnSchedRequest_mail($z));
            event(new eventTrigger('trigger'));
    }

    public function NotifyCoordOnNextStage(Group $group) {
        $CC = $this->getCoordinator();
        $x = User::find($CC->accID);
        $x->notify(new NotifyCoordOnNextStage($group));
        $z = ['grp'=>$group->groupID,'acc'=>$CC,'mailName'=>"Group of {$group->groupName} is ready for next stage",'to'=>$x->accEmail];
        //email notification
        //Mail::send(new NotifyCoordOnSchedRequest_mail($z));
        event(new eventTrigger('trigger'));
    }

    public function NotifyCoordOnSchedFinalize(Group $group) {
        $CC = $this->getCoordinator();
        $x = User::find($CC->accID); 
        $x->notify(new NotifyCoordOnSchedFinalize($group));
        $z = ['grp'=>$group->groupID,'acc'=>$CC,'mailName'=>"Group of {$group->groupName} is ready for next stage",'to'=>$x->accEmail];
        //email notification
        //Mail::send(new NotifyCoordOnSchedRequest_mail($z));
        event(new eventTrigger('trigger'));
    }

    //Student
    public function NotifyStudentOnAdvCorrected(Group $group) {
        $student = $this->getStudent($group);
        foreach($student as $p){
            $x = User::find($p->accID);
            $x->notify(new NotifyStudentOnAdvCorrected('Your content adviser has corrections to your submission'));
        }
        event(new eventTrigger('trigger'));
    }

    public function NotifyStudentOnPanelCorrected(Group $group) {
        $student = $this->getStudent($group);   
        foreach($student as $p){
            $x = User::find($p->accID);
            $x->notify(new NotifyStudentOnPanelCorrected('Your panel members have corrections to your submission'));
        }
        event(new eventTrigger('trigger'));
    }

    public function NotifyStudentOnSchedDisapproved(Group $group) {
        $student = $this->getStudent($group);
        foreach($student as $p){
            $x = User::find($p->accID);
            $x->notify(new NotifyStudentOnSchedDisapproved('Your schedule was disapproved'));
        }
        event(new eventTrigger('trigger'));
    }

    public function NotifyStudentOnCompletion(Group $group) {
        $student = $this->getStudent($group);
        foreach($student as $p) {
            $x = User::find($p->accID);
            $x->notify(new NotifyStudentOnCompletion('Your project is now waiting for completion'));
        }
        event(new eventTrigger('trigger'));
    }

    public function NotifyStudentOnNextStage(Group $group) {
        $student = $this->getStudent($group);
        foreach($student as $p){
            $x = User::find($p->accID);
            $x->notify(new NotifyStudentOnNextStage('Your stage has been set to the next stage'));
        }
        event(new eventTrigger('trigger'));
    }

    public function NotifyStudentOnFinish(Group $group) {
        $student = $this->getStudent($group);
 
        foreach($student as $p){
            $x = User::find($p->accID);
            $x->notify(new NotifyStudentOnFinish('Your project is now finished'));
        }
        event(new eventTrigger('trigger'));
    }

    public function NotifyAllOnReady(Group $group) {
        $panel = $this->getPanel($group);
        $student = $this->getStudent($group);
        foreach($panel as $p){
            $x = User::find($p->panelAccID);
            $x->notify(new NotifyAllOnReady($group));
            $z = ['grp'=>$group->groupID,'acc'=>$p->panelAccID,'mailName'=>'New Schedule Approval Request','to'=>$x->accEmail];
            //email notification
            //Mail::send(new NotifyAllOnReady_mail($z));
        }
        foreach($student as $p){
            $x = User::find($p->accID);
            $x->notify(new NotifyAllOnReady($group));
        }
        event(new eventTrigger('trigger'));
    }
 
}
