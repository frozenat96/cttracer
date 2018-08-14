<?php

namespace App\Http\Controllers;

use Notification;
use Illuminate\Http\Request;
use App\models\Notification as Notify;
use Auth;
use DB;

class NotificationController extends Controller
{
    public function NotifyDelete($name,$redirect,$input) {
        DB::table('notification')
        ->where('notification.notifiable_id','=',Auth::user()->getId())
        ->where('notification.type','=',trim("App\Notifications\ ") . $name)
        ->delete(); 
        if($input=='null') {
            $input='';
        }
        return redirect()->action($redirect, array('q' => $input));
    }

    public function NotifyGet($name) {
        return $notify = Notify::where('notifiable_id','=',Auth::user()->getId())
        ->where('type','=',trim("App\Notifications\ ") . $name)
        ->get();
    }
    
}
