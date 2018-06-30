@extends('layouts.app')

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
        <div class="jumbotron bg1">
        @include('inc.messages')
        <h4><span class="alert bg2">ADVISED GROUPS</span></h4>
        <br class="my-4">
            <div class="row">
                <div class="col-md-12">
                    <form method="post" action="/advised-groups-search-results" accept-charset="UTF-8" role="search">
                        {{csrf_field()}} 
                        <div class="input-group">
                            <input type="text" class="form-control" name="q" placeholder="Search Groups"> 
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
                <div class="form-row card bx2 card1 jumbotron">
                    <div class="col-md-12"> 
                        <table class="table">
                            <thead>
                                <tr class="">
                                    <th>Group Details</th>
                                    @if(in_array($sched->projPVerdictNo,['1','4','5','6']))
                                    <th>Schedule Details</th>
                                    <th>Schedule Status</th>
                                    @else
                                    <th>Project Details</th>
                                    @endif
                                    
                                    <th>Options</th>
                                </tr>
                            </thead>
                        <?php
                        $pgroup = DB::table('panel_group')
                        ->join('account', 'account.accNo', '=', 'panel_group.panelAccNo')
                        ->join('group', 'panel_group.panelCGroupNo', '=', 'group.groupNo')
                        ->join('schedule_approval', 'schedule_approval.schedPGroupNo', '=', 'panel_group.panelGroupNo')
                        ->select('account.*','schedule_approval.*','panel_group.*')
                        ->where('panel_group.panelCGroupNo','=',$sched->groupNo)
                        ->get();
                        ?>
                        <tbody>
                            <tr>
                           
                            <td>
                                <table class="table-sm table-hover table-striped">
                                <tr>
                                    <td>
                                        <small><b>Group Name : {{$sched->groupName}}</b></small>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <small><b>Group Type : {{$sched->grpType}}</b></small>
                                    <td>
                                </tr>
                                <tr>
                                    <td>
                                        <small><b>Group Status : {{$sched->groupStatus}}</b></small>
                                    <td>
                                </tr>
                                <tr>
                                    <td>
                                        <small><b>For: 
                                        @if(in_array($sched->projPVerdictNo,['1','4','5','6']))
                                            Requesting of Schedule
                                        @else
                                            Approval of Revisions
                                        @endif
                                        </b></small>
                                    <td>
                                </tr>
                                @if(in_array($sched->projPVerdictNo,['1','4','5','6']))
                                <tr>
                                    <td><small><b>Project Document : </b></small><a href="{{$sched->projDocumentLink}}" target="_blank" class="btn btn-link btn-sm" title="{{$sched->projName}}" data-toggle="popover" data-content="Download project document" data-placement="top"><span><i class="fas fa-download"></i> {{(substr($sched->projName, 0, 10) . '..')}}</span></a>
                                    </td>
                                </tr>
                                @endif
                                </table>
                            </td>
                            @if(in_array($sched->projPVerdictNo,['1','4','5','6']))
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
                            </td>
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
                            </td>
                            @else
                            <td>
                                <table class="table-sm table-hover table-striped">
                                    <tr>
                                        <td>
                                            <small title="{{$sched->projName}}" data-toggle="popover" data-content="View project document" data-placement="top"> <b>Project Title : {{(substr($sched->projName, 0, 20) . '..')}}</b></small>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <small><b>Project Document : <a href="{{$sched->projDocumentLink}}" target="_blank" class="btn btn-link btn-sm" data-toggle="popover" data-content="Download project document" data-placement="top"><span><i class="fas fa-download"></i> {{(substr($sched->projName, 0, 10) . '..')}}</span></a></b></small>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <small><b>Panel Verdict : {{$sched->pVerdictDescription}}</b></small>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                            @endif
                            <td>
                                <?php 
                                 $acc = DB::table('panel_group')
                                ->join('account', 'account.accNo', '=', 'panel_group.panelAccNo')
                                ->join('group', 'panel_group.panelCGroupNo', '=', 'group.groupNo')
                                ->join('schedule_approval', 'schedule_approval.schedPGroupNo', '=', 'panel_group.panelGroupNo')
                                ->select('account.*','schedule_approval.*','panel_group.*')
                                ->where('panel_group.panelCGroupNo','=',$sched->groupNo)
                                ->where('account.accNo','=',Auth::id())
                                ->get();
                                ?>
                                <table class="table-sm">
                                <tr><td>     

                                {!!Form::open(['action' => 'AdvisedGroupsController@contentAdvAppForSched', 'method' => 'POST']) !!}
                                <button  type="submit" class="btn btn-success btn-sm" name="submit" value="1" data-toggle="popover" data-content="Approve schedule" data-placement="top" onclick="return confirm('Are You Sure?')"><span><i class="fas fa-check"></i> Approve</span></button>
                                <input type="hidden" name="groupNo" value="{{$sched->groupNo}}">
                                <input type="hidden" name="_method" value="PUT">
                                {!!Form::close() !!}

                                </td></tr>
                                <tr><td>
                                <a href="/advised-groups/{{$sched->groupNo}}/edit" class="btn btn-danger btn-sm" name="submit" value="1" data-toggle="popover" data-content="Make corrections" data-placement="top"><span><i class="fas fa-times"></i> Make Corrections</span>
                                </a>
                                </td></tr>
                                </table>
                            </td>
                            </tr>
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
$(document).ready(function () {

});
</script>
@endsection