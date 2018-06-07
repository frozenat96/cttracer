@extends('layouts.app')

@section('style')

@endsection

@section('content')
<div class="row">
    <div class="col-md-12 justify-align-center" id="index_content1">
        <h4>MY PROJECT</h4>
        
        {{$data}}
     
        @foreach($data as $proj)
            {{$proj->projName}}
        @endforeach
        <a href="/my-project/{{$data[0]->projNo}}" class="btn btn-primary">EDIT</a>
    </div>
</div>
@endsection