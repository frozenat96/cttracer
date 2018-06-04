@extends('layouts.app')
@section('includes')
<link rel="stylesheet" href="{{asset('css/login.css')}}">
@endsection

@section('content')
@if($errors->any())
<div class="row justify-content-md-center">
    <h4>{{$errors->first()}}</h4>
</div>
@endif
<div class="row justify-content-md-center align-items-center">
        <div class="card col-md-6 bg2" id="c2">
            <div class="card-header bg-transparent border-dark">LOGIN</div>
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
