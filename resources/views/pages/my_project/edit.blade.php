@extends('layouts.app')

@section('style')

@endsection

@section('content')
<div class="row">
    <div class="col-md-12 justify-align-center" id="index_content1">
        @include('inc.messages')
        <h4>MY PROJECT</h4>
        {{$data}}
        {!!Form::open(['action' => ['MyProjController@update',$data->projNo], 'method' => 'POST']) !!}
        <div class="form-group">
                <label for="project_name">Project Name</label>
                <input name="project_name" type="text" class="form-control" id="given_name" placeholder="Project Name" required="yes" value="{{$data->projName}}">
            </div>
            <input type="hidden" name="_method" value="PUT">
            <input type="submit" class="btn btn-primary" value="Update">
        {!!Form::close() !!}
    </div>
</div>
@endsection
