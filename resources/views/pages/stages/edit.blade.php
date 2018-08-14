@extends('layouts.app')

@section('includes')

@endsection

@section('style')
 
@endsection

@section('content')
<div class="row">
    <div class="col-md-12 justify-align-center" id="index_content1">
        
        <div class="row justify-content-center">
            <div class="col-md-9 bx2 jumbotron">
                @include('inc.messages')
                {!!Form::open(['action' => ['StageController@update',$data['stage']->stageNo], 'method' => 'POST','class'=>'form1']) !!}
                        <fieldset>
                                <legend class="text-left"><span class="alert bg2">EDIT STAGE FORM</span><hr class="my-4"></legend>
                    
                            {{csrf_field()}} 
                    <div class="form-row">
                        <div class="form-group col-md-2">
                                <label for="stage_number">Stage No.</label>
                        <input name="stage_number" type="number" min="1" class="form-control" id="stage_number" placeholder="Stage Number" maxlength="3" required="yes" autocomplete="stage-number" value="{{!is_null(old('stage_number')) ? old('stage_number') : $data['stage']->stageNo}}">
                        </div>
                        
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <label for="stage_name">Stage Name</label>
                            <input name="stage_name" type="text" class="form-control" id="stage_name" placeholder="Stage Name" required="yes" autocomplete="stage-name" maxlength="50" value="{{!is_null(old('stage_name')) ? old('stage_name') : $data['stage']->stageName}}">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-4">
                        <label for="stage_defense_duration" data-toggle="popover" data-content="Specifies how long a presentation is being executed in minutes." data-placement="top">Stage Defense Duration</label>
                        <input type="number" min="0" name="stage_defense_duration" class="form-control" id="stage_defense_duration" placeholder="number of minutes" required="yes" style="max-width:200px;"autocomplete="stage-defense-duration" value="{{!is_null(old('stage_defense_duration')) ? old('stage_defense_duration') : $data['stage']->stageDefDuration}}">
                        </div>

                        <div class="form-group col-md-5">
                            <label for="stage_panel" data-toggle="popover" data-content="Specifies the type panel members required for a presentation." data-placement="top">Stage Panel Members Required</label>
                            <select id="stage_panel" class="form-control" name="stage_panel" autocomplete="stage-panel" required="yes">
                                
                                <option value="All" @if(!is_null(old('stage_panel')) && old('stage_panel')=="All") selected @elseif($data['stage']->stagePanel == "All") selected  @endif>All</option>

                                <option value="Custom" @if(!is_null(old('stage_panel')) && old('stage_panel')=="Custom") selected @elseif($data['stage']->stagePanel == "Custom") selected  @endif>Custom</option>

                            </select>
                        </div>

                    </div>
                    <hr>
                    <div class="form-row justify-content-start">
                        <div class="form-group col-md-6">     
                            <label for="minSchedApp">Minimum Panel Members Required for Schedule Approval</label>
                            <select name="minimum_panel_members_for_schedule_approval" class="form-control" id="minSchedApp" autocomplete="Minimum Panel For Schedule Approval" style="width:100px;">     
                                @foreach($data['pgroup'] as $key => $value)
                                <option value="{{$key+1}}"  id="mps_{{$key+1}}" @if(!is_null(old('minimum_panel_members_for_schedule_approval')) && (($key+1) == old('minimum_panel_members_for_schedule_approval') )) selected @elseif($data['stage']->minSchedPanel==($key+1)) selected @endif>
                                    {{$key+1}}
                                </option>
                                @endforeach   
                            </select>    
                        </div>
                    </div> 
                    <div class="form-row">
                        <div class="form-group col-md-6 my-1">          
                            <div class="custom-control custom-checkbox mr-sm-2">
                                <input type="checkbox" class="custom-control-input" id="label1" name="EditGroupPanel" @if(!is_null(old('EditGroupPanel'))) @if(old('EditGroupPanel')=='on')checked @endif @elseif($data['stage']->requireChairSched=='1') checked @endif> 
                                <label class="custom-control-label" for="label1" data-toggle="popover" data-content="The chair panel approval will be required to approve all schedule approval." data-placement="top">Require Chair Panel Approval</label>
                            </div>          
                        </div>
                        
                    </div>
                    <div class="form-row"><div class="form-group col-md-12"></div></div>
                    <div class="form-row">
                        <div class="form-group col-md-12">
                        <label for="stage_link" data-toggle="popover" data-content="The URL link for instructions of this stage." data-placement="top" >Stage Instructions Link</label>
                        <input name="stage_link" type="url" class="form-control" id="stage_link" placeholder="Stage Instructions Link" autocomplete="stage-link" maxlength="150" value="{{!is_null(old('stage_link')) ? old('stage_link') : $data['stage']->stageRefLink}}">
                        </div>
                    </div>

                        <div class="form-group text-right">
                            <hr class="my-4">
                            <button type="reset" class="btn btn-info btn-lg">
                            <span><i class="fas fa-recycle"></i> Reset Values</span>
                            </button>
                            <button type="button" class="btn btn-success btn-lg" data-toggle="modal" data-target="#confirm1">
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
$('#stage_panel').select2({allowClear:true,selectOnClose:true});
$('#minSchedApp').select2({allowClear:true,selectOnClose:true});

$(document).ready(function () {
    var $stage_panel = $("#group").select2();
    $stage_panel.val("{{$data['stage']->stagePanel}}").trigger("change");
});

</script>
@endsection