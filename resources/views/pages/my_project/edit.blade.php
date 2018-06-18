@extends('layouts.app')

@section('style')

@endsection

@section('content')
<div class="row">
    <div class="col-md-12 justify-align-center" id="index_content1">
        <div class="row justify-content-center">
            <div class="col-md-9 jumbotron bx2">
                @include('inc.messages')
                <legend class="text-left"><span class="alert bg2">MY PROJECT EDIT</span><hr class="my-4"></legend>
                {{$data}}
                {!!Form::open(['action' => ['MyProjController@update',$data->projNo], 'method' => 'POST']) !!}
                <div class="form-group">
                        <label for="project_name">Project Name</label>
                        <input name="project_name" type="text" class="form-control" id="given_name" placeholder="Project Name" required="yes" value="{{$data->projName}}"> 
                    </div>
                    <input type="hidden" name="_method" value="PUT">
                    <div class="form-group text-right">
                        <hr class="my-4">
                        <button type="reset" class="btn btn-info btn-lg">
                        <span><i class="fas fa-recycle"></i> Reset Values</span>
                        </button>
                        <button type="submit" class="btn btn-success btn-lg">
                            <span><i class="fas fa-plus"></i> Update</span>
                        </button>
                    </div>
                {!!Form::close() !!}
            </div>
        </div>
    </div>
</div>
@endsection
