@extends('layouts.app')

@section('includes')
<script src="{{asset('js/groupSelectController.js')}}"></script>
@endsection

@section('style')
 
@endsection

@section('content')
<?php 
$account_types = DB::table('account_type')->get();
?>
<div class="row">
    <div class="col-md-12 justify-align-center" id="index_content1">   
        <div class="row justify-content-center">
            <div class="col-md-9 jumbotron bx2">
                @include('inc.messages')
                {!!Form::open(['action' => ['GroupController@update',$data['group']->groupID], 'method' => 'POST','class'=>'form1']) !!}
                        <fieldset>
                            <!-- title of the form -->
                            <div class="form-row">
                                <div class="col-md-12">
                                <table class="table table-responsive-sm table-responsive-md">
                                <tr>
                                    <td>
                            <h4 class="text-left"><span class="alert bg2">EDIT GROUP FORM</span></h4>
                                    </td>
         
                                    <td class="text-right">
                            <a class="btn btn-secondary btn-lg" href="/quick-view"><i class="fas fa-arrow-left"></i> Back</a>
                                    </td>
                                </tr>
                                </table>
                                </div>
                            </div>
                            <!-- title of the form -->
                            <hr class="my-4">
                            {{csrf_field()}}
                    <section> 
                    <!-- required fields note -->
                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <span><b>
                                Note : fields with <span class="text-danger">*</span> are required fields.</b>
                            </span>
                        </div>
                    </div>
                    <!-- required fields note -->
                    <div class="form-row" style="height:50px;">
                    <div class="form-group col-md-12">
                    <h6 class=""><span class="alert bg2">GROUP DETAILS</span></h6>
                    </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-10">
                            <label for="group_name">Group Name (Family names of group members)<span class="text-danger">*</span></label>
                            <input name="group_name" type="text" maxlength="100" class="form-control" id="group_name" placeholder="Group Name" required="yes" autocomplete="given-name" value="{{!is_null(old('group_name')) ? old('group_name') : $data['group']->groupName}}">
                        </div>     
                    </div>  
                    <div class="form-row">
                        <div class="form-group col-md-4 col-sm-12">
                            <label for="group_type">Group Type<span class="text-danger">*</span></label>
                                <select id="group_type" class="form-control" name="group_type" autocomplete="Group Type" required="yes">
                                <option value="Capstone">Capstone</option>
                                <option value="Thesis">Thesis</option>
                                </select>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6 col-sm-12" id="grp">
                            <label for="content_adviser">Group Content Adviser<span class="text-danger">*</span></label>
                            <?php $model = new App\models\Group; ?>
                            <select id="content_adviser" class="form-control" name="content_adviser" autocomplete="Content Adviser" required="yes">
                                @foreach($data['panel_members'] as $acc)
                                <option value="{{$acc->accID}}" title="{{$acc->accTitle}} {{$acc->accFName}} {{$acc->accMInitial}} {{$acc->accLName}}" 
                                    ><span>{{$acc->accLName}}, {{$model->initials($acc->accFName)}}</span></option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-6 col-sm-12" id="">
                            <label for="capstone_coordinator">Group Capstone Coordinator<span class="text-danger">*</span></label>
                            <?php $model = new App\models\Group; ?>
                            <select id="capstone_coordinator" class="form-control" name="capstone_coordinator" autocomplete="Content Adviser" required="yes">
                                @foreach($data['capstone_coordinator'] as $acc)
                                <option value="{{$acc->accID}}" title="{{$acc->accTitle}} {{$acc->accFName}} {{$acc->accMInitial}} {{$acc->accLName}}" 
                                    ><span>{{$acc->accLName}}, {{$model->initials($acc->accFName)}}</span></option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-10">
                            <label for="group_status">Group Status<span class="text-danger">*</span></label>
                            <select id="group_status" class="form-control" name="group_status" autocomplete="Group Status" required="yes">

                                <option value="Waiting for Submission">Waiting For Submission</option>

                                <option title="Document is submitted to the content adviser" value="Submitted to Content Adviser">Submitted to Content Adviser</option>

                                <option title="Document is corrected by the content adviser" value="Corrected by Content Adviser">Corrected by Content Adviser</option>

                                <option title="Waiting for the schedule to be requested" value="Waiting for Schedule Request">Waiting for Schedule Request</option>

                                <option title="Waiting for the schedule to be approved" value="Waiting for Schedule Approval">Waiting for Schedule Approval</option>
 
                                <option title="Waiting for the schedule to be finalized" value="Waiting for Final Schedule">Waiting for Final Schedule</option>

                                <option title="The Group is ready for defense" value="Ready for Defense">Ready for Defense</option>

                                <option value="Waiting for Project Approval">Waiting for Project Approval</option>

                                <option value="Corrected by Panel Members">Corrected by Panel Members</option>

                                <option title="The Group is ready for the next stage" value="Ready for Next Stage">Ready for Next Stage</option>

                                <option title="Project is waiting for the processing of requirements for completion" value="Waiting for Project Completion">Waiting for Project Completion</option>

                                <option title="The group has completely finished the project" value="Finished">Finished</option>

                            </select>
                        </div>     
                    </div>
                    </section>
                    <hr class="my-4">
                    <section>
                    <div class="form-row" style="height:50px;">
                        <div class="form-group col-md-12">
                            <h6 class=""><span class="alert bg2">GROUP PROJECT DETAILS</span></h6>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <label for="group_project_name">Group Project Name</label>
                            <input name="group_project_name" type="text" readonly aria-readonly="true" maxlength="150" class="form-control" id="group_project_name" placeholder="Group Project Name" required="yes" autocomplete="given-name" value="{{!is_null(old('group_project_name')) ? old('group_project_name') : $data['group']->projName}}">
                            </div>    
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6" id="grp">
                            <label for="stage_no">Stage No.<span class="text-danger">*</span></label>
                            <select id="stage_no" class="form-control" name="stage_no" autocomplete="Stage Number" required="yes">
                                @foreach($data['stage'] as $stage)
                                <option value="{{$stage->stageNo}}" @if(!is_null(old('stage_no'))) @if(old('stage_no')==$stage->stageNo) selected @endif @elseif(($data['group']->projStageNo)==$stage->stageNo) selected @endif><span>STAGE {{$stage->stageNo}} : {{$stage->stageName}}</span></option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-6" id="grp">
                            <label for="panel_verdict">Panel Verdict</label>
                            <select id="panel_verdict" class="form-control" name="panel_verdict" autocomplete="Panel Verdict" required="yes">
                                @foreach($data['panel_verdict'] as $verdict)
                                <option value="{{$verdict->panelVerdictNo}}" @if(!is_null(old('panel_verdict'))) @if(old('panel_verdict')==$verdict->panelVerdictNo) selected @endif @elseif(($data['group']->projPVerdictNo)== $verdict->panelVerdictNo) selected @endif><span>{{$verdict->pVerdictDescription}}</span></option>
                                @endforeach
                            </select>
                        </div> 
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <label for="document_link">Group Project Document Link</label>
                            <input name="document_link" type="url" maxlength="150" class="form-control" id="document_link" placeholder="Group Project Document Link" autocomplete="Group Project Document Link" value="{{!is_null(old('document_link')) ? old('document_link') : $data['group']->projDocumentLink}}">
                        </div>
                    </div>
                    <div class="form-row">
                            <div class="form-group col-md-12">
                                <label for="content_adviser_correction_link">Content Adviser Correction Link</label>
                                <input name="content_adviser_correction_link" type="url"  maxlength="150" class="form-control" id="content_adviser_correction_link" placeholder="Content Adviser Correction Link" autocomplete="Content Adviser Correction Link" value="{{!is_null(old('content_adviser_correction_link')) ? old('content_adviser_correction_link') : $data['group']->projCAdvCorrectionLink}}">
                            </div>
                        </div>
                    </section>

                    @if(isset($data) && count($data))
                    <hr class="my-4">
                    <section>
                    <div class="form-row" style="height:50px;margin-top:30px;">
                        <h6 class=""><span class="alert bg2">GROUP PANEL MEMBER DETAILS</span></h6>
                    </div>
                    <div class="form-row row justify-content-center">
                        <div class="col-md-6 my-1">
                            <div class="custom-control custom-checkbox mr-sm-2">
                                <input type="checkbox" class="custom-control-input" id="customControlAutosizing" name="EditGroupPanel">
                                <label class="custom-control-label" for="customControlAutosizing" data-toggle="popover" data-content="Editing the group panel members will remove the current panel members and create a new one.
                                Note : The first panel member on the selected list will be the chair panel member." data-placement="top">Edit Group Panel Members</label>
                            </div>
                        </div>
                    </div>
                    <div id="for_panel_group" class="responsive-content">
                    <div class="form-row row justify-content-center">
                        <div class="form-group col-md-6">
                            <select id="panel_group" class="form-control" name="panel_group[]" autocomplete="Panel Group" multiple="multiple">
                                @foreach($data['panel_members'] as $acc)
                                <option value="{{$acc->accID}}" title="{{$acc->accTitle}} {{$acc->accFName}} {{$acc->accMInitial}} {{$acc->accLName}}@foreach($data['pgroup'] as $pgroup)@if(($pgroup->accID == $acc->accID) && ($pgroup->panelIsChair)) -- Chair Panel Member
                                    @endif
                                    @endforeach" 
                                    @foreach($data['pgroup'] as $pgroup)
                                    @if($pgroup->accID == $acc->accID)
                                    selected
                                    @endif
                                    @endforeach
                                  >
                                    <span>{{$acc->accLName}}, {{$model->initials($acc->accFName)}}
                                        @foreach($data['pgroup'] as $pgroup)
                                        @if(($pgroup->accID == $acc->accID) && ($pgroup->panelIsChair))
                                        *
                                        @endif
                                        @endforeach
                                    </span>
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <span></span>
                        </div>
                        <div class="form-group col-md-12">     
                            <label for="minProjApp">Minimum Panel Members Required for Project Approval<span class="text-danger">*</span></label>
                            
                        </div>
                        <?php $selectMinPanel = count($data['pgroup']);?>
                        <div class="form-group col-md-12">
                        <input type="number" min="1" max="9" name="minimum_panel_members_for_project_approval" class="form-control" autocomplete="Minimum Panel For Project Approval" required="yes" style="width:100px;" value="{{!is_null(old('minimum_panel_members_for_project_approval')) ? old('minimum_panel_members_for_project_approval') : $data['group']->minProjPanel}}">         
                        </div>
                        
                        <div class="form-group col-md-6 my-1">          
                            <div class="custom-control custom-checkbox mr-sm-2">
                                <input type="checkbox" class="custom-control-input" id="label2" name="EditGroupPanelApp" @if(!is_null(old('EditGroupPanelApp'))) @if(old('EditGroupPanelApp')=='on')checked @endif @elseif($data['group']->requireChairProj=='1') checked @endif>
                                <label class="custom-control-label" for="label2" data-toggle="popover" data-content="The chair panel approval will be required to approve all project approval." data-placement="top">Require Chair Panel Approval</label>
                            </div>          
                        </div>    
                    </div>
                    </div>
                    </section>
                    @endif
                    <!-- options -->
                    <hr class="my-4">
                    <div class="form-row">
                        <div class="col-md-12 text-right">
                        <table class="table-responsive-md" style="float:right;">
                        <tr>
                            <td style="padding-right:3px;" class="back-button">
                                <a class="btn btn-secondary btn-lg" href="javascript:history.back()"><i class="fas fa-arrow-left"></i> Back</a>
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
                        <?php $pg = DB::table('panel_group')->where('panel_group.panelCGroupID','=',$data['group']->groupID)->pluck('panelAccID'); ?>
                        <input type="hidden" name="_method" value="PUT">
                        <input type="hidden" id="panel_select" name="panel_select" value="{{$pg}}">
                {!!Form::close() !!}
            </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('includes2')
<script type="text/javascript">
$('#group_status').select2({allowClear:true,selectOnClose:true,width:'resolve'});
$('#group_type').select2({allowClear:true,selectOnClose:true,width:'resolve'});
$('#content_adviser').select2({allowClear:true,selectOnClose:true,width:'resolve'});
$('#capstone_coordinator').select2({allowClear:true,selectOnClose:true,width:'resolve'});
$('#minProjApp').select2({allowClear:true,selectOnClose:true,width:'resolve'});
var vals = [];
var count = $('#minProjApp > option').length;
$('#panel_group').multiSelect({ 
    keepOrder: true, 
    selectableHeader: "<div class='custom-header text-center'><b>Select Options</b></div>",
    selectionHeader: "<div class='custom-header text-center'><b>Selected List</b></div>",
});
$(document).ready(function () {
    var $group_status = $('#group_status').select2();
    $group_status.val("{{!is_null(old('group_status')) ? old('group_status') : $data['group']->groupStatus}}").trigger("change");

    var $group_type = $('#group_type').select2();
    $group_type.val("{{!is_null(old('group_type')) ? old('group_type') : $data['group']->groupType}}").trigger("change");

    var $content_adviser = $('#content_adviser').select2();
    $content_adviser.val("{{!is_null(old('content_adviser')) ? old('content_adviser') : $data['group']->groupCAdviserID}}").trigger("change");

    var $capstone_coordinator = $('#capstone_coordinator').select2();
    $capstone_coordinator.val("{{!is_null(old('capstone_coordinator')) ? old('capstone_coordinator') : $data['group']->groupCoordID}}").trigger("change");

});

</script>

@endsection