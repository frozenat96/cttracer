<div class="form-row card bx2 card1 jumbotron">
        <div class="col-md-12"> 
            <table class="table table-responsive-sm table-responsive-md">
                <thead>
                    <tr class="">
                        <th>Approval Details</th>
                        <th>Group Details</th>
                        <th>Schedule Details</th>
                        <th>Schedule Status</th>
                        <th>Options</th>
                    </tr>
                </thead>
            <?php
            $stage = new App\models\Stage;
    
            $pgroup = DB::table('panel_group')
            ->join('account', 'account.accID', '=', 'panel_group.panelAccID')
            ->join('group', 'panel_group.panelCGroupID', '=', 'group.groupID')
            ->join('schedule_approval', 'schedule_approval.schedPanelGroupID', '=', 'panel_group.panelGroupID')
            ->select('account.*','schedule_approval.*','panel_group.*')
            ->where('account.isActivePanel','=','1')
            ->where('panel_group.panelCGroupID','=',$data1->groupID)
            ->where('panel_group.panelGroupType','=',$stage->current($data1->groupID))
            ->get();
            
            ?>
            <tbody>
                <tr>
                    <td>
            <table>
                <tr><td>
            <span data-html="true" 
            class="btn btn-info btn-sm" tabindex="0"
            role="button" data-toggle="popover" data-trigger="focus" 
            title="<center><b>Panel Member Approval</b></center>" 
            data-content="<div style='max-width:430px;'>
            <table class='table-sm table-hover table-striped'>
                <thead>
                    <tr>
                        <th>Panel Member</th>
                        <th>Status</th>
                        <th>Short Message</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($pgroup as $pmember)
                <tr>
                    <td>
                        <span title='{{$pmember->accTitle}} {{$pmember->accFName}} {{$pmember->accMInitial}} {{$pmember->accLName}}'>
                        {{$pmember->accLName}}, {{$grpModel->initials($pmember->accFName)}}@if($pmember->panelIsChair)
                        (Chair panel member) @endif
                        </span>
                    </td>
                    <td>
                        @if($pmember->isApproved == 1)
                            <span class='badge badge-pill badge-success'>  Approved 
                            </span>
                        @elseif($pmember->isApproved == 2)
                            <span class='badge badge-pill badge-danger'>Disapproved
                            </span>
                        @else
                            <span class='badge badge-pill badge-warning'>Waiting
                            </span>
                         @endif 
                    </td>
                    <td>
                         <small>
                            {{$pmember->schedAppMsg}}
                        </small>
                    </td>
                </tr>
                @endforeach
                </tbody>
            </table></div>
            "><i class="far fa-question-circle"></i> Check Status</span>
            </td></tr>
            </table>
                </td> <!-- End column 1 -->
                
                <td>
                    <table class="table-sm table-hover table-striped">
                    <tr>
                        <td>
                            <small><b>Group Name : {{$data1->groupName}}</b></small>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <small><b>Group Type : {{$data1->groupType}}</b></small>
                        <td>
                    </tr>
                    <tr>
                        <td>
                            <small><b>Group Status : {{$data1->groupStatus}}</b></small>
                        <td>
                    </tr>
                    <tr>
                        <td><small><b>Project Document : </b></small><a href="{{$data1->projDocumentLink}}" class="btn btn-link btn-sm" title="{{$data1->projName}}" data-toggle="popover" data-content="Download project document" data-placement="top"><span><i class="fas fa-download"></i>  {{(substr($data1->projName, 0, 10) . '..')}}</span></a>
                        </td>
                    </tr>
                    </table>
                </td> <!-- End column 2 -->
                <td>
                    <table class="table-sm table-hover table-striped">
                    <tr>
                        <td>
                            <small><b>Date : {{date_format(new Datetime($data1->schedDate),"F j, Y")}}</b></small>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <small><b>Starting Time : {{date_format(new Datetime($data1->schedTimeStart),"g:i A")}}</b></small>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <small><b>Ending Time : {{date_format(new Datetime($data1->schedTimeEnd),"g:i A")}}</b></small>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <small><b>Place : {{$data1->schedPlace}}</b></small>
                        </td>
                    </tr>
                    </table>
                </td> <!-- End column 3 -->
                <td>
                    <table class="table-sm table-hover table-striped">
                        <tr>
                            <td>
                                <small><b>Status : {{$data1->schedStatus}}</b></small>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <small><b>For : {{$data1->schedType}}</b></small>
                            </td>
                        </tr>
                    </table>
                </td> <!-- End column 4 -->
                <td>
                    <table class="table-sm">
                    <tr><td>     

                    {!!Form::open(['action' => 'SchedAppController@schedApprovalStatus', 'method' => 'POST','class'=>'form1']) !!}
                    {{csrf_field()}}
                    <button  type="submit" class="btn btn-success btn-sm" name="opt" value="1" data-toggle="popover" data-content="Approve schedule" data-placement="top" onclick="return confirm('Are You Sure?')"><span><i class="fas fa-check"></i> Approve</span></button>
                    </td></tr>
                    <tr><td>
                    <button  type="submit" class="btn btn-danger btn-sm" name="opt" value="0" data-toggle="popover" data-content="Dispprove schedule" data-placement="top" onclick="return confirm('Are You Sure?')"><span><i class="fas fa-times"></i> Disapprove</span></button>
                    <input type="hidden" name="grp" value="{{$data1->groupID}}">
                    <input type="hidden" name="acc" value="{{$data1->accID}}">
                    <input type="hidden" name="_method" value="PUT">
                    </td></tr> 
                    <tr rowspan="2"><td> 
                        <label for="shortmsg" style="font-size:12px;">Short Message</label>
                        <textarea class="form-control" name="shortmsg" id="shortmsg" style="width:120px;border-radius:5px;color:black;" maxlength="150" placeholder="Short message.." autocomplete="Short Comment"></textarea>
                    </td></tr>
                    {!!Form::close() !!}
                    
                    
                    </table>
                </td> <!-- End column 5 -->
                </tr> <!-- End Row 1 -->
            </tbody>
            </table>
        </div>  
    </div>