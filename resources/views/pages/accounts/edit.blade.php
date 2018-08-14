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
                {!!Form::open(['action' => ['AccountController@update',$data['account']->accID], 'method' => 'POST','class'=>'form1']) !!}
                        <fieldset>
                            <legend class="text-left"><span class="alert bg2">EDIT ACCOUNT FORM</span><hr class="my-4"></legend>
                    
                            {{csrf_field()}} 
                    <div class="form-row">
                            <div class="form-group col-md-5">
                              <label for="given_name">Given Name<span class="text-danger">*</span></label>
                              <input name="given_name" type="text" maxlength="50" class="form-control" id="given_name" placeholder="Given Name" required="yes" autocomplete="given-name" value="{{!is_null(old('given_name')) ? old('given_name') : $data['account']->accFName}}">
                            </div>
                            <div class="form-group col-md-2">
                                    <label for="middle_initial">Middle Initial</label>
                                    <input name="middle_initial" type="text" class="form-control" id="middle_initial" placeholder="Middle Initial" maxlength="1" autocomplete="middle-initial" value="{{!is_null(old('middle_initial')) ? old('middle_initial') : $data['account']->accMInitial}}">
                                  </div>
                            <div class="form-group col-md-5">
                              <label for="last_name">LastName<span class="text-danger">*</span></label>
                              <input name="last_name" type="text" maxlength="50" class="form-control" id="last_name" placeholder="Last Name" required="yes" autocomplete="family-name" value="{{!is_null(old('last_name')) ? old('last_name') : $data['account']->accLName}}">
                            </div>
                    </div>
                          <div class="form-group">
                            <label for="email">Email Address<span class="text-danger">*</span></label>
                            <input type="email" maxlength="70" class="form-control" id="email" name="email" placeholder="ccs@su.edu.ph" required="yes" autocomplete="email" value="{{!is_null(old('email')) ? old('email') : $data['account']->accEmail}}">
                          </div>
                          <div class="form-row">
                            
                            <div class="form-group col-md-4">
                              <label for="role">Role<span class="text-danger">*</span></label>
                              <select id="role" class="form-control" name="role" autocomplete="role" onchange="groupAllow(this);" required="yes" style="width: 100%">
                                @foreach($data['acc_type'] as $acc)
                                  <option value="{{$acc->accTypeNo}}">{{$acc->accTypeDescription}}</option>
                                @endforeach
                              </select>
                            </div> 
                            
                            <div class="form-group col-md-3" id="for_panel1">
                                <label for="title">Title</label>
                                  <select id="title" class="form-control" name="title" autocomplete="title" style="width: 100%">
                                    <option value="">None</option>
                                    <option value="Mr.">Mr.</option>
                                    <option value="Ms.">Ms.</option>
                                    <option value="Mrs.">Mrs.</option>
                                    <option value="Asst. Prof.">Asst. Prof.</option>
                                    <option value="Prof.">Prof.</option>
                                    <option value="Engr.">Engr.</option>
                                    <option value="Dr.">Dr.</option>
                                  </select>
                            </div>
                            <div class="form-group col-md-12" id="for_panel2">
                                <div class="custom-control custom-checkbox mr-sm-2">
                                    <input type="checkbox" class="custom-control-input" id="label2" name="chair_panel" @if(!is_null(old('chair_panel'))) @if(old('chair_panel')=='on')checked @endif @elseif($data['account']->isChairPanelAll) checked  @endif>
                                    <label class="custom-control-label" for="label2" data-toggle="popover" data-content="The chair panel for all panel members. Note: There can only be one (1) chair panel member for all panel members" data-placement="top">Chair Panel for all panel members</label>
                                </div>  
                            </div>
                            <div class="form-group col-md-12" id="for_panel3">
                                <div class="custom-control custom-checkbox mr-sm-2">
                                    <input type="checkbox" class="custom-control-input" id="label3" name="active_panel_member" @if(!is_null(old('active_panel_member'))) @if(old('active_panel_member')=='on') checked @endif @elseif($data['account']->isActivePanel) checked  @endif>
                                    <label class="custom-control-label" for="label3" data-toggle="popover" data-content="Specifies to whether this account will be included as a panel member or not." data-placement="top">Active Panel Member</label>
                                </div>  
                            </div>
                          </div>
                          <div class="form-row">
                                <div class="form-group col-md-5" id="grp">
                                    <label for="group">Group</label>
                                    <div class="form-row">
                                        <div class="form-group col">
                                    <select id="group" class="form-control" name="group" autocomplete="group" style="width: 100%">
                                        <option value="">None</option>
                                        @foreach($data['group'] as $grp)
                                        <option value="{{$grp->groupID}}">{{$grp->groupName}}</option>
                                        @endforeach
                                    </select>
                                        </div>
                                    </div>
                                </div>
                          </div>
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
$('#title').select2({allowClear:true,selectOnClose:true,width: 'resolve'});
$('#role').select2({allowClear:true,selectOnClose:true,width: 'resolve'});
function groupAllow(v) {
		if(v.value==3) {
      $('#grp').show();
      $('#for_panel1').hide();
      $('#for_panel2').hide();
    } else if(v.value==2) {
      $('#grp').hide();
      $('#for_panel1').show();
      $('#for_panel2').show();
    }
    else {
      $('#grp').hide();
      $('#for_panel1').show();
      $('#for_panel2').hide();
    }
  }
 groupAllow(0);
$(document).ready(function () {
    var $group = $("#group").select2();
    var $title = $("#title").select2();
    var $role = $("#role").select2();
    $group.val("{{!is_null(old('group')) ? old('group') : $data['account']->accGroupID}}").trigger("change");
    $title.val("{{!is_null(old('title')) ? old('title') : $data['account']->accTitle}}").trigger("change");
    $role.val("{{!is_null(old('role')) ? old('role') : $data['account']->accType}}").trigger("change");
});
function groupAllow(v) {
		if(v.value==3) {
      $('#grp').show();
      $('#for_panel1').hide();
      $('#for_panel2').hide();
      $('#for_panel3').hide();
    } else if(v.value==2) {
      $('#grp').hide();
      $('#for_panel1').show();
      $('#for_panel2').show();
      $('#for_panel3').show();
    }
    else {
      $('#grp').hide();
      $('#for_panel1').show();
      $('#for_panel2').hide();
      $('#for_panel3').hide();
    }
  }
</script>
@endsection