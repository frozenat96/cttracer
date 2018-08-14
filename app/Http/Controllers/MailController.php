<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Mail;
use App\mail\NotifyPanelOnSchedRequest;
use App\mail\NotifyCoordOnSchedFinalize;

class MailController extends Controller
{
    public function NotifyPanelOnSchedRequest_e($request) {
        Mail::send(new NotifyPanelOnSchedRequest($request));
    }

    public function NotifyCoordOnSchedFinalize_e($request) {
        Mail::send(new NotifyCoordOnSchedFinalize($request));
    }
}
