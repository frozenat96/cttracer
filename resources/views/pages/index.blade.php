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
        <div class="col-md-12">
            @include('inc.messages')
        </div>
        
        <div class="col-md-12">
            <img src="{{asset('img/design/index-background/index-background.png')}}" class="img-fluid" alt="Responsive image">
        </div>
</div>
<div class="row">
<div class="col-md-12 jumbotron" id="index_content1">
        @if(session('status'))
        <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif
        <div class="row">
            <div class="col-md-12">
                <center><h4>VISION</h4></center>
                <p class="jsfy">
                    A leading Christian institution committed to total human development for the well-being of society and environment.
                </p>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <center><h4>MISSION</h4></center>
                <p class="jsfy">		
                    Infuse into the academic learning the Christian faith 
                    anchored on the gospel of Jesus Christ.Provide an 
                    environment where Christian fellowship and relationship can 
                    be nurtured and promoted.Provide opportunities for growth 
                    and excellence in every dimension of the University life in 
                    order to
                    strengthen competence, character and faith.Instill in all 
                    members of the University community an enlightened social 
                    consciousness and a deep
                    sense of justice and compassion.Promote unity among peoples 
                    and contribute to national development. 
                </p>
            </div>
        </div>
        <div class="row">
            <div class="col-sm">
            <img src="{{asset('img/design/logo/SU_logo64px.png')}}" style="float:right;">
            </div>
        
            <div class="col-sm">
                <div class="row">
                    <div class="col-md-3">
        
                    <img src="{{asset('img/design/logo/ccs_logo.png')}}" style="width:100px;float: right;">
                    </div>
                    <div class="col-md-6">
                        SILLIMAN UNIVERSITY COLLEGE OF COMPUTER STUDIES
                </div>
            </div>
        </div>
    </div>				
</div>
</div>
@endsection
