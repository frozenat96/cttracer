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
                <form method="post" action="{{action('GroupController@store')}}" accept-charset="UTF-8" role="create">
                        <fieldset>
                            <legend class="text-left"><span class="alert bg2">CREATE SCHEDULE SETTING FORM</span><hr class="my-4"></legend>
                            
                            {{csrf_field()}} 
                    <div class="form-row">
                        <div class="form-group col-md-3">
                            <label for="date">Date</label>
                            <input name="date" type="date" class="form-control" id="date" placeholder="" required="yes" autocomplete="date">
                        </div>  
                        <div class="form-group col-md-3">
                                <label for="starting_time">Starting Time</label>
                                <input name="starting_time" type="time" class="form-control" id="starting_time" placeholder="" required="yes" autocomplete="Starting Time">
                        </div>
                        <div class="form-group col-md-3">
                                <label for="ending_time">Ending Time</label>
                                <input name="ending_time" type="time" class="form-control" id="ending_time" placeholder="" required="yes" autocomplete="Ending Time">
                        </div>        
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-10">
                            <label for="place">Place</label>
                            <input name="place" type="text" class="form-control" id="place" placeholder="Place" required="yes" autocomplete="Place">
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
                    </div>
                            <div class="form-group text-right">
                                <hr class="my-4">
                                <button type="reset" class="btn btn-info btn-lg">
                                <span><i class="fas fa-recycle"></i> Reset Values</span>
                                </button>
                                <button type="submit" class="btn btn-success btn-lg">
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

</script>
@endsection