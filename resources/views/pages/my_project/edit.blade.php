@extends('layouts.app')

@section('style')

@endsection

@section('content')
<div class="row">
    <div class="col-md-12 justify-align-center" id="index_content1">
        <h4>MY PROJECT</h4>
        {{$data}}
        {!!Form::open(['action' => ['MyProjController@update',$data->projNo], 'method' => 'POST']) !!}
            <div class="form-group">
                {{Form::label('project_name','Project Name')}}
                {{Form::text('project_name',$data->projName,['class'=>'form-control', 'placeholder'=>'Project Name'])}}
            </div>
            {{Form::hidden('_method','PUT')}}
            {{Form::submit('Update',['class'=>'btn btn-primary'])}}
        {!!Form::close() !!}
    </div>
</div>
@endsection