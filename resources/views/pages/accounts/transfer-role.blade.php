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
                <form method="post" action="{{action('AccountController@transferExecute')}}" accept-charset="UTF-8" role="create" class="form1">
                        <fieldset>
                                <h4 class="text-left"><span class="alert bg2">TRANSFER ROLE FORM</span><hr class="my-4"></h4>
                    
                            {{csrf_field()}} 
                    <div class="form-row">
                        <div class="form-group col-md-6" id="acc">
                            <label for="transferee_account">Transfer To</label>
                            <?php $model = new App\models\Group; ?>
                            <select id="transferee_account" class="form-control" name="transferee_account" autocomplete="Transfer To">
                                @foreach($data as $acc)
                                <option value="{{$acc->accID}}" title="{{$acc->accTitle}} {{$acc->accFName}} {{$acc->accMInitial}} {{$acc->accLName}}"><span>{{$acc->accLName}}, {{$model->initials($acc->accFName)}}</span></option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <!-- options -->
                    <hr class="my-4">
                    <div class="form-row">
                        <div class="col-md-12 text-right">
                        <table class="table-responsive-md" style="float:right;">
                        <tr>
                            <td style="padding-right:3px;" class="back-button">
                                <a class="btn btn-secondary btn-lg" href="/accounts"><i class="fas fa-arrow-left"></i> Back</a>
                            </td>
                            <td>
                                <button type="button" id="sub1" class="btn btn-warning btn-lg" data-toggle="modal" data-target="#confirm1">
                                    <span><i class="far fa-edit"></i> Transfer Role</span>
                                </button>
                                <button id="sub2" type="submit" style="display:none;"></button>
                            </td>
                        </tr>
                        </table>
                        </div>
                    </div>
                    <!-- options -->  
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