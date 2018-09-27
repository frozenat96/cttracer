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
<div class="container-fluid">
<div class="row">
    <div class="col-md-12">
        @include('inc.messages')
    </div>
</div>
<div class="row justify-content-md-center align-items-center">
        <div class="card col-md-6 bx2" id="c2">
            <div class="card-header bg-transparent">LOGIN</div>
                <div class="card-body">
                    <div class="row justify-content-center">
                        <img src="{{asset('img/design/logo/logo2.png')}}" style="width: 250px;height: 250px;" class="img-fluid">
                    </div>
                    <div class="row justify-content-center ">
                        <div class="btn-img"> 
                            <div class="img-wrap">
                            <a href="/login/google">
                            <img id="gLogin" src="{{asset('img/design/buttons/google_sign_in.png')}}" class="img-fluid">
                            </a>
                            </div>
                        </div>
                    </div>
                </div>
        </div>
    </div>	
</div>
@endsection