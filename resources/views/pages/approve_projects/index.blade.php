@extends('layouts.app')

@section('style')
@endsection

@section('content')
<div class="row" id="app">
    <div class="col-md-12 justify-align-center" id="index_content1">
        <div class="jumbotron bg1">
        @include('inc.messages')
        <h4><span class="alert bg2">APPROVE PROJECTS</span></h4>
        <br class="my-4">
        <div class="row">
            <div class="col-md-12">
                <form action="/app-proj-search-results" method="POST" role="search">
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
        @if(count($data) > 0)
        
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th scope="col">Project No.</th>
                    <th scope="col">Project Name</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data as $project)
                    <tr scope="row">
                        <td>{{$project->projNo}}</td>
                        <td>{{$project->projName}}</td>
                    </tr>
                @endforeach
            </tbody>

        </table>
        {!! $data->render() !!}
        @else
        <span>No results found</span>
        @endif
        <!--
        <project-search></project-search>
        -->
    </div>
    
</div>
@endsection