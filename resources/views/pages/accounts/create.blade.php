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
                <form method="post" action="{{action('AccountController@store')}}" accept-charset="UTF-8" role="create">
                        <fieldset>
                            <legend class="text-left"><span class="alert bg2">CREATE ACCOUNT FORM</span><hr class="my-4"></legend>
                    
                            {{csrf_field()}} 
                    <div class="form-row">
                            <div class="form-group col-md-5">
                              <label for="given_name">Given Name</label>
                              <input name="given_name" type="text" class="form-control" id="given_name" placeholder="Given Name" required="yes" autocomplete="given-name">
                            </div>
                            <div class="form-group col-md-2">
                                    <label for="middle_initial">Middle Initial</label>
                                    <input name="middle_initial" type="text" class="form-control" id="middle_initial" placeholder="Middle Initial" maxlength="1" required="yes" autocomplete="middle-initial">
                                  </div>
                            <div class="form-group col-md-5">
                              <label for="last_name">LastName</label>
                              <input name="last_name" type="text" class="form-control" id="last_name" placeholder="Last Name" required="yes" autocomplete="family-name">
                            </div>
                    </div>
                          <div class="form-group">
                            <label for="email">Email Address</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="ccs@su.edu.ph" required="yes" autocomplete="email">
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
                                      <option value="none" selected>None</option>
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
                                  <span><i class="fas fa-plus"></i> Create Account</span>
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

$(document).ready(function () {
  groupAllow(0);
  
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

   $('#group').select2({allowClear:true,selectOnClose:true,width: 'resolve'});
   $('#title').select2({allowClear:true,selectOnClose:true,width: 'resolve'});
   $('#role').select2({allowClear:true,selectOnClose:true,width: 'resolve'});
</script>
@endsection