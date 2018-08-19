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
                {!!Form::open(['action' => ['ScheduleController@coordRequestForSched'], 'method' => 'POST','class'=>'form1']) !!}
                        <fieldset>
                            <h4 class="text-left"><span class="alert bg2">CREATE SCHEDULE FORM</span><hr class="my-4"></h4>
                            
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
                            value="{{!is_null(old('date')) ? old('date') : date('Y-m-d')}}">
                        </div>  
                        <div class="form-group col-md-3">
                                <label for="starting_time">Starting Time</label>
                                <input name="starting_time" type="time" class="form-control" id="starting_time" required="yes" autocomplete="Starting Time" value="{{old('starting_time')}}" min="07-04-2018">
                        </div>
                             
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-10">
                            <label for="place">Place</label>
                            <input name="place" type="text" maxlength="100" class="form-control" id="place" placeholder="Place" required="yes" autocomplete="Place" value="{{old('place')}}">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-3">
                            <label for="schedule_type">Schedule Type</label>
                                <select id="schedule_type" class="form-control" name="schedule_type" autocomplete="Schedule Type">
                                <option value="Oral Defense" @if(old('schedule_type')=='Oral Defense') selected @endif>Oral Defense</option>
                                <option value="Round Table" @if(old('schedule_type')=='Round Table') selected @endif>Round Table</option>
                            </select>
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
                                    <span><i class="fas fa-plus"></i> Create Schedule</span>
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
                        <input type="hidden" name="grp" value="{{$data->groupID}}">
                {!!Form::close() !!}
            </div>
        </div>
    </div>
</div>
@endsection
@section('includes2')
<script type="text/javascript">
$(document).ready(function () {

$('#date').change(function(){
    var now = new Date();
    var day = ("0" + now.getDate()).slice(-2);
    var month = ("0" + (now.getMonth() + 1)).slice(-2);
    var today = now.getFullYear()+"-"+(month)+"-"+(day) ;

    if($('#date').val() < today) {
        $('#date').val(today);
    }
});

});

//$('#group_type').select2({allowClear:true,selectOnClose:true});

</script>
@endsection