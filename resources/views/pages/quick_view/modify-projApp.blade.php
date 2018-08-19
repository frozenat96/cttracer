@extends('layouts.app')

@section('includes')

@endsection

@section('style')
 
@endsection

@section('content')
<?php $grpModel = new App\models\Group; $userModel = new App\User; $user1=$userModel->current();?>
<div class="row">
    <div class="col-md-12 justify-align-center" id="index_content1">   
        <div class="row justify-content-center">
            <div class="col-md-9 jumbotron bx2">
                @include('inc.messages')
                {!!Form::open(['action' => 'QuickViewController@modifyProjAppUpdate', 'method' => 'POST','class'=>'form1']) !!}
                        <fieldset>
                        <div class="form-row">
                                <div class="form-group col-md-12">
                                <table class="table table-responsive-sm table-responsive-md">
                                <tr>
                                    <td>
                            <h4 class="text-left"><span class="alert bg2">MODIFY PROJECT APPROVAL</span></h4>
                                    </td>
            
                                    <td class="text-right">
                            <a class="btn btn-secondary btn-lg btn-block" href="/quick-view"><i class="fas fa-arrow-left"></i> Back</a>
                                    </td>
                                </tr>
                                </table>
                                </div>
                            </div>
                    
                            {{csrf_field()}} 
                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <label for="date">For The Group Of : {{$data[0]->groupName}}</label>
                        </div>   
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <label for="proj_approval">Project Approval Status</label>
                            <table class="table table-striped table-sm table-responsive-sm table-responsive-md" id="proj_approval">
                                <thead>
                                    <tr>
                                    <th>Panel Name</th>
                                    <th>Approval Status</th>
                                    <th>Corrected Document Link</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($data as $pmember)
                                    <tr>
                                        <td>
                                        <span title='{{$pmember->accTitle}} {{$pmember->accFName}} {{$pmember->accMInitial}} {{$pmember->accLName}}'>
                                        {{$pmember->accLName}}, {{$grpModel->initials($pmember->accFName)}}@if($pmember->panelIsChair)
                                        (Chair panel member) @endif
                                        </span>
                                        </td>
                                        <td>
                                        <select id="proj_app" class="form-control proj_app" name="proj_app_{{$pmember->accID}}" autocomplete="Project Approval">
                                                <option value="3" @if(!is_null(old('proj_app_{{$pmember->accID}}')) && old('proj_app_{{$pmember->accID}}')=='3') selected @elseif(($pmember->isApproved)=='3') selected @endif>Disabled</option>
                                                <option value="0" @if(!is_null(old('proj_app_{{$pmember->accID}}')) && old('proj_app_{{$pmember->accID}}')=='0') selected @elseif(($pmember->isApproved)=='0') selected @endif>Waiting</option>
                                                <option value="1" @if(!is_null(old('proj_app_{{$pmember->accID}}')) && old('proj_app_{{$pmember->accID}}')=='1') selected @elseif(($pmember->isApproved)=='1') selected @endif>Approved</option>
                                                <option value="2" @if(!is_null(old('proj_app_{{$pmember->accID}}')) && old('proj_app_{{$pmember->accID}}')=='2') selected @elseif(($pmember->isApproved)=='2') selected @endif>Corrected</option>
                                        </select>
                                        </td>
                                        <td>
                                            <input type="url" class="form-control" name="proj_rlink_{{$pmember->accID}}" maxlength="150" autocomplete="Corrected Document Link" value="{{!is_null(old('proj_rlink_' . $pmember->accID)) ? old('proj_rlink_'. $pmember->accID) : $pmember->revisionLink}}">
                                        </td> 
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <label for="">Comments of panel members</label>
                            @foreach($data as $pmember)
                            <hr class="my-4">
                            <label for="proj_approval_comment">
                                <span title='{{$pmember->accTitle}} {{$pmember->accFName}} {{$pmember->accMInitial}} {{$pmember->accLName}}'>
                                    {{$pmember->accLName}}, {{$grpModel->initials($pmember->accFName)}}@if($pmember->panelIsChair)
                                    (Chair panel member) @endif
                                    </span>
                            </label>
                            <textarea id="proj_approval_comment" name="proj_comment_{{$pmember->accID}}" class="form-control" maxlength="1600">{{!is_null(old('proj_comment_' . $pmember->accID)) ? old('proj_comment_' . $pmember->accID) : $pmember->projAppComment}}</textarea>

                            @endforeach
                        </div>
                    </div>
                    <!-- options -->
                    <hr class="my-4">
                    <div class="form-row">
                        <div class="col-md-12 text-right">
                        <table class="table-responsive-md" style="float:right;">
                        <tr>
                            <td style="padding-right:3px;" class="back-button">
                                <a class="btn btn-secondary btn-lg" href="/quick-view"><i class="fas fa-arrow-left"></i> Back</a>
                            </td>
                            <td style="padding-right:3px;">    
                                <button type="reset" class="btn btn-info btn-lg">
                                <span><i class="fas fa-recycle"></i> Reset Values</span>
                                </button>
                            </td>
                            <td>
                                <button type="button" id="sub1" class="btn btn-success btn-lg" data-toggle="modal" data-target="#confirm1">
                                    <span><i class="far fa-edit"></i> Save Changes</span>
                                </button>
                                <button id="sub2" type="submit" style="display:none;"></button>
                            </td>
                        </tr>
                        </table>
                        </div>
                    </div>
                    <!-- options -->
                        </fieldset>
                        <input type="hidden" name="groupID" value="{{$data[0]->groupID}}">
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

//$('#group_type').select2({allowClear:true,selectOnClose:true});

</script>
@endsection