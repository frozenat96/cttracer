<?php

namespace App\Http\Controllers;

use Notification;
use Illuminate\Http\Request;
use App\models\Notification as Notify;
use Auth;
use DB;
use App\Events\eventTrigger;

class NotificationController extends Controller
{

    public function NotifyPanelOnSchedRequest() {
        return $notify = DB::table('notification')
        ->where('notification.notifiable_id','=',Auth::id())
        ->where('notification.type','=', 'App\Notifications\NotifyPanelOnSchedRequest')
        ->get();
    }

    public function NotifyPanelOnSchedRequest_d() {
        DB::table('notification')
        ->where('notification.notifiable_id','=',Auth::id())
        ->where('notification.type','=','App\Notifications\NotifyPanelOnSchedRequest')
        ->delete();
        return redirect()->action('SchedAppController@index');
    }

    public function NotifyPanelOnRevisions() {
        return $notify = Notify::where('notifiable_id','=',Auth::id())
        ->where('type','=','App\Notifications\NotifyPanelOnRevisions')
        ->get();
    }

    public function NotifyAdviserOnSchedRequest() {
        return $notify = Notify::where('notifiable_id','=',Auth::id())
        ->where('type','=','App\Notifications\NotifyAdviserOnSchedRequest')
        ->get();
    }
    
    public function NotifyAdviserOnSchedRequest_d() {
        DB::table('notification')
        ->where('notification.notifiable_id','=',Auth::id())
        ->where('notification.type','=','App\Notifications\NotifyAdviserOnSchedRequest')
        ->delete();
        return redirect()->action('AdvisedGroupsController@index');
    }

    public function NotifyAdviserOnRevisions() {
        return $notify = Notify::where('notifiable_id','=',Auth::id())
        ->where('type','=','App\Notifications\NotifyAdviserOnRevisions')
        ->get();
    }

    public function NotifyCoordOnSchedFinalize() {
        return $notify = Notify::where('notifiable_id','=',Auth::id())
        ->where('type','=','App\Notifications\NotifyCoordOnSchedFinalize')
        ->get();
    }

    public function NotifyCoordOnProjFinalize() {
        return $notify = Notify::where('notifiable_id','=',Auth::id())
        ->where('type','=','App\Notifications\NotifyCoordOnProjFinalize')
        ->get();
    }

    public function NotifyCoordOnReadyForStage() {
        return $notify = Notify::where('notifiable_id','=',Auth::id())
        ->where('type','=','App\Notifications\NotifyCoordOnReadyForStage')
        ->get();
    }

}
