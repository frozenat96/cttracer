@extends('layouts.app')

@section('includes')

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
                {!!Form::open(['action' => ['GroupController@store'], 'method' => 'POST','id'=>'form1']) !!}
                        <fieldset>
                            <legend class="text-left"><span class="alert bg2">CREATE GROUP FORM</span><hr class="my-4"></legend>
                            
                            {{csrf_field()}}
                    <section> 
                    <div class="form-row" style="height:50px;">
                    <h6 class=""><span class="alert bg2">GROUP DETAILS</span></h6>
                    </div>
                    <div class="form-row">
                    
                        <div class="form-group col-md-10">
                            <label for="group_name">Group Name</label>
                            <input name="group_name" type="text" class="form-control" id="group_name" placeholder="Group Name" required="yes" autocomplete="Group Name">
                        </div>     
                    </div>  
                    <div class="form-row">
                        <div class="form-group col-md-3">
                            <label for="group_type">Group Type</label>
                                <select id="group_type" class="form-control" name="group_type" autocomplete="Group Type" required="yes">
                                <option value="Capstone">Capstone</option>
                                <option value="Thesis">Thesis</option>
                                </select>
                        </div>

                        <div class="form-group col-md-3" id="grp">
                            <label for="content_adviser">Group Content Adviser</label>
                            <?php $model = new App\models\Group; ?>
                            <select id="content_adviser" class="form-control" name="content_adviser" autocomplete="Content Adviser" required="yes">
                                @foreach($data['panel_members'] as $acc)
                                <option value="{{$acc->accNo}}" title="{{$acc->accTitle}} {{$acc->accFName}} {{$acc->accMInitial}} {{$acc->accLName}}"><span>{{$acc->accLName}}, {{$model->initials($acc->accFName)}}</span></option>
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
                            <label for="group_project_name">Group Project Name</label>
                            <input name="group_project_name" type="text" class="form-control" id="group_project_name" placeholder="Group Project Name" required="yes" autocomplete="given-name">
                            </div>    
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6" id="grp">
                            <label for="stage_no">Stage No.</label>
                            <select id="stage_no" class="form-control" name="stage_no" autocomplete="Stage Number" required="yes">
                                @foreach($data['stage'] as $stage)
                                <option value="{{$stage->stageNo}}"><span>STAGE {{$stage->stageNo}} : {{$stage->stageName}}</span></option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-6" id="grp">
                            <label for="panel_verdict">Panel Verdict</label>
                            <select id="panel_verdict" class="form-control" name="panel_verdict" autocomplete="Panel Verdict" required="yes">
                                @foreach($data['panel_verdict'] as $verdict)
                                <option value="{{$verdict->panelVerdictNo}}"><span>{{$verdict->pVerdictDescription}}</span></option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <label for="document_link">Group Project Document Link</label>
                            <input name="document_link" type="text" class="form-control" id="document_link" placeholder="Group Project Document Link" autocomplete="Group Project Document Link" value="">
                        </div>
                    </div>
                    </section>
                                
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
  
});
</script>
@endsection