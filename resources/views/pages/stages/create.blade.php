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
                <form method="post" action="{{action('StageController@store')}}" accept-charset="UTF-8" role="create" class="form1">
                        <fieldset>
                            <!-- title of the form -->
                            <div class="form-row">
                                <div class="col-md-12">
                                <table class="table table-responsive-sm table-responsive-md">
                                <tr>
                                    <td>
                            <h4 class="text-left"><span class="alert bg2">CREATE STAGE FORM</span></h4>
                                    </td>
         
                                    <td class="text-right">
                            <a class="btn btn-secondary btn-lg" href="/stage-settings"><i class="fas fa-arrow-left"></i> Back</a>
                                    </td>
                                </tr>
                                </table>
                                </div>
                            </div>
                            <!-- title of the form -->
                            <hr class="my-4">
                    
                            {{csrf_field()}} 
                    <!-- required fields note -->
                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <span><b>
                                Note : fields with <span class="text-danger">*</span> are required fields.</b>
                            </span>
                        </div>
                    </div>
                    <!-- required fields note -->
                    <div class="form-row">
                        <div class="form-group col-md-2">
                                <label for="stage_number">Stage No.<span class="text-danger">*</span></label>
                        <input name="stage_number" type="number" min="1" class="form-control" id="stage_number" placeholder="Stage Number" maxlength="3" required="yes" autocomplete="stage-number" value="{{!is_null(old('stage_number')) ? old('stage_number') : $data['next']}}">
                        </div>
                        
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <label for="stage_name">Stage Name<span class="text-danger">*</span></label>
                            <input name="stage_name" type="text" class="form-control" id="stage_name" placeholder="Stage Name" required="yes" autocomplete="stage-name" maxlength="50" value="{{old('stage_name')}}">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-4">
                        <label for="stage_defense_duration" data-toggle="popover" data-content="Specifies how long a presentation is being executed in minutes." data-placement="top">Stage Defense Duration<span class="text-danger">*</span></label>
                        <input type="number" min="0" name="stage_defense_duration" class="form-control" id="stage_defense_duration" placeholder="number of minutes" required="yes" style="max-width:200px;"autocomplete="stage-defense-duration" value="{{old('stage_defense_duration')}}">
                        </div>

                        <div class="form-group col-md-5">
                            <label for="stage_panel" data-toggle="popover" data-content="Specifies the type panel members required for a presentation." data-placement="top">Stage Panel Members Required<span class="text-danger">*</span></label>
                                <select id="stage_panel" class="form-control" name="stage_panel" autocomplete="stage-panel" required="yes">
                                    <option value="All">All</option>
                                    <option value="Custom">Custom</option>
                                </select>
                        </div>
                    </div>


                    <!-- Minimum Panel Member Information -->
                    <hr>
                    <div class="form-row justify-content-start">
                        <div class="form-group col-md-6">     
                            <label for="minSchedApp">Minimum Panel Members Required for Schedule Approval<span class="text-danger">*</span></label>
                          <input type="number" min="1" max="20" name="minimum_panel_members_for_schedule_approval" class="form-control" autocomplete="Minimum Panel For Schedule Approval" required="yes" style="width:100px;" value="{{!is_null(old('minimum_panel_members_for_schedule_approval')) ? old('minimum_panel_members_for_schedule_approval') : '3'}}"> 
                          
                              
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6 my-1">          
                            <div class="custom-control custom-checkbox mr-sm-2">
                                <input type="checkbox" class="custom-control-input" id="label1" name="EditGroupPanel" @if(!is_null(old('EditGroupPanel'))&&old('EditGroupPanel')=='on') checked @endif>
                                <label class="custom-control-label" for="label1" data-toggle="popover" data-content="The chair panel approval will be required to approve all schedule approval." data-placement="top">Require Chair Panel Approval</label>
                            </div>          
                        </div>
                        
                    </div>
                    <!-- Minimum Panel Member Information -->

                    <div class="form-row"><div class="form-group col-md-12"></div></div>
                    <div class="form-row">
                        <div class="form-group col-md-12">
                        <label for="stage_link" data-toggle="popover" data-content="The URL link for instructions of this stage." data-placement="top">Stage Instructions Link</label>
                        <input name="stage_link" type="url" class="form-control" id="stage_link" placeholder="Stage Instructions Link" autocomplete="stage-link" maxlength="150" value="{{old('stage_link')}}">
                        </div>
                    </div>

                        <!-- options -->
                        <hr class="my-4">
                        <div class="form-row">
                            <div class="col-md-12 text-right">
                            <table class="table-responsive-md" style="float:right;">
                            <tr>
                                <td style="padding-right:3px;" class="back-button">
                                    <a class="btn btn-secondary btn-lg" href="/stage-settings"><i class="fas fa-arrow-left"></i> Back</a>
                                </td>
                                <td style="padding-right:3px;">    
                                    <button type="reset" class="btn btn-info btn-lg">
                                    <span><i class="fas fa-recycle"></i> Reset Values</span>
                                    </button>
                                </td>
                                <td>
                                    <button type="button" id="sub1" class="btn btn-success btn-lg" data-toggle="modal" data-target="#confirm1">
                                        <span><i class="fas fa-plus"></i> Create Stage</span>
                                    </button>
                                    <button id="sub2" type="submit" style="display:none;"></button>
                                </td>
                            </tr>
                            </table>
                            </div>
                        </div>
                        <!-- options -->
                    </fieldset>
                </form>
            </div>
        </div>
    
    </div>
</div>
@endsection
@section('includes2')
<script type="text/javascript">

$(document).ready(function () {
    var $stgpanel = $("#stage_panel").select2();
    $stgpanel.val("{{!is_null(old('stage_panel')) ? old('stage_panel') : 'All'}}").trigger("change");
  
});
$('#stage_panel').select2({allowClear:true,selectOnClose:true});
$('#minSchedApp').select2({allowClear:true,selectOnClose:true});
</script>
@endsection