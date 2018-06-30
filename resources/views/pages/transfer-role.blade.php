@extends('layouts.app')

@section('includes')

@endsection

@section('style')
 
@endsection

@section('content')
<div class="row">
    <div class="col-md-12 justify-align-center" id="index_content1">
        
        <div class="row justify-content-center">
            <div class="col-md-9 bx2 jumbotron">
                @include('inc.messages')
                <form method="post" action="{{action('AccountController@transferExecute')}}" accept-charset="UTF-8" role="create">
                        <fieldset>
                                <legend class="text-left"><span class="alert bg2">TRANSFER ROLE FORM</span><hr class="my-4"></legend>
                    
                            {{csrf_field()}} 
                    <div class="form-row">
                        <div class="form-group col-md-6" id="acc">
                            <label for="transferee_account">Transfer To</label>
                            <?php $model = new App\models\Group; ?>
                            <select id="transferee_account" class="form-control" name="transferee_account" autocomplete="Transfer To">
                                @foreach($data as $acc)
                                <option value="{{$acc->accNo}}" title="{{$acc->accTitle}} {{$acc->accFName}} {{$acc->accMInitial}} {{$acc->accLName}}"><span>{{$acc->accLName}}, {{$model->initials($acc->accFName)}}</span></option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group text-center">
                        <hr class="my-4">
                        <button type="button" class="btn btn-warning btn-lg" data-toggle="modal" data-target="#confirm1">
                            <span><i class="fas fa-exchange-alt"></i> Transfer Role</span>
                        </button>
                        <button id="sub2" type="submit" class="btn btn-success btn-lg" style="display:none;">
                    </div>  
                    <input type="hidden" name="_method" value="PUT">     
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@section('includes2')
<script type="text/javascript">

$(document).ready(function () {

  $('#transferee_account').select2({allowClear:true,selectOnClose:true});
});

</script>
@endsection