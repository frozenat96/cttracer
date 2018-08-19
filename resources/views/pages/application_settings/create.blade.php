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
                {!!Form::open(['action' => 'PagesController@appSettingsStore', 'method' => 'POST','class'=>'form1']) !!}
                        <fieldset>
                                <h4 class="text-left"><span class="alert bg2">CREATE APPLICATION SETTINGS FORM</span><hr class="my-4"></h4>
                    
                            {{csrf_field()}} 
                    <!-- required fields note -->
                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <span><b>
                                Note : fields with <span class="text-danger">*</span> are required fields.</b>
                            </span>
                        </div>
                    </div>
                    <!-- required fields note -->
                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <label for="document_folder_link">Documents Folder Link<span class="text-danger">*</span></label>
                            <input type="url" maxlength="150" class="form-control" placeholder="URL link" name="document_folder_link" autocomplete="Document Link" required="yes" value="{{!is_null(old('document_folder_link')) ? old('document_folder_link') : ''}}"> 
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <label for="project_archive_folder_link">Project Archive Folder Link<span class="text-danger">*</span></label>
                            <input type="url" maxlength="150" class="form-control" placeholder="URL link" name="project_archive_folder_link" autocomplete="Document Link" required="yes" value="{{!is_null(old('project_archive_folder_link')) ? old('project_archive_folder_link') : ''}}"> 
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6 my-1">          
                            <div class="custom-control custom-checkbox mr-sm-2">
                                <input type="checkbox" class="custom-control-input" id="label1" name="auto_delete_revision_history" @if(!is_null(old('auto_delete_revision_history'))) checked @endif>
                                <label class="custom-control-label" for="label1" data-toggle="popover" data-content="Allows automatic deletion of revision history of all finished groups when deleting all finished groups" data-placement="top">Auto-delete Revision History</label>
                            </div>          
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6 my-1">          
                            <div class="custom-control custom-checkbox mr-sm-2">
                                <input type="checkbox" class="custom-control-input" id="label1" name="auto_delete_group_history" @if(!is_null(old('auto_delete_group_history'))) checked @endif>
                                <label class="custom-control-label" for="label1" data-toggle="popover" data-content="Allows automatic deletion of group history of all finished groups when deleting all finished groups" data-placement="top">Auto-delete Group History</label>
                            </div>          
                        </div>
                    </div>
                    <!-- options -->
                    <hr class="my-4">
                    <div class="form-row">
                        <div class="col-md-12 text-right">
                        <table class="table-responsive-md" style="float:right;">
                        <tr>
                            <td style="padding-right:3px;" class="back-button">
                                <a class="btn btn-secondary btn-lg" href="/"><i class="fas fa-arrow-left"></i> Back</a>
                            </td>
                            <td style="padding-right:3px;">    
                                <button type="reset" class="btn btn-info btn-lg">
                                <span><i class="fas fa-recycle"></i> Reset Values</span>
                                </button>
                            </td>
                            <td>
                                <button type="button" id="sub1" class="btn btn-success btn-lg" data-toggle="modal" data-target="#confirm1">
                                    <span><i class="fas fa-plus"></i> Create Settings</span>
                                </button>
                                <button id="sub2" type="submit" style="display:none;"></button>
                            </td>
                        </tr>
                        </table>
                        </div>
                    </div>
                    <!-- options -->
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