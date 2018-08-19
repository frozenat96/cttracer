@extends('layouts.app-no-popover')

@section('style')
    .list-group-item {
        background-color: rgba(0,0,0,0);
        border: none;
    }
    .card1 {
        border: none;
    }
@endsection

@section('content')
<?php $grpModel = new App\models\Group; $userModel = new App\User; $user1=$userModel->current();?>
<div class="row">
    <div class="col-md-12 justify-align-center" id="index_content1">
        <div class="jumbotron bg1">
        @include('inc.messages')
        <h4><span class="alert bg2">APPROVE SCHEDULES</span></h4>
        <br class="my-4">
            <div class="row">
                <div class="col-md-12">
            <form id="form-search" method="post" action="/approve-schedules-search-results" accept-charset="UTF-8" role="search">
                {{csrf_field()}} 
                <div class="input-group">
                    <input type="text" class="form-control search-bar1" name="q" list="list1" placeholder="Search Groups"> 

                    @if(isset($data) && count($data))
                    <datalist id="list1" class="datalist scrollable">
                        @foreach($data as $data1)
                            <option value="{{$data1->groupName}}">
                        @endforeach
                        @foreach($data as $data2)
                            <option value="{{$data1->projName}}">
                        @endforeach
                    </datalist>
                    @endif

                    <span class="input-group-btn">
                        <button type="submit" class="btn btn-info btn-lg">
                            <span><i class="fas fa-search"></i> Search</span>
                        </button>
                    </span>
                    
                </div>
            </form>
                </div>
            </div>
            <hr class="my-4">
            @if(isset($data) && count($data))
            <?php 
            $model = new App\models\Group;
            ?> 
                @foreach($data as $data1)
                    @include('inc.prefab-schedApp')
                @endforeach
            {!! $data->render() !!}
            @else
            <table class="table"><tr><td><span>No results found</span></td></tr></table>
            @endif
        </div>
    </div>
</div>
@endsection

@section('includes2')
<script type="text/javascript">
$(function () {
  $('[data-toggle="popover"]').popover()
})
$(document).ready(function () {
    $('.popover-dismiss').popover({
    trigger: 'focus'
    })
});
</script>
@endsection