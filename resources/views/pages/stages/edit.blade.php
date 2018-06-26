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
                {!!Form::open(['action' => ['StageController@update',$data['stage']->stageNo], 'method' => 'POST']) !!}
                        <fieldset>
                                <legend class="text-left"><span class="alert bg2">EDIT STAGE FORM</span><hr class="my-4"></legend>
                    
                            {{csrf_field()}} 
                    <div class="form-row">
                        <div class="form-group col-md-2">
                                <label for="stage_number">Stage No.</label>
                        <input name="stage_number" type="number" min="1" class="form-control" id="stage_number" placeholder="Stage Number" maxlength="3" required="yes" autocomplete="stage-number" value="{{$data['stage']->stageNo}}">
                        </div>
                        
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <label for="stage_name">Stage Name</label>
                            <input name="stage_name" type="text" class="form-control" id="stage_name" placeholder="Stage Name" required="yes" autocomplete="stage-name" value="{{$data['stage']->stageName}}">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-4">
                        <label for="stage_defense_duration">Stage Defense Duration</label>
                        <input type="number" min="0" name="stage_defense_duration" class="form-control" id="stage_defense_duration" placeholder="number of minutes" required="yes" style="max-width:200px;"autocomplete="stage-defense-duration" value="{{$data['stage']->stageDefDuration}}">
                        </div>

                        <div class="form-group col-md-5">
                            <label for="stage_panel">Stage Panel</label>
                                <select id="stage_panel" class="form-control" name="stage_panel" autocomplete="stage-panel" required="yes">
                                    <option value="All">All</option>
                                    <option value="Custom">Custom</option>
                                </select>
                        </div>

                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-12">
                        <label for="stage_link">Stage Instructions Link</label>
                        <input name="stage_link" type="text" class="form-control" id="stage_link" placeholder="Stage Instructions Link" autocomplete="stage-link" value="{{$data['stage']->stageRefLink}}">
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
$(document).ready(function () {
    var $stage_panel = $("#group").select2();
    $stage_panel.val("{{$data['stage']->stagePanel}}").trigger("change");
});

</script>
@endsection