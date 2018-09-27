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

        <div class="jumbotron bx2" style="padding-top:50px;">
                <h4 class="text-left"><span class="alert bg2">PROJECT VIEW</span><hr class="my-4"></h4>
        @if(isset($data) && count($data))
        <div class="row"> <!-- Group Information -->
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

            <div class="col-md-4">
                <section>
                    <h5>Project Information</h5> 
                        <ul class="list1">
                            <li>
                            <h6>Title :  <span tabindex="0" class="" data-toggle="popover" data-content="{{$data['proj']->projName}}" data-placement="top">{{(substr($data['proj']->projName, 0, 20) . '..')}}</span></h6>
                            </li>
                            
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
                        <a href="{{($data['proj']->projDocumentLink)}}" target="_blank" data-content="Download project document" data-toggle="popover" data-placement="top"><i class="far fa-eye"></i> View Project Document</a>
                        </h6>
                        </li>
                        <?php $allowRevHist = new App\models\RevisionHistory; ?>
                        @if($allowRevHist->status==true)
                        <li>
                            <a href="/revision-history-search-results/{{$data['proj']->groupName}}" target="_blank" data-content="View Revision History" data-toggle="popover" data-placement="top"><i class="far fa-eye"></i> View Revision History</a>
                        </li>
                        @endif
                        </ul>
                </section>
            </div>         
            <div class="col-md-4"> <!-- Schedule Information -->
            @if(!(in_array($data['proj']->projPVerdictNo,['2','3'])) && (in_array($data['proj']->groupStatus,['Waiting for Schedule Approval','Waiting for Final Schedule','Ready for Defense'])))
                    
            <section>
            <div class="col-md-12">
                    <h5>Schedule Information</h5>
                    <ul class="list1">
                            <li>
                        <h6>Date : {{date_format(new Datetime($data['schedApp'][0]->schedDate),"F j, Y")}}</h6>
                            </li>
                            <li>
                        <h6>Starting Time : {{date_format(new Datetime($data['schedApp'][0]->schedTimeStart),"g:i A")}}</h6>
                            </li>
                            <li>
                        <h6>Ending Time : {{date_format(new Datetime($data['schedApp'][0]->schedTimeEnd),"g:i A")}}</h6>
                            </li>
                            <li>
                        <h6>Place : {{$data['schedApp'][0]->schedPlace}}</h6>
                            </li>
                            <li>
                        <h6>Type of schedule : {{$data['schedApp'][0]->schedType}}</h6>
                            </li>
                            <li>
                        <h6>Status : {{$data['schedApp'][0]->schedStatus}}</h6> 
                                </li>
                    </ul>
                    </div>
                </section>
                    
                @endif
            </div> <!-- End of Schedule Information -->
        </div> <!-- End of Group Information -->   
        <!-- Project Revision Approval -->
        <?php $currentStage = new App\models\Stage; ?>
        @if((in_array($data['proj']->projPVerdictNo,['2','3'])) && (in_array($data['proj']->groupStatus,['Waiting for Project Approval','Corrected by Panel Members'])))
        <hr class="my-4">
        <div class="row">
            <div class="col-md-12">
            <h5>Project Revisions Approval</h5>
            <table class="table table-striped table-hover table-hover table-responsive-sm">
                <thead>
                    <tr class="">
                        <th>Position</th>
                        <th>Name</th>
                        <th>Approval Status</th>
                        <th>Reviewed On</th>
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
                            <td>
                                @if($pmembers->isApproved != 0)
                                {{date_format(new Datetime($pmembers->projAppTimestamp),"M d, Y - g:i A")}}
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
                <textarea id="proj_approval_comment" name="proj_comment_{{$pmember->accID}}" class="form-control" readonly>{{$pmember->projAppComment}}</textarea>
                </div>
                @endforeach
        </div> <!-- End of comment section -->
        <!-- End of Project Revision Approval -->

        <!-- Schedule Approval Information -->
        @elseif(!in_array($data['proj']->projPVerdictNo,['2','3']) && (in_array($data['proj']->groupStatus,['Waiting for Schedule Approval','Waiting for Final Schedule','Ready for Defense'])) )
        <hr class="my-4">
        <div class="row">
            <div class="col-md-12">
            <h5>Schedule Approval</h5>
            <table class="table table-striped table-hover table-hover table-responsive-sm">
                <thead>
                    <tr class="">
                        <th>Position</th>
                        <th>Name</th>
                        <th>Approval Status</th>
                        <th>Short Message</th>
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
                                <span class="badge badge-success badge-pill">Available</span>
                                @elseif($pmembers->isApproved == 2)
                                <span class="badge badge-danger badge-pill">Not Available</span>
                                @else
                                <span class="badge badge-secondary badge-pill">Waiting</span>
                                @endif  
                            </td>
                            <td>
                                <div class="form-group">
                                <textarea class="form-control">{{$pmembers->schedAppMsg}}</textarea>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            </div>
        </div> <!-- End of Schedule Approval Information -->
        @endif
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