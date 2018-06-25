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
                {!!Form::open(['action' => ['GroupController@update',$data['group'][0]->groupNo], 'method' => 'POST','id'=>'form1']) !!}
                        <fieldset>
                            <legend class="text-left"><span class="alert bg2">EDIT GROUP FORM</span><hr class="my-4"></legend>
                            
                            {{csrf_field()}}
                    <section> 
                    <div class="form-row" style="height:50px;">
                    <h6 class=""><span class="alert bg2">GROUP DETAILS</span></h6>
                    </div>
                    <div class="form-row">
                    
                        <div class="form-group col-md-10">
                            <label for="group_name">Group Name</label>
                            <input name="group_name" type="text" class="form-control" id="group_name" placeholder="Group Name" required="yes" autocomplete="given-name" value="{{$data['group'][0]->groupName}}">
                        </div>     
                    </div>  
                    <div class="form-row">
                        <div class="form-group col-md-3">
                            <label for="group_type">Group Type</label>
                                <select id="group_type" class="form-control" name="group_type" autocomplete="Group Type" required="yes">
                                <option value="Capstone" @if(($data['group'][0]->grpType)== "Capstone") selected @endif>Capstone</option>
                                <option value="Thesis" @if(($data['group'][0]->grpType)== "Thesis") selected @endif>Thesis</option>
                                </select>
                        </div>

                        <div class="form-group col-md-3" id="grp">
                            <label for="content_adviser">Group Content Adviser</label>
                            <?php $model = new App\models\Group; ?>
                            <select id="content_adviser" class="form-control" name="content_adviser" autocomplete="Content Adviser" required="yes">
                                @foreach($data['panel_members'] as $acc)
                                <option value="{{$acc->accNo}}" title="{{$acc->accTitle}} {{$acc->accFName}} {{$acc->accMInitial}} {{$acc->accLName}}" @if(($data['group'][0]->groupCAdviserNo)== $acc->accNo) selected @endif><span>{{$acc->accLName}}, {{$model->initials($acc->accFName)}}</span></option>
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
                            <input name="group_project_name" type="text" class="form-control" id="group_project_name" placeholder="Group Project Name" required="yes" autocomplete="given-name" value="{{$data['group'][0]->projName}}">
                            </div>    
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6" id="grp">
                            <label for="stage_no">Stage No.</label>
                            <select id="stage_no" class="form-control" name="stage_no" autocomplete="Stage Number" required="yes">
                                @foreach($data['stage'] as $stage)
                                <option value="{{$stage->stageNo}}" @if(($data['group'][0]->projStageNo)== $stage->stageNo) selected @endif><span>STAGE {{$stage->stageNo}} : {{$stage->stageName}}</span></option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-6" id="grp">
                            <label for="panel_verdict">Panel Verdict</label>
                            <select id="panel_verdict" class="form-control" name="panel_verdict" autocomplete="Panel Verdict" required="yes">
                                @foreach($data['panel_verdict'] as $verdict)
                                <option value="{{$verdict->panelVerdictNo}}" @if(($data['group'][0]->projPVerdictNo)== $verdict->panelVerdictNo) selected @endif><span>{{$verdict->pVerdictDescription}}</span></option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <label for="document_link">Group Project Document Link</label>
                            <input name="document_link" type="text" class="form-control" id="document_link" placeholder="Group Project Document Link" autocomplete="Group Project Document Link" value="{{$data['group'][0]->projDocumentLink}}">
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
                                <label class="custom-control-label" for="customControlAutosizing" data-toggle="popover" data-content="Editing the group panel members will remove the current panel members and create a new one." data-placement="top">Edit Group Panel Members</label>
                            </div>
                        </div>
                    </div>
                    <div id="for_panel_group">
                    <div class="form-row row justify-content-center">
                        <div class="form-group col-md-6">
                            <select id="panel_group" class="form-control" name="panel_group[]" autocomplete="Panel Group" multiple="multiple" required="yes">
                                @foreach($data['panel_members'] as $acc)
                                <option value="{{$acc->accNo}}" title="{{$acc->accTitle}} {{$acc->accFName}} {{$acc->accMInitial}} {{$acc->accLName}}" 
                                    @foreach($data['pgroup'] as $pgroup)
                                        @if($pgroup->accNo == $acc->accNo)
                                        selected 
                                        @endif
                                    @endforeach>
                                    <span>{{$acc->accLName}}, {{$model->initials($acc->accFName)}}
                                    </span>
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <span>Note : The first panel member on the selected list will be the chair panel member.</span>
                        </div>
                    </div>
                    </div>
                    </section>
                    @endif
                        
                    <div class="form-group text-right">
                        <hr class="my-4">
                        <button type="reset" class="btn btn-info btn-lg">
                        <span><i class="fas fa-recycle"></i> Reset Values</span>
                        </button>
                        <button id="sub1" type="submit" class="btn btn-success btn-lg">
                            <span><i class="far fa-edit"></i> Save Changes</span>
                        </button>
                    </div>
                        </fieldset>
                        <?php $pg = DB::table('panel_group')->where('panel_group.panelCGroupNo','=',$data['group'][0]->groupNo)->pluck('panelGroupNo'); ?>
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
$('#group_type').select2({allowClear:true,selectOnClose:true,width:'resolve'});
$('#content_adviser').select2({allowClear:true,selectOnClose:true,width:'resolve'});
var vals = [];
$('#panel_group').multiSelect({ 
    keepOrder: true, 
    selectableHeader: "<div class='custom-header alert-dark text-center'>Select Options</div>",
    selectionHeader: "<div class='custom-header alert-dark text-center'>Selected List</div>",
});
//$('#panel_group').select2({allowClear:true,selectOnClose:true,width:'resolve'});

$(document).ready(function () {
    $('#panel_group').change(function(e) {
    for(var i=0; i <$('#panel_group option').length; i++) {
      if ($($('#panel_group option')[i]).prop('selected') ) {
        if (!vals.includes(i)) {
          vals.push(i);
        }
      } else {
        if (vals.includes(i)) {
          vals.splice(vals.indexOf(i), 1);
        }
      }
    }
  });

    $("#sub1").click(function(){
        var order = [];
        vals.forEach(function(ele) {
        order.push( $($('#panel_group option')[ele]).val() );
        });
        $("#panel_select").val(order); 
        return confirm('Are you sure?');
    });
});


</script>
@endsection