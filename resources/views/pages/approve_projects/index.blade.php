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
        <h4><span class="alert bg2">APPROVE PROJECTS</span></h4>
        <br class="my-4">
            <div class="row">
                <div class="col-md-12">
            <form method="post" action="/app-proj-search-results" accept-charset="UTF-8" role="search">
                {{csrf_field()}} 
                <div class="input-group">
                    <input type="text" class="form-control" name="q" placeholder="Search Groups For Approval"> 
                    <span class="input-group-btn">
                        <button type="submit" class="btn btn-info btn-lg">
                            <span><i class="fas fa-search"></i> Search</span>
                        </button>
                    </span>
                    
                </div>
            </form>
            </div>
            <hr class="my-4">
            @if(isset($data) && count($data))
            <?php 
            $model = new App\models\Group;
            ?> 
                @foreach($data as $proj)
                <div class="form-row card bx2 card1 jumbotron">
                    <div class="col-md-12"> 
                        <table class="table">
                            <thead>
                                <tr class="">
                                    <th>Approval Details</th>
                                    <th>Group Details</th>
                                    <th>Project Details</th>
                                    <th>Options</th>
                                </tr>
                            </thead>
                        <?php
                        $pgroup = DB::table('panel_group')
                        ->join('account', 'account.accNo', '=', 'panel_group.panelAccNo')
                        ->join('group', 'panel_group.panelCGroupNo', '=', 'group.groupNo')
                        ->join('project_approval', 'project_approval.projAppPGroupNo', '=', 'panel_group.panelGroupNo')
                        ->select('account.*','project_approval.*','panel_group.*')
                        ->where('panel_group.panelCGroupNo','=',$proj->groupNo)
                        ->get();
                        ?>
                        <tbody>
                            <tr>
                                <td>
                        <table>
                            <tr><td>
                        <span data-html="true" 
                        class="btn btn-info btn-sm"
                        data-toggle="popover" 
                        data-trigger="focus" 
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
                                        <small><b>Group Name : {{$proj->groupName}}</b></small>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <small><b>Group Type : {{$proj->grpType}}</b></small>
                                    <td>
                                </tr>
                                <tr>
                                    <td>
                                        <small><b>Group Status : {{$proj->groupStatus}}</b></small>
                                    <td>
                                </tr>
                               
                                </table>
                            </td> <!-- End Column 2 -->
                            <td>
                                <table class="table-sm table-hover table-striped">
                                <tr>
                                    
                                    <td><small><b>Project Title: </b></small><a href="projects/{{$proj->groupNo}}" class="btn btn-link btn-sm" title="{{$proj->projName}}" data-toggle="popover" data-content="View project document" data-placement="top"><span><i class="fas fa-project-diagram"></i></span> {{(substr($proj->projName, 0, 10) . '..')}}</a>
                                    </td>
                                    
                                </tr>
                                <tr>
                                    <td>
                                        <small><b>Stage No : {{$proj->projStageNo}}</b></small>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <small><b>Project Verdict : {{$proj->pVerdictDescription}}</b></small>
                                    </td>
                                </tr>
                             
                                </table>
                            </td> <!-- End Column 3 -->
                            
                            <td>
                                <table class="table-sm">
                                <tr><td>     

                                {!!Form::open(['action' => 'ProjAppController@projApprovalStatus', 'method' => 'POST']) !!}
                                {{csrf_field()}}
                                <button  type="submit" class="btn btn-success btn-sm" name="submit" value="1" data-toggle="popover" data-content="Approve schedule" data-placement="top" onclick="return confirm('Are You Sure?')"><span><i class="fas fa-check"></i> Approve</span></button>
                                <input type="hidden" name="opt" value="1">
                                <input type="hidden" name="grp" value="{{$proj->groupNo}}">
                                <input type="hidden" name="acc" value="{{$proj->accNo}}">
                                <input type="hidden" name="_method" value="PUT">
                                {!!Form::close() !!}

                                </td></tr>
                                <tr><td>
                                
                                {!!Form::open(['action' => 'ProjAppController@projApprovalStatus', 'method' => 'POST']) !!}
                                {{csrf_field()}}
                                <button  type="submit" class="btn btn-danger btn-sm" name="submit" value="1" data-toggle="popover" data-content="Dispprove schedule" data-placement="top" onclick="return confirm('Are You Sure?')"><span><i class="fas fa-times"></i> Disapprove</span></button>
                                <input type="hidden" name="opt" value="0">
                                <input type="hidden" name="grp" value="{{$proj->groupNo}}">
                                <input type="hidden" name="acc" value="{{$proj->accNo}}">
                                <input type="hidden" name="_method" value="PUT">
                                {!!Form::close() !!}
                                
                                </td></tr>
                                </table>
                            </td> <!-- End Column 4 -->
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
$(document).ready(function () {

});
</script>
@endsection