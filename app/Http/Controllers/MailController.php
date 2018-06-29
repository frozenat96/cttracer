<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Mail;
use App\mail\SendMail;

class MailController extends Controller
{
    public function NotifyPanelOnSchedRequest_e($request){
        Mail::send(new SendMail($request));
    }

    public function NotifyPanelOnSchedRequest_s(){
        $myRequest = ['grp'=>'1','acc'=>'10','to'=>'carlparioste@su.edu.ph'];
        //Mail::send(new SendMail($myRequest));
        return redirect()->action(
            'MailController@NotifyPanelOnSchedRequest_e', ['request' => $myRequest]
        );
    }
}
