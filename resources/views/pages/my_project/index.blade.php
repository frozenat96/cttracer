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
            
        @include('inc.messages')
        <div class="row">
        
        </div>

        <div class="jumbotron bx2">
                <legend class="text-left"><span class="alert bg2">MY PROJECT</span><hr class="my-4"></legend>
        @if(isset($data) && count($data))
        <div class="row">
            <!--
            <div class="col-md-12">
                <h5 id="font2">Project Name : </h5><ul class="list1"><li>{{$data['proj']->projName}}
                        <a href="/my-project/{{$data['proj']->projNo}}/edit" class="">Edit title</a></li></ul><br>
            </div>
            -->
        </div>
        <div class="row">
            <div class="col-md-4">
                    
                 
            
        

            <section>
            <h5>Content Adviser</h5>
            <ul class="list1">
                <li>
                    {{$data['adviser']->accTitle}} {{$data['adviser']->accFName}} {{$data['adviser']->accMInitial}} {{$data['adviser']->accLName}}
                </li>
            </ul>
            <br>
            </section>
            <section>
                <h5>Group Members</h5>
                <ul class="list1">
                @foreach($data['group'] as $members)
                    <li class="">{{$members->accFName}} {{$members->accMInitial}} {{$members->accLName}}</li>
                @endforeach
                </ul>
            </section>
            </div>

            <div class="col-md-5">
                <section>
                    <h5>Project Information</h5> 
                        <ul class="list1">
                            <li>
                        <h6>Stage : {{$data['proj']->projStageNo}} ({{$data['proj']->stageName}})</h6>
                            </li>
                            <li>
                        <h6>Group Status : {{$data['proj']->groupStatus}}</h6>
                            </li>
                            <li>
                        <h6>Project Panel Verdict : {{$data['proj']->pVerdictDescription}}</h6>
                            </li>
                            <li>
                        <h6>Project Document : 
                        <a href="{{($data['proj']->projDocumentLink)}}" target="_blank" data-content="Download project document" data-toggle="popover" data-placement="top"><i class="fas fa-download"></i> download</a>
                            </li></h6>
                        </ul>
                </section>
            </div>
            <div class="col-md-3">
                @if(in_array($data['proj']->groupStatus,['Waiting','Corrected by Content Adviser']))
                <h5>Options</h5>
                <a href="/my-project/{{$data['proj']->groupNo}}/edit" class="btn btn-primary">Submit Document</a>
                @elseif($data['proj']->groupStatus == 'Submitted to Content Adviser')
                <h5>Status</h5>
                <span>Waiting for content adviser's approval</span>
                @elseif($data['proj']->groupStatus == 'Submitted to Panel Members')
                <h5>Status</h5>
                <span>Waiting for panel members' approval</span>
                @endif
            </div>
        </div>
        @if((in_array($data['proj']->projPVerdictNo,['2','3'])) && (in_array($data['proj']->groupStatus,['Approved by Content Adviser','Submitted to Panel Members','Corrected by Panel Members'])) )
        <hr class="my-4">
        <div class="row">
            <div class="col-md-12">
            <h5>Project Revisions Approval</h5>
            <table class="table table-striped table-hover table-hover">
                <thead>
                    <tr class="">
                        <th>Position</th>
                        <th>Name</th>
                        <th>Approval Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data['projApp'] as $pmembers)
                        <tr class="">
                            <td>
                                @if($pmembers->panelIsChair)
                                    Chair panel member
                                @else
                                    Panel member
                                @endif
                            </td>
                            <td>
                                {{$pmembers->accTitle}}
                                {{$pmembers->accFName}} {{$pmembers->accMInitial}} {{$pmembers->accLName}}
                            </td>
                            <td>
                                @if($pmembers->isApproved == 1)
                                <span class="badge badge-success badge-pill">  Approved </span>
                                @elseif($pmembers->isApproved == 2)
                                <span class="badge badge-danger badge-pill">  Returned with Corrections </span> <a href="{{($pmembers->revisionLink)}}" target="_blank" data-content="Download document with corrections" data-toggle="popover" data-placement="top"><i class="fas fa-download"></i> download</a>
                                @else
                                <span class="badge badge-secondary badge-pill">  Waiting </span>
                                @endif  
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            </div>
        </div>

        <!-- comment section -->
        <div class="form-row">
            <?php $grpModel = new App\models\Group; ?>
            <label for="">Comments of panel members</label>
                @foreach($data['projApp'] as $pmember)
                <div class="form-group col-md-12 align-self-center">
                
                <label for="proj_approval_comment">
                    <span title='{{$pmember->accTitle}} {{$pmember->accFName}} {{$pmember->accMInitial}} {{$pmember->accLName}}'>
                        {{$pmember->accLName}}, {{$grpModel->initials($pmember->accFName)}}@if($pmember->panelIsChair)
                        (Chair panel member) @endif
                        </span>
                </label>
                <textarea id="proj_approval_comment" name="proj_comment_{{$pmember->accNo}}" class="form-control" readonly="readonly">{{$pmember->projAppComment}}</textarea>
                </div>
                @endforeach
        </div> 
        <!-- End of comment section -->

        @elseif((!in_array($data['proj']->projPVerdictNo,['2','3'])) && (in_array($data['proj']->groupStatus,['Approved by Content Adviser','Submitted to Panel Members','Corrected by Panel Members'])))
        <hr class="my-4">
        <div class="row">
            <div class="col-md-4">
            <h5>Schedule Information</h5>
            <br>
            <ul class="list1">
                    <li>
                <h6>Date : {{date_format(new Datetime($data['schedApp']->schedDate),"F j, Y")}}</h6>
                    </li>
                    <li>
                <h6>Starting Time : {{date_format(new Datetime($data['schedApp']->schedTimeStart),"g:i A")}}</h6>
                    </li>
                    <li>
                <h6>Ending Time : {{date_format(new Datetime($data['schedApp']->schedTimeEnd),"g:i A")}}</h6>
                    </li>
                    <li>
                <h6>Place : {{$data['schedApp']->schedPlace}}</h6>
                    </li>
                    <li>
                <h6>Type of schedule : {{$data['schedApp']->schedType}}</h6>
                    </li>
                    <li>
                <h6>Status : {{$data['schedApp']->schedStatus}}</h6>
                     </li>
            </ul>
            </div>
            <div class="col-md-8">
            <h5>Schedule Approval</h5>
            <table class="table table-striped table-hover table-hover">
                <thead>
                    <tr class="">
                        <th>Position</th>
                        <th>Name</th>
                        <th>Approval Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data['schedApp'] as $pmembers)
                        <tr class="">
                            <td>
                                @if($pmembers->panelIsChair)
                                    Chair panel member
                                @else
                                    Panel member
                                @endif
                            </td>
                            <td>
                                {{$pmembers->accTitle}}
                                {{$pmembers->accFName}} {{$pmembers->accMInitial}} {{$pmembers->accLName}}
                            </td>
                            <td>
                                @if($pmembers->isApproved == 1)
                                <span class="badge badge-success badge-pill">  Approved </span>
                                @elseif($pmembers->isApproved == 2)
                                <span class="badge badge-danger badge-pill">  Disapproved </span>
                                @else
                                <span class="badge badge-secondary badge-pill">  Waiting </span>
                                @endif  
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            </div>
        </div>
        @endif
        </div>
        </div>
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