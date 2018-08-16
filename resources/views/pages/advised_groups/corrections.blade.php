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
                            <input type="hidden" name="groupID" value="{{$data->groupID}}">
                        </div>
                    </div>
                    <div class="form-row">
                        @if(!is_null($data->projPCorrectionLink) && trim($data->projPCorrectionLink)!='')
                        <div class="form-group col-md-12">
                            <a class="btn btn-secondary" href="{{$data->projPCorrectionLink}}"><i class="far fa-eye"></i> View Panel Member's Corrections
                            </a>
                        </div>
                        @endif
                        @if(!is_null($data->projCAdvCorrectionLink) && trim($data->projCAdvCorrectionLink)!='')
                        <div class="form-group col-md-12">
                            <a class="btn btn-dark" href="{{$data->projCAdvCorrectionLink}}"><i class="far fa-eye"></i> View Old Document
                            </a>
                        </div>
                        @endif
                        <div class="form-group col-md-12">
                            <a class="btn btn-primary" href="{{$data->projDocumentLink}}"><i class="far fa-eye"></i> View Submitted Document
                            </a>
                        </div>
                    </div>
                    <!-- options -->
                    <hr class="my-4">
                    <div class="form-row">
                        <div class="col-md-12 text-right">
                        <table class="table-responsive-md" style="float:right;">
                        <tr>
                            <td style="padding-right:3px;">
                                <a class="btn btn-secondary btn-lg" href="/advised-groups"><i class="fas fa-arrow-left"></i> Back to Advised Groups</a>
                            </td>
                            <td style="padding-right:3px;">    
                                {!!Form::open(['action' => 'AdvisedGroupsController@contentAdvCorrections', 'method' => 'POST','class'=>'form1']) !!} 
                                <button  type="submit" class="btn btn-danger btn-lg" name="submit" value="2" data-toggle="popover" data-content="Make corrections to the document" data-placement="top" onclick="return confirm('Are you sure?');"><span><i class="far fa-edit"></i> Set as Corrected</span></button>
                                <input type="hidden" name="groupID" value="{{$data->groupID}}">
                                <input type="hidden" name="_method" value="PUT">
                                {!!Form::close() !!}
                            </td>
                            <td>
                                {!!Form::open(['action' => 'AdvisedGroupsController@contentAdvApproval', 'method' => 'POST','class'=>'form1']) !!} 
                                <button  type="submit" class="btn btn-success btn-lg" name="submit" value="1" data-toggle="popover" data-content="Approve schedule" data-placement="top" onclick="return confirm('Are you sure?');"><span><i class="fas fa-check"></i> Approve</span></button>
                                <input type="hidden" name="groupID" value="{{$data->groupID}}">
                                <input type="hidden" name="_method" value="PUT">
                                {!!Form::close() !!}
                            </td>
                        </tr>
                        </table>
                        </div>
                    </div>
                    <!-- options -->

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