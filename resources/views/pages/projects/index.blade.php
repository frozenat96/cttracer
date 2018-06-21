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
                <form method="post" action="/proj-search-results" accept-charset="UTF-8" role="search">
                    {{csrf_field()}} 
                    <div class="input-group">
                        <input type="text" class="form-control" name="q" placeholder="Search Projects"> 
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
        @if(isset($data) && count($data))
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th scope="col">Project Name</th>
                    <th scope="col">Group Name</th>
                    <th scope="col">Project Status</th>
                    <th scope="col">Project view</th>
                    <th scope="col">Edit</th>
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
                        <a class="btn btn-warning" href="/projects/{{$proj->projGroupNo}}" data-toggle="popover" data-content="View project details" data-placement="top">
                                    <i class="far fa-eye"></i> View Project 
                            </a>     
                        </td>
                        <td><a href="#" class="btn btn-secondary" data-toggle="popover" data-content="Edit project details" data-placement="top"><span><i class="far fa-edit"></i> Edit</span></a>
                        </td>
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