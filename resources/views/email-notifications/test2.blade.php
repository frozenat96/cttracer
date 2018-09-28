<?php
            date_default_timezone_set('Asia/Manila');
            $data = DB::table('group')
            ->join('schedule','schedule.schedGroupID','=','group.groupID')
            ->join('project','project.projGroupID','=','group.groupID')
            ->where('group.groupID','=',$grp)
            ->first();
            $panel = DB::table('account')
            ->where('account.accID','=',$acc)
            ->first();
            $greet = "";
            $time = date("H");
            if ($time < "12") {
                $greet = "good morning.";
            } elseif ($time >= "12" && $time < "17") {
                $greet = "good afternoon.";
            } elseif ($time >= "17") {
                $greet = "good evening.";
            }
        ?>

<div class=""><div class="aHl"></div><div id=":jm" tabindex="-1"></div><div id=":jt" class="ii gt"><div id=":i4" class="a3s aXjCH m1645e10c67c9e1c6"><div bgcolor="#ffffff" style="font-family:Roboto,Helvetica,Arial,sans-serif;margin:0;padding:0"><table border="0" cellpadding="0" cellspacing="0" role="presentation" style="border-collapse:collapse;margin:0" width="100%"><tbody><tr bgcolor="#263238">
    <td bgcolor="#FFC694" 
    style="text-align:center"><div style="height:32px"></div><img aria-hidden="true" style="display:inline-block;margin:0" height="80px" width="80px" src="http://cttracer.epizy.com/img/design/logo/logo.png" class="CToWUd"><div style="height:16px"></div><div style="height:32px"></div></td></tr></tbody></table><div style="padding:0 32px" width="100%"><table align="center" cellpadding="0" cellspacing="0" role="presentation" style="background-color:#ffffff;border-collapse:collapse;max-width:528px;min-width:256px" width="100%"><tbody><tr height="48px"></tr><tr><td style="color:black;font-size:20px;font-weight:700">To whom it may concern,</td></tr><tr height="24px"></tr><tr><td style="color:black;font-size:14px;font-weight:400">The group of {{$data->groupName}} requested for approval of schedule. <a href="http://classroom.google.com/c/MTQ4MDU5MDczMTFa" style="color:#263238;font-weight:400" target="_blank" data-saferedirecturl="https://www.google.com/url?hl=en&amp;q=http://classroom.google.com/c/MTQ4MDU5MDczMTFa&amp;source=gmail&amp;ust=1530720930449000&amp;usg=AFQjCNELCQuq74eIlkja76Bdnzo3kPzgXQ"></a>.</td></tr><tr height="24px"></tr><tr><td><table bgcolor="#fafafa" border="0" cellpadding="0" cellspacing="0" role="presentation" style="border:1px solid #f0f0f0;border-bottom:1px solid #c0c0c0;border-bottom-left-radius:3px;border-bottom-right-radius:3px;border-top:0" width="100%"><tbody><tr><td rowspan="3" width="24px"></td><td colspan="2" height="24px"></td><td rowspan="3" width="24px"></td></tr><tr><td style="padding-right:24px" valign="top" width="72px"><div style="border-radius:50%;background-color:#263238;height:72px;width:72px"><img alt="Question" height="72px" src="https://ci4.googleusercontent.com/proxy/UnyUI7jQ1dsqD_fTjjixmKez1H2K7PobYq7-W780TSwORJFkJLwT1dEbi_Ww7HEmnISh8rPXB9F3sZeJKDazFb5FKLVie0FeX288kcEIFA=s0-d-e1-ft#https://www.gstatic.com/classroom/email/question_icon.png" width="72px" class="CToWUd"></div></td><td><table cellpadding="0" cellspacing="0" role="presentation"><tbody><tr><td style="color:black;font-size:14px;font-weight:400"></td></tr><tr height="8px"></tr><tr><td style="color:black;font-size:20px;font-weight:500">
    
     
                <h3 style="color:black;">Approval of schedule</h3>
                The group of : {{$data->groupName}} is requesting for your approval of schedule.</h5>
                <h5 style="color:black;">Project Title: {{$data->projName}}</h5>
                <h5 style="color:black;">Schedule Details : </h5>
      
       
                <ul style="list-style-type: none;padding: 0;color:black;">
                    <li style="color:black;">Schedule Date : {{date_format(new Datetime($data->schedDate),"F d, Y")}}</li>
                    <li style="color:black;">Starting Time : {{date_format(new Datetime($data->schedTimeStart),"h:i A")}}</li>
                    <li style="color:black;">Ending Time : {{date_format(new Datetime($data->schedTimeEnd),"h:i A")}}</li>
                    <li style="color:black;">Place : {{$data->schedPlace}}</li>
                    <li style="color:black;">Type of Defense : {{$data->schedType}}</li>
                </ul>
  				<h5 style="color:black;">Click <a href="http://cttracer.epizy.com" target="_blank">here</a> to login</h5>
  				

</td></tr><tr height="8px"></tr><tr><td style="color:#737373;font-size:14px;font-weight:400"></td></tr><tr height="16px"></tr><tr><td><table border="0" cellpadding="0" cellspacing="0" role="presentation" style="border-collapse:collapse;display:inline-block"><tbody><tr><td>
    
    </td></tr></tbody></table></td></tr></tbody></table></td></tr><tr><td colspan="2" height="24px"></td></tr></tbody></table></td></tr><tr height="24px"></tr><tr><td style="color:black;font-size:14px;font-weight:400">For inquiries, you may contact us at <a href="javascript:void(0);" style="text-decoration:none;color:black;">cttracer05102018@gmail.com</a></td></tr><tr height="48px" ></tr><tr><td><div style="border-top:1px solid #a0a0a0;margin:0 auto"></div><div style="text-align:center;padding-top:24px;margin-bottom:8px"><a href="cttracer.epizy.com"><img alt="Google logo" height="80px" src="http://cttracer.epizy.com/img/design/logo/logo.png" width="80px" class="CToWUd"></a></div><div style="color:#a0a0a0;font-size:12px;font-weight:400;text-align:center">CT-Tracer 2018<br><a href="cttracer.epizy.com">Dumaguete City, </a><br><a href="#">Negros Oriental 6200 Philippines</a></div></td></tr></tbody></table></div></div></div><div class="yj6qo"></div></div><div id=":ji" class="ii gt" style="display:none"><div id=":jh" class="a3s aXjCH undefined"></div></div><div class="hi"></div></div>