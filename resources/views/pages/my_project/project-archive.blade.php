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
                
                {!!Form::open(['action' => ['MyProjController@submitProjectArchiveStore',$data->groupID], 'method' => 'POST','class'=>'form1']) !!}
                        <fieldset>
                                <legend class="text-left"><span class="alert bg2">SUBMIT PROJECT ARCHIVE FORM</span><hr class="my-4"></legend>
                    
                            {{csrf_field()}} 
                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <a class="btn btn-dark" href="{{$settings->settingProjArcLink}}" target="_blank"><i class="far fa-eye"></i> Open Project Archive Folder
                            </a>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-12" id="acc">
                            <label for="document_link">Document Link<span class="text-danger">* (required)</span></label>
                            <input class="form-control" name="document_link" placeholder="Document Link" autocomplete="Document Link" required="yes" value="{{old('document_link')}}"> 
                        </div>
                    </div>
                    <div class="form-group text-right">
                            <hr class="my-4">
                            <button type="reset" class="btn btn-info btn-lg">
                              <span><i class="fas fa-recycle"></i> Reset Values</span>
                            </button>
                            <button type="button" class="btn btn-success btn-lg" data-toggle="modal" data-target="#confirm1">
                                <span><i class="fas fa-plus"></i> Submit Project Archive</span>
                            </button>
                            <button id="sub2" type="submit" class="btn btn-success btn-lg" style="display:none;">
                        </div>
                {!!Form::close() !!}
            </div>
        </div>
    </div>
</div>
@endsection
@section('includes2')
<script type="text/javascript">

$(document).ready(function () {


});

</script>
@endsection