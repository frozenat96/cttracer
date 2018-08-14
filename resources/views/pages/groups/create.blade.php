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
                {!!Form::open(['action' => ['GroupController@store'], 'method' => 'POST','class'=>'form1']) !!}
                        <fieldset>
                            <legend class="text-left"><span class="alert bg2">CREATE GROUP FORM</span><hr class="my-4"></legend>
                            
                            {{csrf_field()}}
                    <section> 
                    <div class="form-row" style="height:50px;">
                    <h6 class=""><span class="alert bg2">GROUP DETAILS</span></h6>
                    </div>
                    <div class="form-row">
                    
                        <div class="form-group col-md-10">
                            <label for="group_name">Group Name (Family names of group members)<span class="text-danger">*</span></label>
                            <input name="group_name" type="text" maxlength="100" class="form-control" id="group_name" placeholder="Group Name" required="yes" autocomplete="Group Name" value="{{ old('group_name') }}">
                        </div>     
                    </div>  
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="group_type">Group Type<span class="text-danger">*</span></label>
                                <select id="group_type" class="form-control" name="group_type" autocomplete="Group Type" required="yes">
                                <option value="Capstone" @if(old('group_type')=='Capstone') selected @endif>Capstone</option>
                                <option value="Thesis" @if(old('group_type')=='Thesis') selected @endif>Thesis</option>
                                </select>
                        </div>

                        <div class="form-group col-md-4" id="grp">
                            <label for="content_adviser">Group Content Adviser<span class="text-danger">*</span></label>
                            <?php $model = new App\models\Group; ?>
                            <select id="content_adviser" class="form-control" name="content_adviser" autocomplete="Content Adviser" required="yes">
                                @foreach($data['panel_members'] as $acc)
                                <option value="{{$acc->accID}}" title="{{$acc->accTitle}} {{$acc->accFName}} {{$acc->accMInitial}} {{$acc->accLName}}"  @if(old('content_adviser')==$acc->accID) selected @endif><span>{{$acc->accLName}}, {{$model->initials($acc->accFName)}}</span></option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    </section>
                    <hr class="my-4">
                    <section>
                    <div class="form-row" style="height:50px;margin-top:30px;">
                        <h6 class=""><span class="alert bg2">GROUP PROJECT DETAILS</span></h6>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <label for="group_project_name">Group Project Name<span class="text-danger">*</span></label>
                            <input name="group_project_name" type="text" maxlength="150" class="form-control" id="group_project_name" placeholder="Group Project Name" required="yes" autocomplete="given-name" value="{{ old('group_project_name') }}">
                            </div>    
                    </div>
               
                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <label for="document_link">Group Project Document Link</label>
                            <input name="document_link" type="url" maxlength="150" class="form-control" id="document_link" placeholder="Group Project Document Link" autocomplete="Group Project Document Link" value="{{ old('document_link') }}">
                        </div>
                    </div>
                    </section>
                    <section>
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <span></span>
                            </div>
                            <div class="form-group col-md-12">     
                                <label for="minProjApp">Minimum Panel Members Required for Project Approval<span class="text-danger">*</span></label>
                                
                            </div>
                            <?php 
                            $selectMinPanel = DB::table('account')
                            ->where('account.accType','=','2')
                            ->pluck('account.accID');
                            ?>
                            <div class="form-group col-md-12">
                                <select name="minimum_panel_members_for_schedule_approval" class="form-control" id="minProjApp" autocomplete="Minimum Panel For Schedule Approval" required="yes" style="width:100px;"> 
            
                                    @foreach($selectMinPanel as $key => $value)
                                    <option value="{{$key+1}}"  id="mps_{{$key+1}}" @if(!is_null(old('minimum_panel_members_for_schedule_approval')) && old('minimum_panel_members_for_schedule_approval')==$key+1) selected @endif>
                                        {{$key+1}}
                                    </option>
                                    @endforeach
                                </select>   
                            </div>
                            
                            <div class="form-group col-md-6 my-1">          
                                <div class="custom-control custom-checkbox mr-sm-2">
                                    <input type="checkbox" class="custom-control-input" id="label2" name="EditGroupPanelApp" checked>
                                    <label class="custom-control-label" for="label2" data-toggle="popover" data-content="The chair panel approval will be required to approve all project approval." data-placement="top" @if(!is_null(old('EditGroupPanelApp'))) checked @endif>Require Chair Panel Approval</label> 
                                </div>          
                            </div>    
                        </div>
                    </section>
                    <!-- required fields note -->
                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <span><b>
                                Note : fields with <span class="text-danger">*</span> are required fields.</b>
                            </span>
                        </div>
                    </div>
                    <!-- required fields note -->
                    <div class="form-group text-right">
                        <hr class="my-4">
                        <button type="reset" class="btn btn-info btn-lg">
                        <span><i class="fas fa-recycle"></i> Reset Values</span>
                        </button>
                        <button id="sub1" type="button" class="btn btn-success btn-lg" data-toggle="modal" data-target="#confirm1">
                            <span><i class="fas fa-plus"></i> Create Group</span>
                        </button>
                        <button id="sub2" type="submit" style="display:none;"></button>
                    </div>
                        </fieldset>
                {!!Form::close() !!}
            </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('includes2')
<script type="text/javascript">
$('#group_type').select2({allowClear:true,selectOnClose:true,width:'resolve'});
$('#content_adviser').select2({allowClear:true,selectOnClose:true,width:'resolve'});
$(document).ready(function(){
    var $minProjApp = $("#minProjApp").select2();
    $minProjApp.val("{{!is_null(old('minimum_panel_members_for_schedule_approval')) ? old('minimum_panel_members_for_schedule_approval') : ceil(count($data['panel_members']) * 7 / 10)  }}").trigger("change");
});
</script>
@endsection