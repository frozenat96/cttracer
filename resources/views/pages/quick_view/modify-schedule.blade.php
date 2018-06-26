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
                {!!Form::open(['action' => ['QuickViewController@update',$data[0]->groupNo], 'method' => 'POST','id'=>'form_submit1']) !!}
                        <fieldset>
                            <legend class="text-left"><span class="alert bg2">MODIFY SCHEDULE FORM</span><hr class="my-4"></legend>
                            
                            {{csrf_field()}} 
                    <div class="form-row">
                        <div class="form-group col-md-3">
                            <label for="date">Date</label>
                            <input name="date" type="date" class="form-control" id="date" placeholder="" required="yes" autocomplete="date"
                            value="{{$data[0]->schedDate}}">
                        </div>  
                        <div class="form-group col-md-3">
                                <label for="starting_time">Starting Time</label>
                                <input name="starting_time" type="time" class="form-control" id="starting_time" placeholder="" required="yes" autocomplete="Starting Time" value="{{date_format(new Datetime($data[0]->schedTimeStart),"H:i")}}">
                        </div>
                        <div class="form-group col-md-3">
                                <label for="ending_time">Ending Time</label>
                                <input name="ending_time" type="time" class="form-control" id="ending_time" placeholder="" required="yes" autocomplete="Ending Time" value="{{date_format(new Datetime($data[0]->schedTimeEnd),"H:i")}}">
                        </div>        
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-10">
                            <label for="place">Place</label>
                            <input name="place" type="text" class="form-control" id="place" placeholder="Place" required="yes" autocomplete="Place" value="{{$data[0]->schedPlace}}">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-3">
                            <label for="schedule_type">Schedule Type</label>
                                <select id="schedule_type" class="form-control" name="schedule_type" autocomplete="Schedule Type">
                                <option value="Oral Defense" @if(($data[0]->schedType)=='Oral Defense') selected @endif>Oral Defense</option>
                                <option value="Round Table" @if(($data[0]->schedType)=='Round Table') selected @endif>Round Table</option>
                            </select>
                        </div>  

                        <div class="form-group col-md-3">
                            <label for="schedule_status">Schedule Status</label>
                            <select id="schedule_status" class="form-control" name="schedule_status" autocomplete="Schedule Status">
                                <option value="Ready" @if(($data[0]->schedStatus)=='Ready') selected @endif>Ready</option>
                                <option value="Not Ready" @if(($data[0]->schedStatus)=='Not Ready') selected @endif>Not Ready</option>
                                <option value="Finished" @if(($data[0]->schedStatus)=='Finished') selected @endif>Finished</option>
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
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($data as $pmember)
                                    <tr>
                                        <td>
                                        <span title='{{$pmember->accTitle}} {{$pmember->accFName}} {{$pmember->accMInitial}} {{$pmember->accLName}}'>
                                        {{$pmember->accLName}}, {{$grpModel->initials($pmember->accFName)}}@if($pmember->panelIsChair)
                                        (Chair panel member) @endif
                                        </span>
                                        </td>
                                        <td>
                                        <select id="sched_app" class="form-control sched_app" name="sched_app_{{$pmember->accNo}}" autocomplete="Schedule Approval">
                                                <option value="0" @if(($pmember->isApproved)=='0') selected @endif>Waiting</option>
                                                <option value="1" @if(($pmember->isApproved)=='1') selected @endif>Approved</option>
                                                <option value="2" @if(($pmember->isApproved)=='2') selected @endif>Disapproved</option>
                                        </select>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                            <div class="form-group text-right">
                                <hr class="my-4">
                                <button type="reset" class="btn btn-info btn-lg">
                                <span><i class="fas fa-recycle"></i> Reset Values</span>
                                </button>
                                <button type="button" id="sub1" class="btn btn-success btn-lg" data-toggle="modal" data-target="#confirm1">
                                    <span><i class="far fa-edit"></i> Save Changes</span>
                                </button>
                                <button id="sub2" type="submit" style="display:none;"></button>
                            </div>
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