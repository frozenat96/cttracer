@extends('layouts.app')

@section('style')

@endsection

@section('content')
<div class="row">
    <div class="col-md-12 justify-align-center" id="index_content1">
        <h4>MY PROJECT</h4>
        
        {{$data['proj']}}
        <div class="well">
            <h5>Members</h5>
            <ul class="list-group">
            @foreach($data['group'] as $members)
                <li class="list-item">{{$members->accFName}} {{$members->accMInitial}} {{$members->accLName}}</li>
            @endforeach
            </ul>
        </div>
        <a href="/my-project/{{$data['proj'][0]->projNo}}/edit" class="btn btn-primary">EDIT</a>
    </div>
</div>
@endsection