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
                @if(isset($data))
                {!!Form::open(['action' => ['MyProjController@update',$data['group']->projID], 'method' => 'POST','class'=>'form1']) !!}
                        <fieldset>
                                <legend class="text-left"><span class="alert bg2">SUBMIT DOCUMENT TO CONTENT ADVISER</span><hr class="my-4"></legend>
                    
                            {{csrf_field()}} 
                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <label for="for_group">For Group Of : {{$data['group']->groupName}}</label>
                            <input type="hidden" name="groupID" value="{{$data['group']->groupID}}">
                            <p>Instructions: </p>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <a class="btn btn-dark" href="{{$data['settings']->settingDocLink}}" target="_blank"><i class="far fa-eye"></i> Open Documents Folder
                            </a>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-12" id="acc">
                            <label for="document_link">Document Link</label>
                            <input type="url" maxlength="150" class="form-control" name="document_link" autocomplete="Document Link" required="yes" value="{{!is_null(old('document_link')) ? old('document_link') : $data['group']->projDocumentLink}}">
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
                @else
                <p>No results found.</p>
                @endif
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