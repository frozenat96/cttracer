@extends('layouts.app')

@section('includes')

@endsection

@section('style')
 
@endsection

@section('content')

<div class="row">
    <div class="col-md-12 justify-align-center" id="index_content1">
        
        <div class="row justify-content-center">
            <div class="col-md-9 jumbotron bx2">
                @include('inc.messages')
                {!!Form::open(['action' => ['RevHistoryController@update',$data['rev']->revID], 'method' => 'POST','class'=>'form1']) !!}
                        <fieldset>
                            <legend class="text-left"><span class="alert bg2">EDIT REVISION FORM</span><hr class="my-4"></legend>
                    
                            {{csrf_field()}} 
                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <span>For the Group of : {{$data['rev']->groupName}}</span>
                        </div>
                    </div>
                    <div class="form-row">
                            <div class="form-group col-md-4">
                              <label for="stage_no">Stage No.</label>
                              <select id="stage_no" class="form-control" name="stage_no" autocomplete="Content Adviser" required="yes">
                                    @foreach($data['stage'] as $stg)
                                    <option value="{{$stg->stageNo}}"><span>{{$stg->stageNo}} - {{$stg->stageName}}</span></option>
                                    @endforeach
                            </select>
                            </div>
                            
                            
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <span>Revision No. : {{$data['rev']->revNo}}</span>
                        </div>
                    </div>
                          <div class="form-group">
                            <label for="comment">Comment</label>
                            <textarea id="proj_approval_comment" name="comment" class="form-control" maxlength="1600">{{!is_null(old('comment')) ? old('comment') : $data['rev']->revComment}}</textarea>
                          </div>
                          <div class="form-row">
                            <div class="form-group col-md-6" id="for_panel1">
                                <?php $model = new App\models\Group; ?>
                                <label for="panel_member">Reviewed By : {{$data['rev']->accTitle}} {{$data['rev']->accFName}} {{$data['rev']->accMInitial}} {{$data['rev']->accLName}}</label>
                            </div>
                          </div>
                          <div class="form-row">
                            <div class="form-group col-md-3" id="">
                                <label for="status">Status</label>
                                <select id="status" class="form-control" name="status" autocomplete="status" style="width: 100%" onchange="linkAllow(this);">
                                <option value="1">Approved</option>
                                <option value="2">Corrected</option>
                                </select>
                            </div>
                          </div>
                            
                            <div class="form-row" id="revLink">
                                <div class="form-group col-md-12">
                                    <label for="revision_link">Revision Link</label>
                                    <input name="revision_link" type="text" maxlength="150" class="form-control" id="revision_link" placeholder="Revision Link" required="yes" autocomplete="Revision Link" value="{{!is_null(old('revision_link')) ? old('revision_link') : $data['rev']->revLink}}">
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
                              <button id="sub2" type="submit" class="btn btn-success btn-lg" style="display:none;">
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
$('#group').select2({allowClear:true,selectOnClose:true,width: 'resolve'});
$('#status').select2({allowClear:true,selectOnClose:true,width: 'resolve'});
$('#panel_member').select2({allowClear:true,selectOnClose:true,width:'resolve'});
$('#stage_no').select2({allowClear:true,selectOnClose:true,width:'resolve'});

function linkAllow(v) {
	if(v.value==1) {
      $('#revLink').hide();
    } else if(v.value==2) {
      $('#revLink').show();
    }
  }

$(document).ready(function () {
    var $group = $("#group").select2();
    var $status = $("#status").select2();
    var $panel_member = $('#panel_member').select2();
    var $stage_no = $('#stage_no').select2();

    $group.val("{{!is_null(old('group')) ? old('group') : $data['rev']->accGroupID}}").trigger("change");
    $status.val("{{!is_null(old('status')) ? old('status') : $data['rev']->revStatus}}").trigger("change");
    $stage_no.val("{{!is_null(old('stage_no')) ? old('stage_no') : $data['rev']->revStageNo}}").trigger("change");

    t = "{{!is_null(old('status')) ? old('status') : $data['rev']->revStatus}}";
    linkAllow(t);
});

</script>
@endsection