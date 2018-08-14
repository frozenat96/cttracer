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
                <form method="post" action="{{action('AccountController@store')}}" accept-charset="UTF-8" role="create" class="form1">
                        <fieldset>
                            <legend class="text-left"><span class="alert bg2">CREATE ACCOUNT FORM</span><hr class="my-4"></legend>
                    
                            {{csrf_field()}} 
                    <div class="form-row">
                            <div class="form-group col-md-5">
                              <label for="given_name">Given Name<span class="text-danger">*</span></label>
                                <input name="given_name" type="text" maxlength="50" class="form-control" id="given_name" placeholder="Given Name" required="yes" autocomplete="given-name" value="{{old('given_name')}}">
                            </div>
                            <div class="form-group col-md-2">
                                    <label for="middle_initial">Middle Initial</label>
                                    <input name="middle_initial" type="text" class="form-control" id="middle_initial" placeholder="Middle Initial" maxlength="1" autocomplete="middle-initial" value="{{old('middle_initial')}}">
                                  </div>
                            <div class="form-group col-md-5">
                              <label for="last_name">LastName<span class="text-danger">*</span></label>
                              <input name="last_name" type="text" maxlength="50" class="form-control" id="last_name" placeholder="Last Name" required="yes" autocomplete="family-name" value="{{old('last_name')}}">
                            </div>
                    </div>
                          <div class="form-group">
                            <label for="email">Email Address<span class="text-danger">*</span></label>
                            <input type="email" maxlength="70" class="form-control" id="email" name="email" placeholder="ccs@su.edu.ph" required="yes" autocomplete="email" value="{{old('email')}}">
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
                                    <input type="checkbox" class="custom-control-input" id="label2" name="chair_panel">
                                    <label class="custom-control-label" for="label2" data-toggle="popover" data-content="The chair panel for all panel members. Note: There can only be one (1) chair panel member for all panel members" data-placement="top" @if(!is_null(old('chair_panel'))) checked @endif>Chair Panel for all panel members</label>
                                </div>  
                            </div>
                          </div>
                          <div class="form-row">
                              <div class="form-group col-md-5" id="grp">
                                  <label for="group">Group</label>
                                  <div class="form-row">
                                      <div class="form-group col">
                                  <select id="group" class="form-control" name="group" autocomplete="group" style="width: 100%">
                                      <option value="none" selected>None</option>
                                      @foreach($data['group'] as $grp)
                                      <option value="{{$grp->groupID}}" @if(!is_null(old('group')) && old('group')==$grp->groupID) selected @endif>{{$grp->groupName}}</option>
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
                                  <span><i class="fas fa-plus"></i> Create Account</span>
                              </button>
                              <button id="sub2" type="submit" class="btn btn-success btn-lg" style="display:none;">
                                  <span><i class="far fa-edit"></i> Save Changes</span>
                              </button>
                          </div>
                        </fieldset>
                </form>
            </div>
        </div>
    
    </div>
</div>
@endsection
@section('includes2')
<script type="text/javascript">
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
    $group.val("{{!is_null(old('group')) ? old('group') : null}}").trigger("change");
    $title.val("{{!is_null(old('title')) ? old('title') : ''}}").trigger("change");
    $role.val("{{!is_null(old('role')) ? old('role') : '3'}}").trigger("change");
  
});


   $('#group').select2({allowClear:true,selectOnClose:true,width: 'resolve'});
   $('#title').select2({allowClear:true,selectOnClose:true,width: 'resolve'});
   $('#role').select2({allowClear:true,selectOnClose:true,width: 'resolve'});
</script>
@endsection