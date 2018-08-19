@extends('layouts.app')

@section('includes')

@endsection

@section('style')
 
@endsection

@section('content')
<?php $grpModel = new App\models\Group; $userModel = new App\User; $user1=$userModel->current();?>
<div class="row">
    <div class="col-md-12 justify-align-center" id="index_content1">   
        <div class="row justify-content-center">
            <div class="col-md-9 jumbotron bx2">
                @include('inc.messages')
                {!!Form::open(['action' => ['QuickViewController@update',$data->groupID], 'method' => 'POST','class'=>'form1']) !!}
                        <fieldset>
                            <h4 class="text-left"><span class="alert bg2">MODIFY SCHEDULE FORM</span><hr class="my-4"></h4>
                            
                            {{csrf_field()}} 
                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <label for=""><b>For group of : {{$data->groupName}}</b></label>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-3">
                            <label for="date">Date</label>
                            <input name="date" type="date" class="form-control" id="date" placeholder="" required="yes" autocomplete="date"
                            value="{{!is_null(old('date')) ? old('date') : $data->schedDate}}">
                        </div>  
                        <div class="form-group col-md-3">
                                <label for="starting_time">Starting Time</label>
                                <input name="starting_time" type="time" class="form-control" id="starting_time" required="yes" autocomplete="Starting Time" value="{{!is_null(old('starting_time')) ? old('starting_time')   : date_format(new Datetime($data->schedTimeStart),"H:i")}}">
                        </div>
                        <div class="form-group col-md-3">
                                <label for="ending_time">Ending Time</label>
                                <input name="ending_time" type="time" class="form-control" id="ending_time"  required="yes" autocomplete="Ending Time" value="{{!is_null(old('ending_time')) ? old('ending_time') : date_format(new Datetime($data->schedTimeEnd),"H:i")}}">
                        </div>        
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-10">
                            <label for="place">Place</label>
                            <input name="place" type="text" maxlength="100" class="form-control" id="place" placeholder="Place" required="yes" autocomplete="Place" value="{{!is_null(old('place')) ? old('place') : $data->schedPlace}}">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-3">
                            <label for="schedule_type">Schedule Type</label>
                                <select id="schedule_type" class="form-control" name="schedule_type" autocomplete="Schedule Type">
                                <option value="Oral Defense" @if(!is_null(old('schedule_type')) && old('schedule_type') == 'Oral Defense') selected @elseif(($data->schedType) == 'Oral Defense') selected @endif>Oral Defense</option>
                                <option value="Round Table" @if(!is_null(old('schedule_type')) && old('schedule_type') == 'Round Table') selected @elseif(($data->schedType) == 'Round Table') selected @endif>Round Table</option>
                            </select>
                        </div>  

                        <div class="form-group col-md-3">
                            <label for="schedule_status">Schedule Status</label>
                            <select id="schedule_status" class="form-control" name="schedule_status" autocomplete="Schedule Status">
                                <option value="Ready" @if(!is_null(old('schedule_status')) && old('schedule_status') == 'Ready') selected @elseif(($data->schedStatus) == 'Ready') selected @endif>Ready</option>
                                
                                <option value="Not Ready" @if(!is_null(old('schedule_status')) && old('schedule_status') == 'Not Ready') selected @elseif(($data->schedStatus) == 'Not Ready') selected @endif>Not Ready</option>
                                
                                <option value="Finished" @if(!is_null(old('schedule_status')) && old('schedule_status') == 'Finished') selected @elseif(($data->schedStatus) == 'Finished') selected @endif>Finished</option>
                            </select>
                        </div>  
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <label for="sched_approval">Schedule Status</label>
                            <table class="table table-striped table-sm" id="sched_approval">
                                <thead>
                                    <tr>
                                    <th>Panel Name</th>
                                    <th>Approval Status</th>
                                    <th>Short Message</th>
                                    </tr>
                                </thead>
                                <tbody>
                        <?php 
                            $stage = new App\models\Stage;

                            $data1 = DB::table('panel_group')
                            ->join('account','account.accID','=','panel_group.panelAccID')
                            ->join('schedule_approval','schedule_approval.schedPanelGroupID','=','panel_group.panelGroupID')
                            ->where('account.isActivePanel','=','1')
                            ->select('account.*','panel_group.*','schedule_approval.*')
                            ->where('panel_group.panelGroupType','=',$stage->current($data->groupID))
                            ->where('panel_group.panelCGroupID','=',$data->groupID)
                            ->get();
                        ?>
                                    @foreach($data1 as $pmember)
                                    <tr>
                                        <td>
                                        <span title='{{$pmember->accTitle}} {{$pmember->accFName}} {{$pmember->accMInitial}} {{$pmember->accLName}}'>
                                        {{$pmember->accLName}}, {{$grpModel->initials($pmember->accFName)}}@if($pmember->panelIsChair)
                                        (Chair panel member) @endif
                                        </span>
                                        </td> 
                                        <td>
                                        <select id="sched_app" class="form-control sched_app" name="sched_app_{{$pmember->accID}}" autocomplete="Schedule Approval">
                                                <option value="3" @if(!is_null(old('sched_app_{{$pmember->accID}}')) && old('sched_app_{{$pmember->accID}}') == '3') selected @elseif(($pmember->isApproved) == '3') selected @endif>Disabled</option>

                                                <option value="0" @if(!is_null(old('sched_app_{{$pmember->accID}}')) && old('sched_app_{{$pmember->accID}}') == '0') selected @elseif(($pmember->isApproved) == '0') selected @endif>Waiting</option>

                                                <option value="1" @if(!is_null(old('sched_app_{{$pmember->accID}}')) && old('sched_app_{{$pmember->accID}}') == '1') selected @elseif(($pmember->isApproved) == '1') selected @endif>Approved</option>

                                                <option value="2" @if(!is_null(old('sched_app_{{$pmember->accID}}')) && old('sched_app_{{$pmember->accID}}') == '2') selected @elseif(($pmember->isApproved) == '2') selected @endif>Disapproved</option>
                                        </select>
                                        </td>
                                        <td>
                                            <textarea id="sched_approval_comment" name="sched_comment_{{$pmember->accID}}" class="form-control" style="height:38px;" maxlength="70">@if(!is_null(old('sched_comment_{{$pmember->accID}}'))){{old('sched_comment_'. $pmember->accID)}}@else{{$pmember->schedAppMsg}}@endif</textarea>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- options -->
                    <hr class="my-4">
                    <div class="form-row">
                        <div class="col-md-12 text-right">
                        <table class="table-responsive-md" style="float:right;">
                        <tr>
                            <td style="padding-right:3px;" class="back-button">
                                <a class="btn btn-secondary btn-lg" href="/quick-view"><i class="fas fa-arrow-left"></i> Back</a>
                            </td>
                            <td style="padding-right:3px;">    
                                <button type="reset" class="btn btn-info btn-lg">
                                <span><i class="fas fa-recycle"></i> Reset Values</span>
                                </button>
                            </td>
                            <td>
                                <button type="button" id="sub1" class="btn btn-success btn-lg" data-toggle="modal" data-target="#confirm1">
                                    <span><i class="far fa-edit"></i> Save Changes</span>
                                </button>
                                <button id="sub2" type="submit" style="display:none;"></button>
                            </td>
                        </tr>
                        </table>
                        </div>
                    </div>
                    <!-- options -->
                        </fieldset>
                        <input type="hidden" name="_method" value="PUT">
                {!!Form::close() !!}
            </div>
        </div>
    </div>
</div>
@endsection
@section('includes2')
<script type="text/javascript">
$(document).ready(function () {

});

//$('#group_type').select2({allowClear:true,selectOnClose:true});

</script>
@endsection