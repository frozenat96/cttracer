@extends('layouts.app')

@section('style')
 
@endsection

@section('content')
<?php 
$account_types = DB::table('account_type')->get();
?>
<div class="row">
    <div class="col-md-12 justify-align-center" id="index_content1">
        <h4>CREATE ACCOUNT</h4>
        <div class="row justify-content-center">
            <div class="col-md-9 bx">
                
                        <form method="post" action="{{action('AccountController@store')}}" accept-charset="UTF-8" role="create">
                            {{csrf_field()}} 
                            <div class="form-row">
                                    <div class="form-group col-md-5">
                                      <label for="given_name">Given Name</label>
                                      <input name="given_name" type="text" class="form-control" id="given_name" placeholder="Given Name" required="yes">
                                    </div>
                                    <div class="form-group col-md-2">
                                            <label for="middle_initial">Middle Initial</label>
                                            <input name="middle_initial" type="text" class="form-control" id="middle_initial" placeholder="Middle Initial" maxlength="1" required="yes">
                                          </div>
                                    <div class="form-group col-md-5">
                                      <label for="last_name">LastName</label>
                                      <input name="last_name" type="text" class="form-control" id="last_name" placeholder="Last Name" required="yes">
                                    </div>
                                  </div>
                                  <div class="form-group">
                                    <label for="inputEmail">Address</label>
                                    <input type="email" class="form-control" id="inputEmail" placeholder="ccs@su.edu.ph">
                                  </div>
                                  <div class="form-group">
                                    <label for="inputAddress2">Address 2</label>
                                    <input type="text" class="form-control" id="inputAddress2" placeholder="Apartment, studio, or floor">
                                  </div>
                                  <div class="form-row">
                                    <div class="form-group col-md-6">
                                      <label for="inputCity">City</label>
                                      <input type="text" class="form-control" id="inputCity">
                                    </div>
                                    <div class="form-group col-md-4">
                                      <label for="inputState">State</label>
                                      <select id="inputState" class="form-control">
                                        <option selected>Choose...</option>
                                        <option>...</option>
                                      </select>
                                    </div>
                                    <div class="form-group col-md-2">
                                      <label for="inputZip">Zip</label>
                                      <input type="text" class="form-control" id="inputZip">
                                    </div>
                                  </div>
                                  <div class="form-group">
                                    <div class="form-check">
                                      <input class="form-check-input" type="checkbox" id="gridCheck">
                                      <label class="form-check-label" for="gridCheck">
                                        Check me out
                                      </label>
                                    </div>
                                  </div>
                            <span class="input-group-btn">
                                    <button type="submit" class="btn btn-danger">
                                        <span><i class="fas fa-search"></i></span>
                                    </button>
                                </span>
                        </form>
                
            </div>
        </div>
    
    </div>
</div>
@endsection