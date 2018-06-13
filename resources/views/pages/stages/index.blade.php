@extends('layouts.app')

@section('style')

@endsection

@section('content')
<div class="row">
    <div class="col-md-12 justify-align-center" id="index_content1">
        <h4>STAGE SETTINGS</h4>
            <div class="row">
                <div class="col-md-10">
            <form method="post" action="/stage-search-results" accept-charset="UTF-8" role="search">
                {{csrf_field()}} 
                <div class="input-group">
                    <input type="text" class="form-control" name="q" placeholder="Search Stages"> 
                    <span class="input-group-btn">
                        <button type="submit" class="btn btn-danger">
                            <span><i class="fas fa-search"></i> Search</span>
                        </button>
                    </span>
                    
                </div>
            </form>
                </div>
                <div class="col-md-1">
                        <a href="/stage-settings/create" class="btn btn-primary"><span><i class="fas fa-plus"></i> Add</span></a>
                </div>
            </div>
            @if(isset($data) && count($data))
            <table class="table table-striped table-hover table-sm">
                <thead>
                    <tr>
                        <th scope="col">Stage No.</th>
                        <th scope="col">Stage Name</th>
                        <th scope="col">Defense Duration</th>
                        <th scope="col">Panel Members</th>
                        <th scope="col">Edit</th>
                        <th scope="col">Delete</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $stage)
                        <tr scope="row">
                            <td>{{$stage->stageNo}}</td>
                            <td>{{$stage->stageName}}</td>
                            <td>{{$stage->stageDefDuration}}</td>
                            <td>{{$stage->stagePanel}}</td>
                            <td><a href="#" class="btn btn-warning"><span><i class="far fa-edit"></i> Edit</span></a></td>
                            <td><a href="#" class="btn btn-danger"><span><i class="fas fa-minus"></i> Delete</span></a></td>
                        </tr>
                    @endforeach
                </tbody>

            </table>
            {!! $data->render() !!}
            @else
            <table class="table"><tr><td><span>No results found</span></td></tr></table>
            @endif
    </div>
</div>
@endsection