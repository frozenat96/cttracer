@extends('layouts.nonav')
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
        @include('inc.messages')
        <div class="card col-md-6 bx2" id="c2">
            <div class="card-header bg-transparent">LOGIN</div>
                <div class="card-body">
                    <div class="row justify-content-center">
                        <img src="{{asset('img/design/logo/logo2.png')}}" style="width: 300px;height: 250px;">
                    </div>
                    <div class="row justify-content-center ">
                        <div class="btn-img"> 
                            <div class="img-wrap">
                            <a href="/login/google">
                            <img id="gLogin" src="{{asset('img/design/buttons/google_sign_in.png')}}">
                            </a>
                            </div>
                        </div>
                    </div>
                </div>
        </div>
    </div>	
@endsection
