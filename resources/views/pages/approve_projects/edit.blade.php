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
                @if(isset($data) && !is_null($data))
                
                        <fieldset>
                                <legend class="text-left"><span class="alert bg2">APPROVE/CORRECT DOCUMENT</span><hr class="my-4"></legend>
                    
                            {{csrf_field()}} 
                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <label for="for_group">For Group Of : {{$data->groupName}}</label>
                        </div>
                    </div>
                    <div class="form-row">
                        @if(!is_null($data->projPCorrectionLink) && trim($data->projPCorrectionLink)!='')
                        <div class="form-group col-md-12">
                            <a class="btn btn-dark" href="{{$data->projPCorrectionLink}}" target="_blank"><i class="far fa-eye"></i> View Old Document
                            </a>
                        </div>
                        @endif
                        <div class="form-group col-md-12">
                            <a class="btn btn-primary" href="{{$data->projDocumentLink}}" target="_blank"><i class="far fa-eye"></i> View Submitted Document
                            </a>
                        </div>
            
                    </div>
                    {!!Form::open(['action' => 'ProjAppController@projApprovalStatus', 'method' => 'POST','class'=>'form1']) !!}
                    <div class="form-row">
                            <div class="form-group col-md-12" id="acc">
                                <label for="document_link">Comments</label>
                                <textarea class="form-control" name="comments" autocomplete="Comments" placeholder="Optional comments (maximum of 1600 characters)" maxlength="1600">{{!is_null(old('comments')) ? old('comments') : $data->projAppComment}}</textarea>
                            </div>
                    </div>
                    <hr class="my-4">
                    <table class="table-responsive-md" style="float:right;">
                    <tr>
                        <td style="padding-right:3px;">    
                            <button type="reset" class="btn btn-info btn-lg">
                              <span><i class="fas fa-recycle"></i> Reset Values</span>
                            </button>
                        </td>
                        <td style="padding-right:3px;">
                            <button type="submit" class="btn btn-danger btn-lg" onclick="return confirm('Are You Sure?')" data-toggle="popover" data-content="Make corrections to the document" data-placement="top">
                                <span><i class="far fa-edit"></i> Set as Corrected</span>
                            </button> 
                        </td>
                        <input type="hidden" name="opt" value="0">
                        <input type="hidden" name="grp" value="{{$data->groupID}}">
                        <input type="hidden" name="acc" value="{{$data->panelAccID}}">
                        <input type="hidden" name="_method" value="PUT">
                {!!Form::close() !!}
                <td>
                {!!Form::open(['action' => 'ProjAppController@projApprovalStatus', 'method' => 'POST','class'=>'form1']) !!}
                    {{csrf_field()}}
                    <button  type="submit" class="btn btn-success btn-lg" name="submit" value="1" data-toggle="popover" data-content="Approve revision" data-placement="top" onclick="return confirm('Are You Sure?')"><span><i class="fas fa-check"></i> Approve</span></button>
                    <input type="hidden" name="opt" value="1">
                    <input type="hidden" name="grp" value="{{$data->groupID}}">
                    <input type="hidden" name="acc" value="{{$data->panelAccID}}">
                    <input type="hidden" name="_method" value="PUT">
                    {!!Form::close() !!}
                </td>
                </tr>
                </table>
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