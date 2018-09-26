@extends('layouts.app')

@section('includes')

@endsection

@section('style')
@endsection

@section('content')
<div class="row" id="app">
    <div class="col-md-12 justify-align-center" id="index_content1">
        <div class="jumbotron bg1">
        @include('inc.messages')
        <h4><span class="alert bg2">PROJECT SEARCH</span></h4>
        <br class="my-4">
            <div class="row">
            <div class="col-md-12">
                <form id="form-search" method="post" action="/proj-search-results" accept-charset="UTF-8" role="search">
                    {{csrf_field()}} 
                    <div class="input-group">
                        <input type="text" class="form-control search-bar1" list="list1" name="q" placeholder="Search Projects"> 

                        @if(isset($data) && count($data))
                        <datalist id="list1" class="datalist scrollable">
                            @foreach($data as $data1)
                                <option value="{{$data1->groupName}}">
                            @endforeach
                            @foreach($data as $data2)
                                <option value="{{$data2->projName}}">
                            @endforeach
                        </datalist>
                        @endif

                        <span class="input-group-btn">
                            <button type="submit" class="btn btn-info btn-lg">
                                <span><i class="fas fa-search"></i> Search</span>
                            </button>
                        </span>  
                    </div>
                </form>
            </div>
               
            </div>
        <hr class="my-4">
        <?php $allowRevHist = new App\models\RevisionHistory; ?>
        @if(isset($data) && count($data))
        <table class="table table-sm table-striped table-hover table-responsive-sm">
            <thead>
                <tr>
                    <th scope="col">Project Name</th>
                    <th scope="col">Group Name</th>
                    <th scope="col">Project Status</th>
                    <th scope="col">Project view</th>
                    @if($allowRevHist->status==true)
                    <th scope="col">Revision History</th>
                    <th scope="col">Group History</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach($data as $proj)
                    <tr scope="row">
                        <td>
                            <span tabindex="0" class="" data-toggle="popover" data-content="{{$proj->projName}}" data-placement="top">{{(substr($proj->projName, 0, 20) . '..')}}</span>
                        </td>
                        <td>
                            <span tabindex="0" class="" data-toggle="popover" data-content="{{$proj->groupName}}" data-placement="top">{{(substr($proj->groupName, 0, 20) . '..')}}</span>
                            </td>
                        <td>
                            <span tabindex="0" class="">{{$proj->pVerdictDescription}}</span>
                        </td>
                        <td>
                        <a class="btn btn-warning" href="/projects/{{$proj->projGroupID}}" data-toggle="popover" data-content="View project details" data-placement="top">
                                    <i class="far fa-eye"></i> View Project 
                            </a>     
                        </td>
                        
                        
                        @if($allowRevHist->status==true)
                        <td>
                            <a class="btn btn-dark" href="/revision-history-search-results/{{$proj->groupName}}" data-content="View Revision History" data-toggle="popover" data-placement="top"><i class="far fa-eye"></i> View Revision History</a>
                        </td>
                        <td>
                            <a class="btn btn-primary" href="/group-history-search-results?q={{$proj->groupName}}" data-content="View Group History" data-toggle="popover" data-placement="top"><i class="far fa-eye"></i> View Group History</a>
                        </td>
                        @endif
                       
                    </tr>
                @endforeach
            </tbody>

        </table>
        {!! $data->render() !!}
        @else
        <table class="table"><tr><td><span>No results found</span></td></tr></table>
        @endif
        <!--
        <project-search></project-search>
        -->
        </div>
    </div>
    
</div>

@section('paginator')
    <!--
    <div class="container justify-align-center">
    </div> 
    -->
@endsection

@endsection