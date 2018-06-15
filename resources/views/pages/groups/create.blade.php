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
                <form method="post" action="{{action('GroupController@store')}}" accept-charset="UTF-8" role="create">
                        <fieldset>
                            <legend class="text-left"><span class="alert bg2">CREATE GROUP FORM</span><hr class="my-4"></legend>
                            
                            {{csrf_field()}} 
                    <div class="form-row">
                        <div class="form-group col-md-10">
                            <label for="group_name">Group Name</label>
                            <input name="group_name" type="text" class="form-control" id="group_name" placeholder="Group Name" required="yes" autocomplete="given-name">
                        </div>     
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-3">
                            <label for="group_type">Group Type</label>
                                <select id="group_type" class="form-control" name="group_type" autocomplete="Group Type">
                                <option value="Capstone">Capstone</option>
                                <option value="Thesis">Thesis</option>
                                </select>
                        </div>

                        <div class="form-group col-md-3" id="grp">
                            <label for="content_adviser">Group Content Adviser</label>
                            <?php $model = new App\models\Group; ?>
                            <select id="content_adviser" class="form-control" name="content_adviser" autocomplete="Content Adviser">
                                @foreach($data['content_adviser'] as $acc)
                                <option value="{{$acc->accNo}}" title="{{$acc->accTitle}} {{$acc->accFName}} {{$acc->accMInitial}} {{$acc->accLName}}"><span>{{$acc->accLName}}, {{$model->initials($acc->accFName)}}</span></option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <label for="group_name">Group Project Name</label>
                            <input name="group_name" type="text" class="form-control" id="group_name" placeholder="Group Project Name" required="yes" autocomplete="given-name">
                            </div>    
                    </div>
                            <!--
                        <div class="form-group col-md-2" id="grp">
                            <label for="group_project">Group</label>
                            <select id="group_project" class="form-control" name="group_project" autocomplete="Group Project">
                                @foreach($data['proj'] as $proj)
                                <option value="{{$proj->projNo}}" title="{{$proj->projName}}"><span> {{(substr($proj->projName, 0, 10) . '..')}}</span></option>
                                @endforeach
                            </select>
                            </div>
                        -->
                    
                            <div class="form-group text-right">
                                <hr class="my-4">
                                <button type="submit" class="btn btn-danger">
                                    <span><i class="fas fa-plus"></i> Create Group</span>
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
  
});

$('#group_type').select2({allowClear:true,selectOnClose:true});
$('#content_adviser').select2({allowClear:true,selectOnClose:true});

</script>
@endsection