@extends('layouts.noall')
@section('includes')
<link rel="stylesheet" href="{{asset('css/login.css')}}">
@endsection
@section('style')
    .card {
        border:none;
    }
@endsection
@section('content')

<div class="row justify-content-md-center align-items-center">
    <div class="col-md-6 bx2 jumbotron">
        @include('inc.messages')
        <a href="login/google" class="btn btn-default">Login</a>
    </div>
</div>	
@endsection
