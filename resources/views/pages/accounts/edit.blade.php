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
                {!!Form::open(['action' => ['AccountController@update',$data['account']->accNo], 'method' => 'POST']) !!}
                        <fieldset>
                            <legend class="text-left"><span class="alert bg2">EDIT ACCOUNT FORM</span><hr class="my-4"></legend>
                    
                            {{csrf_field()}} 
                    <div class="form-row">
                            <div class="form-group col-md-5">
                              <label for="given_name">Given Name</label>
                              <input name="given_name" type="text" class="form-control" id="given_name" placeholder="Given Name" required="yes" autocomplete="given-name" value="{{$data['account']->accFName}}">
                            </div>
                            <div class="form-group col-md-2">
                                    <label for="middle_initial">Middle Initial</label>
                                    <input name="middle_initial" type="text" class="form-control" id="middle_initial" placeholder="Middle Initial" maxlength="1" required="yes" autocomplete="middle-initial" value="{{$data['account']->accMInitial}}">
                                  </div>
                            <div class="form-group col-md-5">
                              <label for="last_name">LastName</label>
                              <input name="last_name" type="text" class="form-control" id="last_name" placeholder="Last Name" required="yes" autocomplete="family-name" value="{{$data['account']->accLName}}">
                            </div>
                    </div>
                          <div class="form-group">
                            <label for="email">Email Address</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="ccs@su.edu.ph" required="yes" autocomplete="email" value="{{$data['account']->accEmail}}">
                          </div>
                          <div class="form-row">
                            
                            <div class="form-group col-md-4">
                              <label for="role">Role</label>
                              <select id="role" class="form-control" name="role" autocomplete="role" onchange="groupAllow(this);" required="yes" style="width: 100%">
                                @foreach($data['acc_type'] as $acc)
                                  <option value="{{$acc->accTypeNo}}">{{$acc->accTypeDescription}}</option>
                                @endforeach
                              </select>
                            </div> 
                            
                            <div class="form-group col-md-3" id="title-1">
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
                                <div class="msel"></div>
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
                                        <option value="{{$grp->groupNo}}">{{$grp->groupName}}</option>
                                        @endforeach
                                    </select>
                                        </div>
                                    </div>
                                </div>
                          </div>
                          <div class="form-group text-right">
                              <hr class="my-4">
                              <button type="reset" class="btn btn-info btn-lg">
                                <span><i class="fas fa-recycle"></i> Reset Values</span>
                              </button>
                              <button type="submit" class="btn btn-success btn-lg">
                                  <span><i class="far fa-edit"></i> Save Changes</span>
                              </button>
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
$(document).ready(function () {
  groupAllow(0);
    var $group = $("#group").select2();
    var $title = $("#title").select2();
    var $role = $("#role").select2();
    $group.val("{{$data['account']->accGroupNo}}").trigger("change");
    $title.val("{{$data['account']->accTitle}}").trigger("change");
    $role.val("{{$data['account']->accType}}").trigger("change");
});
function groupAllow(v) {
		if(v.value==3) {
      $('#grp').show();
      $('#title-1').hide();
    } else {
      $('#grp').hide();
      $('#title-1').show();
    }
  }

   
</script>
@endsection