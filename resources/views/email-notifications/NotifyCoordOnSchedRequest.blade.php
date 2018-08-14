<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="UTF-8">
        <meta name="csrf-token" content="{{ csrf_token() }}">
    </head>
    <body>
        <?php
            $data = DB::table('group')
            ->join('schedule','schedule.schedGroupID','=','group.groupID')
            ->join('project','project.projGroupID','=','group.groupID')
            ->where('group.groupID','=',$grp)
            ->first();
            $panel = DB::table('account')
            ->where('account.accID','=',$acc)
            ->first();
        ?>

        <div style="padding:20px;color:black;font-size:1.1em;width:100%;">
            <div style="margin-left:30px;">
            <section>
                <a href="http://cttracer.epizy.com" target="_blank" style="text-decoration:none">
                <img src="http://cttracer.epizy.com/img/design/logo/logo2.png" style="width: 50px;">
                </a>
                <a href="http://cttracer.epizy.com" target="_blank" style="text-decoration:none;color:black">
                <span style="font-size: 25px;font-weight:bold;">CT-Tracer</span>
                </a>
                <table>
                    <tr>
                        <td>
                            
                        </td>
                        <td>
                            
                        </td>
                    </tr>
                </table>
            </section>
            <section>
                <h3>Request for schedule</h3>
                <h5>To : {{$panel->accTitle}} {{$panel->accFName}} {{$panel->accMInitial}} {{$panel->accLName}}<br>
                For the group of : {{$data->groupName}}</h5>
                <h5>On Project : {{$data->projName}}</h5>
            </section>
        
            <br>
            <br>
            <br>
            <ul style="list-style-type: none;">
                <li><small>© 2018 CT-Tracer</small></li>
            </ul>
            </div>
        </div>
    </body>
</html>
