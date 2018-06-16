@extends('layouts.app')

@section('style')

@endsection

@section('content')
<div class="row">
    <div class="col-md-12 justify-align-center" id="index_content1">
        @include('inc.messages')
        <div class="row">
        <h4>MY PROJECT</h4>
        </div>
       
        <div class="row">
            <div class="col-md-4">
                    
                 
            

            <section>
            <h5>Content Adviser</h5>
            <p>
                    {{$data['adviser'][0]->accTitle}} {{$data['adviser'][0]->accFName}} {{$data['adviser'][0]->accMInitial}} {{$data['adviser'][0]->accLName}}
            </p>
            </section>
            <section>
                <h5>Group Members</h5>
                <ul class="list-group">
                @foreach($data['group'] as $members)
                    <li class="list-item">{{$members->accFName}} {{$members->accMInitial}} {{$members->accLName}}</li>
                @endforeach
                </ul>
            </section>
            <a href="/my-project/{{$data['proj'][0]->projNo}}/edit" class="btn btn-primary">EDIT</a>
            </div>

            <div class="col-md-8">
                <section>
                        <h5>Project Name : {{$data['proj'][0]->projName}}</h5>
                        <h5>Stage : {{$data['proj'][0]->projStageNo}} ({{$data['proj'][0]->stageName}})</h5>
                        <h5>Project Status : {{$data['proj'][0]->pVerdictDescription}}</h5>
                </section>
                <section>
                        @if(($data['proj'][0]->pVerdictDescription == 'Minor Revisions') || ($data['proj'][0]->pVerdictDescription == 'Major Revisions'))
                        <a href="#" class="btn btn-primary">Submit Document</a>
                        @elseif(($data['proj'][0]->pVerdictDescription == 'Submitted Document For Approval'))
                            <a href="#" class="btn btn-primary">Unsubmit </a>
                        @endif
                </section>

                <section>
                        <h5>Panel Members</h5>
                   

                        <table class="table table-striped table-hover">
                                <thead>
                                    <tr class="table-danger">
                                        <th>Position</th>
                                        <th>Name</th>
                                        <th>Approval Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($data['pgroup'] as $pmembers)
                                        <tr class="table-danger">
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
                                                <span class="badge badge-success">  Approved </span>
                                                @elseif($pmembers->isApproved == 2)
                                                <span class="badge badge-danger">  Returned with Corrections </span> <a href="{{($pmembers->revisionLink)}}" target="_blank"><i class="fas fa-download"></i> download</a>
                                                @else
                                                <span class="badge badge-warning">  Waiting </span>
                                                @endif  
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                    
                        </table>
                    </section>
            </div>
            
        </div>
        <div class="row">
            <div class="col">
                   <!-- {{$data['proj']}} -->
            </div>
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