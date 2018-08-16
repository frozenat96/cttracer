<div class="form-row card bx2 card1 jumbotron">
        <div class="col-md-12"> 
            <table class="table table-responsive-sm">
                <thead>
                    <tr class="">
                        <th>Approval Details</th>
                        <th>Group Details</th>
                        <th>Project Details</th>
                        <th>Options</th>
                    </tr>
                </thead>
            <?php
            $stage = new App\models\Stage;
            $pgroup = DB::table('panel_group')
            ->join('account', 'account.accID', '=', 'panel_group.panelAccID')
            ->join('group', 'panel_group.panelCGroupID', '=', 'group.groupID')
            ->join('project_approval', 'project_approval.projAppPanelGroupID', '=', 'panel_group.panelGroupID')
            ->select('account.*','project_approval.*','panel_group.*')
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
            role="button"
            data-toggle="popover" data-trigger="focus"
            title="<center><b>Panel Approval</b></center>" 
            data-content="
            <table class='table-sm table-hover table-striped'>
                <thead>
                    <tr>
                        <th>Panel Member</th>
                        <th>Status</th>
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
                            <span class='badge badge-pill badge-danger'>Has Corrections
                            </span>
                        @else
                            <span class='badge badge-pill badge-warning'>Waiting
                            </span>
                         @endif 
                    </td>
                </tr>
                @endforeach
                </tbody>
            </table>
            "><i class="far fa-question-circle"></i> Check Status</span>
            </td></tr>

            </table>
                </td> <!-- End Column 1 -->
                
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
                   
                    </table>
                </td> <!-- End Column 2 -->
                <td>
                    <table class="table-sm table-hover table-striped">
                    <tr>
                        
                        <td><small><b>Project Title: </b></small><a href="projects/{{$data1->groupID}}" class="btn btn-link btn-sm" title="{{$data1->projName}}" data-toggle="popover" data-content="View project document" data-placement="top"><span><i class="fas fa-project-diagram"></i></span> {{(substr($data1->projName, 0, 10) . '..')}}</a>
                        </td>
                        
                    </tr>
                    <tr>
                        <td>
                            <small><b>Stage No : {{$data1->projStageNo}} ({{$data1->stageName}})</b></small>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <small><b>Project Verdict : {{$data1->pVerdictDescription}}</b></small>
                        </td>
                    </tr>
                 
                    </table>
                </td> <!-- End Column 3 -->
                
                <td>
                    <table class="table-sm">
                    <tr><td>     

                    {!!Form::open(['action' => 'ProjAppController@projApprovalStatus', 'method' => 'POST','class'=>'form1']) !!}
                    {{csrf_field()}}
                    <button  type="submit" class="btn btn-success btn-sm" name="submit" value="1" data-toggle="popover" data-content="Approve revision" data-placement="top" onclick="return confirm('Are You Sure?')"><span><i class="fas fa-check"></i> Approve</span></button>
                    <input type="hidden" name="opt" value="1">
                    <input type="hidden" name="grp" value="{{$data1->groupID}}">
                    <input type="hidden" name="acc" value="{{$data1->accID}}">
                    <input type="hidden" name="_method" value="PUT">
                    {!!Form::close() !!}

                    </td></tr>
                    <tr><td>
               
                    <a class="btn btn-primary btn-sm" href="/approve-projects/{{$data1->groupID}}/edit" data-toggle="popover" data-content="Make corrections or approve the document submitted" data-placement="top">
                        <span><i class="far fa-question-circle"></i> Correct/Approve Document</span>
                    </a>
                    </td></tr>
                    </table>
                </td> <!-- End Column 4 -->
                </tr> <!-- End Row 1 -->
            </tbody>
            </table>
        </div>  
    </div>