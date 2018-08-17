@extends('layouts.app')

@section('style')
    .list1 {
        list-style:none;
    }
    #font2 {
        font-size 20px;
    }
@endsection

@section('content')
<div class="row">
    <div class="col-md-12 justify-align-center" id="index_content1">
        <div class="container">
            
        
        <div class="row">
        
        </div>

        <div class="jumbotron bx2">
                @include('inc.messages')
                <legend class="text-left"><span class="alert bg2">REVISION VIEW</span><hr class="my-4"></legend>
        @if(isset($data) && count($data))
        <!-- Project Revision Approval -->
        <div class="row">
            <div class="col-md-12">
            <h5>Revision Details : </h5>
            <h6>For the group of : {{$data['projApp'][0]->groupName}}</h6>
            <h6>Stage : {{$data['projApp'][0]->stageNo}} - {{$data['projApp'][0]->stageName}}</h6>
            <h6>Revision {{$data['projApp'][0]->revNo}}</h6>
            <hr>
            <h5>Project Approval Details : </h5>
            <table class="table table-striped table-hover table-hover">
                <thead>
                    <tr class="">
                        <th>Position</th>
                        <th>Name</th>
                        <th>Approval Status</th>
                        <th>Date Reviewed</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data['projApp'] as $rev)
                        <tr class="">
                            <td>
                                @if($rev->panelIsChair)
                                    Chair panel member
                                @else
                                    Panel member
                                @endif
                            </td>
                            <td>
                                {{$rev->accTitle}}
                                {{$rev->accFName}} {{$rev->accMInitial}} {{$rev->accLName}}
                            </td>
                            <td>
                                @if($rev->revStatus == 1)
                                <span class="badge badge-success badge-pill">  Approved </span>
                                @elseif($rev->revStatus == 2)
                                <span class="badge badge-danger badge-pill">  Returned with Corrections </span> <a href="{{($rev->revLink)}}" target="_blank" data-content="Download document with corrections" data-toggle="popover" data-placement="top"><i class="fas fa-download"></i> download</a>
                                @else
                                <span class="badge badge-secondary badge-pill">  Waiting </span>
                                @endif  
                            </td>
                            <td>
                                {{date_format(new Datetime($rev->revTimestamp),"Y-m-d g:i A")}}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            </div>
        </div> 
        <hr>
        <!-- comment section -->
        <div class="form-row">
            <?php $grpModel = new App\models\Group; ?>
            <h5>Comments of panel members</h5>
                @foreach($data['projApp'] as $rev)
                <div class="form-group col-md-12 align-self-center">
                
                <label for="proj_approval_comment">
                    <span title='{{$rev->accTitle}} {{$rev->accFName}} {{$rev->accMInitial}} {{$rev->accLName}}'>
                        {{$rev->accLName}}, {{$grpModel->initials($rev->accFName)}}@if($rev->panelIsChair)
                        (Chair panel member) @endif
                        </span>
                </label>
                <textarea id="proj_approval_comment" name="proj_comment_{{$rev->accID}}" class="form-control" readonly="readonly">{{$rev->revComment}}</textarea>
                </div>
                @endforeach
        </div> <!-- End of comment section -->
        <!-- End of Project Revision Approval -->
        <span class="text-right">
        {!!Form::open(['action' => ['RevHistoryController@print'], 'method' => 'POST', 'target'=>'_blank']) !!}
        <button  type="submit" class="btn btn-warning btn-lg" name="submit" data-toggle="popover" data-content="View revision for printing" data-placement="top"><span><i class="fas fa-print"></i> View for Printing</span></button>
        <input type="hidden" name="grp" value="{{$data['projApp'][0]->groupID}}">
        <input type="hidden" name="stg" value="{{$data['projApp'][0]->stageNo}}">
        <input type="hidden" name="rev_no" value="{{$data['projApp'][0]->revNo}}">
        {!!Form::close() !!}
        </span>
      
        </div> <!-- End of div jumbotron -->
        </div> <!-- End of div container -->
        @else
        <span>No results found.</span>
        @endif
    </div>
    </div>
</div>
@endsection
@section('includes2')
    <script type="text/javascript">
       
    </script>
@endsection