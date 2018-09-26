@extends('layouts.app-no-popover')

@section('style')
    .list-group-item {
        background-color: rgba(0,0,0,0);
        border: none;
    }
    .card1 {
        border: none;
    }
@endsection

@section('content')
<?php $grpModel = new App\models\Group; $userModel = new App\User; $user1=$userModel->current();?>
<div class="row">
    <div class="col-md-12 justify-align-center" id="index_content1">
        <div class="jumbotron bg1" style="padding:20px;padding-top:30px;">
        @include('inc.messages')
        <h4><span class="alert bg2">FINAL SCHEDULE LIST</span></h4>
        <br class="my-4">
            <div class="row">
                <div class="col-md-12">
            <form id="form-search" method="post" action="/final-schedule-list-results" accept-charset="UTF-8" role="search">
                {{csrf_field()}} 
                <div class="input-group">
                    <input type="text" class="form-control search-bar1" name="q" list="list1" placeholder="Search Groups"> 

                    @if(isset($data) && count($data))
                    <datalist id="list1" class="datalist scrollable">
                        @foreach($data as $data1)
                            <option value="{{$data1->groupName}}">
                        @endforeach
                        @foreach($data as $data2)
                            <option value="{{$data2->projName}}">
                        @endforeach
                    </datalist>
                    @endif

                    <span class="input-group-btn">
                        <button type="submit" class="btn btn-info btn-lg">
                            <span><i class="fas fa-search"></i> Search</span>
                        </button>
                    </span>
                    
                </div>
            </form>
                </div>
       
            </div>
            <hr class="my-4">
            @if(isset($data) && count($data))
            <?php 
            $model = new App\models\Group; 
            ?> 
                @foreach($data as $sched)
                <div class="form-row card bx2 card1 jumbotron" style="padding:0;">
                    <div class="col-md-12"> 
                        <table class="table table-responsive-sm">
                            <thead>
                                <tr class="">
                                    <th>Approval Details</th>
                                    <th>Group Details</th>
                                    <th>Schedule Details</th>
                                    <th>Schedule Status</th>
                                </tr>
                            </thead>
                        <?php
                        $stage = new App\models\Stage;
                        $pgroup = DB::table('panel_group')
                        ->join('account', 'account.accID', '=', 'panel_group.panelAccID')
                        ->join('group', 'panel_group.panelCGroupID', '=', 'group.groupID')
                        ->join('schedule_approval', 'schedule_approval.schedPanelGroupID', '=', 'panel_group.panelGroupID')
                        ->select('account.*','schedule_approval.*','panel_group.*')
                        ->where('panel_group.panelCGroupID','=',$sched->groupID)
                        ->where('panel_group.panelGroupType','=',$stage->current($sched->groupID))
                        ->get();
                        ?>
                        <tbody>
                            <tr>
                                <td>
                        <table>
                            <tr><td>
                        <span data-html="true" 
                        class="btn btn-info btn-sm"
                        tabindex="0" role="button" data-toggle="popover" data-trigger="focus"
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
                        <!-- 
                        </td></tr>
                        <tr><td>
                            @if(in_array($user1[0]->accType,['1']))
                            <a href="/quick-view/{{$sched->groupID}}/edit" class="btn btn-secondary btn-sm" data-toggle="popover" data-content="Modify schedule" data-placement="top"><span><i class="far fa-calendar-plus"></i></span> Modify Schedule</a>
                            @endif
                        </td></tr>
                        -->
                        </table>
                            </td> <!-- End column 1 -->
                            
                            <td>
                                <table class="table-sm table-hover table-striped">
                                <tr>
                                    <td>
                                        <small><b>Group Name : {{$sched->groupName}}</b></small>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <small><b>Group Type : {{$sched->groupType}}</b></small>
                                    <td>
                                </tr>
                                <tr>
                                    <td>
                                        <small><b>Group Status : {{$sched->groupStatus}}</b></small>
                                    <td>
                                </tr>
                                <tr>
                                    <td><small><b>Project Document : </b></small><a href="{{$sched->projDocumentLink}}" target="_blank" class="btn btn-link btn-sm" title="{{$sched->projName}}" data-toggle="popover" data-content="View project document" data-placement="top"><span><i class="fas fa-download"></i>  {{(substr($sched->projName, 0, 10) . '..')}}</span></a>
                                    </td>
                                </tr>
                                </table>
                            </td> <!-- End column 2 -->
                            <td>
                                <table class="table-sm table-hover table-striped">
                                <tr>
                                    <td>
                                        <small><b>Date : {{date_format(new Datetime($sched->schedDate),"F j, Y")}}</b></small>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <small><b>Starting Time : {{date_format(new Datetime($sched->schedTimeStart),"g:i A")}}</b></small>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <small><b>Ending Time : {{date_format(new Datetime($sched->schedTimeEnd),"g:i A")}}</b></small>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <small><b>Place : {{$sched->schedPlace}}</b></small>
                                    </td>
                                </tr>
                                </table>
                            </td> <!-- End column 3 -->
                            <td>
                                <table class="table-sm table-hover table-striped">
                                    <tr>
                                        <td>
                                            <small><b>Status : {{$sched->schedStatus}}</b></small>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <small><b>For : {{$sched->schedType}}</b></small>
                                        </td>
                                    </tr>
                                </table>
                            </td> <!-- End column 4 -->
                            </tr> <!-- End Row 1 -->
                        </tbody>
                        </table>
                    </div>  
                </div>
                @endforeach
            {!! $data->render() !!}
            @else
            <table class="table"><tr><td><span>No results found</span></td></tr></table>
            @endif
        </div>
    </div>
</div>
@endsection

@section('includes2')
<script type="text/javascript">
$(function () {
  $('[data-toggle="popover"]').popover()
})
$(document).ready(function () {
    $('.popover-dismiss').popover({
    trigger: 'focus'
    })
});
</script>
@endsection