@extends('layouts.app')

@section('style')

@endsection

@section('content')
<div class="row">
    <div class="col-md-12 justify-align-center" id="index_content1">
        <div class="jumbotron bg1">
        @include('inc.messages')
        <h4><span class="alert bg2">APPROVE SCHEDULES</span></h4>
        <br class="my-4">
            <div class="row">
                <div class="col-md-10">
            <form method="post" action="/schedule-search-results" accept-charset="UTF-8" role="search">
                {{csrf_field()}} 
                <div class="input-group">
                    <input type="text" class="form-control" name="q" placeholder="Search Groups"> 
                    <span class="input-group-btn">
                        <button type="submit" class="btn btn-info btn-lg">
                            <span><i class="fas fa-search"></i> Search</span>
                        </button>
                    </span>
                    
                </div>
            </form>
                </div>
                <div class="col-md-1">
                        <a href="/schedule-settings/create" class="btn btn-success btn-lg" data-toggle="popover" data-content="Add a new schedule setting" data-placement="top"><span><i class="fas fa-plus"></i> Add</span></a>
                </div>
            </div>
            <hr class="my-4">
            @if(isset($data) && count($data))
            <?php 
            $model = new App\models\Group;
            ?> 
                @foreach($data as $sched)
                <div class="form-row">
                    <div class="form-group col-md-3">
                        <div>
                            {{$sched->groupName}} {{$sched->groupStatus}}
                        </div>
                    </div>  
                    <div class="form-group col-md-3">
                        <span>
                            Panel Member
                            {{$sched->accFName}} {{$sched->accLName}} @if($sched->panelIsChair):: Chair
                            @endif
                        </span>
                    </div>
                    <div class="form-group col-md-3">

                    </div>  
                        
                </div>
                @endforeach
            {!! $data->render() !!}
            @else
            <table class="table"><tr><td><span>No results found</span></td></tr></table>
            @endif
        </div>
    </div>
</div>
@endsection