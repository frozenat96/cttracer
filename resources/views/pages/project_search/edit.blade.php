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
                
                {!!Form::open(['action' => ['ProjSearchController@update',$data->projID], 'method' => 'POST','class'=>'form1']) !!}
                        <fieldset>
                                <legend class="text-left"><span class="alert bg2">EDIT PROJECT ARCHIVE FORM</span><hr class="my-4"></legend>
                    
                            {{csrf_field()}} 

                    <div class="form-row">  
                        <div class="form-group col-md-12" id="acc">
                            <label for="project_name">Project Name</label>
                            <input class="form-control" type="text" name="project_name" maxlength="150" placeholder="Project Name" autocomplete="Project Name" required="yes" value="{{!is_null(old('project_name')) ? old('project_name') : $data->projName}}">  
                        </div>
                    </div>
                    <div class="form-row">
                            <div class="form-group col-md-12" id="acc">
                                <label for="document_link">Document Link</label>
                                <input class="form-control" type="url" name="document_link" maxlength="150" placeholder="Document Link" autocomplete="Document Link" required="yes" value="{{!is_null(old('document_link')) ? old('document_link') : $data->projDocumentLink}}"> 
                            </div>
                        </div>
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
                        <input type="hidden" name="_method" value="PUT">
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