<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="UTF-8">
        <meta name="csrf-token" content="{{ csrf_token() }}">
    </head>
    <body>
        <?php
            $data = DB::table('group')
            ->join('schedule','schedule.schedGroupNo','=','group.groupNo')
            ->join('project','project.projGroupNo','=','group.groupNo')
            ->where('group.groupNo','=',$grp)
            ->first();
        ?>

        <div style="padding:20px;color:black;font-size:1.1em;">
            <div style="margin-left:30px;">
            <section>
                <table>
                    <tr>
                        <td>
                            <a href="http://cttracer.epizy.com" target="_blank" style="text-decoration:none">
                <img src="http://cttracer.epizy.com/img/design/logo/logo2.png" style="width: 50px;">
                            </a>
                        </td>
                        <td>
                            <a href="http://cttracer.epizy.com" target="_blank" style="text-decoration:none;color:black">
                            <h2>CT-Tracer</h2>
                            </a>
                        </td>
                    </tr>
                </table>
            </section>
            <section>
                <h3>Approval of schedule</h3>
                <h5>For the group of {{$data->groupName}}</h5>
                <h5>On Project : {{$data->projName}}</h5>
                <bold>Schedule Details : </bold>
            </section>
            <section>
                <ul style="list-style-type: none;">
                    <li>Schedule Date : {{date_format(new Datetime($data->schedDate),"F d, Y")}}</li>
                    <li>Starting Time : {{date_format(new Datetime($data->schedTimeStart),"h:i A")}}</li>
                    <li>Ending Time : {{date_format(new Datetime($data->schedTimeEnd),"h:i A")}}</li>
                    <li>Place : {{$data->schedPlace}}</li>
                    <li>Type of Defense : {{$data->schedType}}</li>
                </ul>
            </section>


            {!!Form::open(['action' => 'SchedAppController@approvalStatus_e', 'method' => 'POST', 'target'=>'_blank']) !!}
            <ul style="list-style-type: none;">
                <li>
                    <span>
                    <label for="disapprove">Disapprove</label> <input type="radio" name="opt" id="disapprove" value="0">
                    </span>
                    <span style="margin-left:20px;">
                    <label for="approve">Approve</label> <input type="radio" name="opt" id="approve" value="1" checked="checked">
                    </span>
                </li>

            <input type="hidden" name="grp" value="{{$grp}}">
            <input type="hidden" name="acc" value="{{$acc}}">
            <li><br></li>
                <li>
                <input type="submit" value="submit" name="submit" class="btn btn-success" style="text-align:right">
                </li>
            </ul>
            <input type="hidden" name="_token" value="{{csrf_token()}}">
            <input type="hidden" name="_method" value="PUT">
            {!!Form::close() !!}
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
