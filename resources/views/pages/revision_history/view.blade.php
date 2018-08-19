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
                <h4 class="text-left"><span class="alert bg2">REVISION VIEW</span><hr class="my-4"></h4>
        @if(isset($data['projApp']) && count($data['projApp']))
        <!-- Project Revision Approval -->
        <div class="row">
            <div class="col-md-12">
            <h5>Revision Details : </h5>
            <h6>For the group of : {{$data['projApp'][0]->revGroupName}}</h6>
            <h6>Stage : {{$data['projApp'][0]->stageNo}} - {{$data['projApp'][0]->stageName}}</h6>
            <h6>Revision {{$data['projApp'][0]->revNo}}</h6>
            <hr>
            <h5>Project Approval Details : </h5>
            <table class="table table-striped table-hover table-hover table-responsive-sm table-responsive-md">
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
                                @if($rev->revPanelIsChair)
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
                                {{date_format(new Datetime($rev->revTimestamp),"M-d-Y -- g:i A")}}
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
                        {{$rev->accLName}}, {{$grpModel->initials($rev->accFName)}}@if($rev->revPanelIsChair)
                        (Chair panel member) @endif
                        </span>
                </label>
                <textarea id="proj_approval_comment" name="proj_comment_{{$rev->accID}}" class="form-control" readonly="readonly">{{$rev->revComment}}</textarea>
                </div>
                @endforeach
        </div> <!-- End of comment section -->
        <!-- End of Project Revision Approval -->
        <div class="form-row">
            <div class="col-md-12 text-right">
        <a class="btn btn-secondary btn-lg" href="/revision-history-search-results/{{$rev->revGroupName}}"><i class="fas fa-arrow-left"></i> Back</a>
            </div>
        </div>
      
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