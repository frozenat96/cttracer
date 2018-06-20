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
            <div class="col-md-12">
                <h5 id="font2">Project Name : </h5><ul class="list1"><li>{{$data['proj'][0]->projName}}
                        <a href="/my-project/{{$data['proj'][0]->projNo}}/edit" class="">Edit title</a></li></ul><br>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                    
                 
            
        

            <section>
            <h5>Content Adviser</h5>
            <ul class="list1">
                <li>
                    {{$data['adviser'][0]->accTitle}} {{$data['adviser'][0]->accFName}} {{$data['adviser'][0]->accMInitial}} {{$data['adviser'][0]->accLName}}
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
                        <h6>Stage : {{$data['proj'][0]->projStageNo}} ({{$data['proj'][0]->stageName}})</h6>
                            </li>
                            <li>
                        <h6>Group Status : {{$data['proj'][0]->groupStatus}}</h6>
                            </li>
                            <li>
                        <h6>Project Status : {{$data['proj'][0]->pVerdictDescription}}</h6>
                            </li>
                        </ul>
                </section>
            </div>
            <div class="col-md-3">
                
                @if((($data['proj'][0]->projPVerdictNo == '2') || ($data['proj'][0]->projPVerdictNo == '3')) && ($data['proj'][0]->groupStatus == 'Making Revisions'))
                <h5>Options</h5>
                <a href="#" class="btn btn-primary">Submit Document</a>
                @elseif($data['proj'][0]->groupStatus == 'Making Document')
                <h5>Options</h5>
                <a href="#" class="btn btn-primary">Submit Document</a>
                @else
                <h5>Status</h5>
                <span>Waiting for approval</span>
                @endif
            </div>
        </div>
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
                    @foreach($data['pgroup'] as $pmembers)
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
        $('#myList a').on('click', function (e) {
            e.preventDefault()
            $(this).tab('show')
        })
    </script>
@endsection