@extends('layouts.app')

@section('style')
    #index_content1 {
        background-color: #FFC694;
        padding: 20px;
    }
    #index_content1 p {
        
    }
    #index_content1 h4 {
        font-weight: bold;
    }

    #indexbg {
        
    }
@endsection

@section('content')

<div class="row">
<div class="col-md-12 jumbotron" id="index_content1">
        @if(session('status'))
        <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif
        <div class="row">
            <div class="col-md-12 text-justify">
                <center><h4>Terms and Conditions</h4></center>
                @include('inc.terms-content')
            </div>	
        </div>
</div>
@endsection
