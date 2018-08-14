@extends('layouts.app')

@section('style')
    .list-group-item {
        background-color: rgba(0,0,0,0);
        border: none;
    }
    .card1 {
        border: none;
    }

    .card, .card-body {
        background-color: rgba(0,0,0,0);
        border:none;
    }
@endsection

@section('content')
<?php $grpModel = new App\models\Group; $userModel = new App\User; $user1=$userModel->current();?>
<div class="row">
    <div class="col-md-12 justify-align-center" id="index_content1">
        <div class="jumbotron bx2">
        @include('inc.messages')
        <h4><span class="alert bg2">DASHBOARD</span></h4>
        <br class="my-4">
            <hr class="my-4">
            @if(isset($data) && count($data))
            <?php 
            $model = new App\models\Group;
            ?> 
            <div class="row">
                <div class="col-md-4"> <!-- card -->
                    <div class="card my-card1">
                        <div class="card-body">
                            <div class="row bg-danger text-light" style="border-top-left-radius:5px;border-top-right-radius:5px;">
                            <div class="col-8" style="padding-top:10px;">
                                <h6>Total Schedule Approval Requests</h6>
                            </div>
                            <div class="col-4">
                                    @if($data['sched'])
                                    <h1 class="card-title text-right"><span class="badge badge-pill badge-danger">{{$data['sched']}}</span></h1>
                                    @endif
                            </div>
                            </div>
                            <div class="row bg-light" style="border-bottom-left-radius:5px;border-bottom-right-radius:5px;">
                                <div class="col-12" style="padding-top:5px;">
                                    @if($data['sched'])
                                    <p class="card-text blockquote-footer">You have a total of {{$data['sched']}} Schedules to be approved</p>
                                    @else
                                    <p class="card-text blockquote-footer">No schedules to be approved</p>
                                    @endif
                                </div>  
                                <div class="col-12">
                                    <div class="text-right" style="padding-bottom:10px;padding-top:10px;">
                                        <a href="/nd/NotifyPanelOnSchedRequest/SchedAppController@search/null" class="btn btn-primary">View</a>
                                    </div>
                                </div>  
                            </div>
                        </div>
                    </div>
                </div> <!-- End of card -->

                <div class="col-md-4"> <!-- card -->
                    <div class="card my-card1">
                        <div class="card-body">
                            <div class="row bg-success text-light" style="border-top-left-radius:5px;border-top-right-radius:5px;">
                            <div class="col-8" style="padding-top:10px;">
                                <h6>Total Advised Group Submissions</h6>
                            </div>
                            <div class="col-4">
                                    @if($data['adv'])
                                    <h1 class="card-title text-right"><span class="badge badge-pill badge-success">{{$data['adv']}}</span></h1>
                                    @endif
                            </div>
                            </div>
                            <div class="row bg-light" style="border-bottom-left-radius:5px;border-bottom-right-radius:5px;">
                                <div class="col-12" style="padding-top:5px;">
                                        @if($data['adv'])
                                        <p class="card-text blockquote-footer">You have a total of {{$data['adv']}} Advised Groups that had submitted their documents</p>
                                        @else
                                        <p class="card-text blockquote-footer">No advised groups have submitted their document yet.</p>
                                        @endif
                                </div>  
                                <div class="col-12">
                                    <div class="text-right" style="padding-bottom:10px;padding-top:10px;">
                                        <a href="/nd/NotifyAdviserOnSubmission/AdvisedGroupsController@search/null" class="btn btn-primary">View</a>
                                    </div>
                                </div>  
                            </div>
                        </div>
                    </div>
                </div> <!-- End of card -->

                <div class="col-md-4"> <!-- card -->
                    <div class="card my-card1">
                        <div class="card-body">
                            <div class="row bg-primary text-light" style="border-top-left-radius:5px;border-top-right-radius:5px;">
                            <div class="col-8" style="padding-top:10px;">
                                <h6>Total Project Approval Requests</h6>
                            </div>
                            <div class="col-4">
                                    @if($data['proj'])
                                    <h1 class="card-title text-right"><span class="badge badge-pill badge-primary">{{$data['proj']}}</span></h1>
                                    @endif
                            </div>
                            </div>
                            <div class="row bg-light" style="border-bottom-left-radius:5px;border-bottom-right-radius:5px;">
                                <div class="col-12" style="padding-top:5px;">
                                        @if($data['proj'])
                                        <p class="card-text blockquote-footer">You have a total of {{$data['proj']}} projects to be approved</p>
                                        @else
                                        <p class="card-text blockquote-footer">No projects to be approved</p>
                                        @endif
                                </div>  
                                <div class="col-12">
                                    <div class="text-right" style="padding-bottom:10px;padding-top:10px;">
                                        <a href="/nd/NotifyPanelOnProjectApproval/ProjAppController@search/null" class="btn btn-primary">View</a>
                                    </div>
                                </div>  
                            </div>
                        </div>
                    </div>
                </div> <!-- End of card -->


            </div>
            @else
            <table class="table"><tr><td><span>No results found</span></td></tr></table>
            @endif
        </div>
    </div>
</div>
@endsection

@section('includes2')
<script type="text/javascript">
$(document).ready(function () {

});
</script>
@endsection