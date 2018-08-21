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
                {!!Form::open(['action' => ['RevHistoryController@update',$data->revID], 'method' => 'POST','class'=>'form1']) !!}
                        <fieldset>
                            <!-- title of the form -->
                            <div class="form-row">
                                    <div class="col-md-12">
                                    <table class="table table-responsive-sm table-responsive-md">
                                    <tr>
                                        <td>
                                <h4 class="text-left"><span class="alert bg2">EDIT REVISION HISTORY FORM</span></h4>
                                        </td>
             
                                        <td class="text-right">
                                <a class="btn btn-secondary btn-lg" href="/revision-history-search-results/{{$data->revGroupName}}"><i class="fas fa-arrow-left"></i> Back</a>
                                        </td>
                                    </tr>
                                    </table>
                                    </div>
                                </div>
                                <!-- title of the form -->
                                <hr class="my-4">
                    
                            {{csrf_field()}} 
                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <span>For the Group of : {{$data->revGroupName}}</span>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="stage_no">Stage No. {{$data->revStageNo}}</label>         
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <span>Revision No. : {{$data->revNo}}</span>
                        </div>
                    </div>
                          <div class="form-group">
                            <label for="comment">Comment</label>
                            <textarea id="proj_approval_comment" name="comment" class="form-control" maxlength="1600">{{!is_null(old('comment')) ? old('comment') : $data->revComment}}</textarea>
                          </div>
                          <div class="form-row">
                            <div class="form-group col-md-6" id="for_panel1">
                                <?php $model = new App\models\Group; ?>
                                <label for="panel_member">Reviewed By : {{$data->accTitle}} {{$data->accFName}} {{$data->accMInitial}} {{$data->accLName}}</label>
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
                                    <input name="revision_link" type="text" maxlength="150" class="form-control" id="revision_link" placeholder="Revision Link" required="yes" autocomplete="Revision Link" value="{{!is_null(old('revision_link')) ? old('revision_link') : $data->revLink}}">
                                </div>
                            </div>
                           
                          <!-- options -->
                    <hr class="my-4">
                    <div class="form-row">
                        <div class="col-md-12 text-right">
                        <table class="table-responsive-md" style="float:right;">
                        <tr>
                            <td style="padding-right:3px;" class="back-button">
                                <a class="btn btn-secondary btn-lg" href="/revision-history-search-results/{{$data->revGroupName}}"><i class="fas fa-arrow-left"></i> Back</a>
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
$('#stage_no').select2({allowClear:false,selectOnClose:false,width:'resolve'});

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

    $group.val("{{!is_null(old('group')) ? old('group') : $data->accGroupID}}").trigger("change");
    $status.val("{{!is_null(old('status')) ? old('status') : $data->revStatus}}").trigger("change");
    $stage_no.val("{{!is_null(old('stage_no')) ? old('stage_no') : $data->revStageNo}}").trigger("change");

    t = "{{!is_null(old('status')) ? old('status') : $data->revStatus}}";
    linkAllow(t);
});

</script>
@endsection