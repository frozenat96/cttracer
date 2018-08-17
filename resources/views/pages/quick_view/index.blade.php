@extends('layouts.app')

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
<div class="row">
    <div class="col-md-12 justify-align-center" id="index_content1">
        <div class="jumbotron bg1">
        <div class="row">
            <div class="col">@include('inc.messages')</div>
        </div>
        <h4><span class="alert bg2">SEARCH GROUPS</span></h4>
        <br class="my-4">
            <div class="row">
                <div class="col-md-12">
            <form method="post" action="/quick-view-search-results" accept-charset="UTF-8" role="search" id="form-search">
                {{csrf_field()}} 
                <div class="input-group">
                    <input type="text" class="form-control" id="query" name="q" list="groups1" placeholder="Search Groups"> 
                    <span class="input-group-btn">
                        <button type="submit" class="btn btn-info btn-lg">
                            <span><i class="fas fa-search"></i> Search</span>
                        </button>
                    </span>
                    <?php 
                    $g1 = DB::table('group')->pluck('group.groupName');
                    $p1 = DB::table('project')->pluck('project.projName'); 
                    ?>
                    <datalist id="groups1" class="datalist scrollable">
                        @foreach($g1 as $g2)
                            <option value="{{$g2}}">
                        @endforeach
                        @foreach($p1 as $p2)
                            <option value="{{$p2}}">
                        @endforeach
                    </datalist>
                    
                </div>
            </form>
                </div>
                
            </div>
            <div class="row justify-content-left">
                <div class="form-group col-sm-12 col-md-6">
                    <table class="table-responsive-sm table-responsive-md" style="margin-top:10px;">
                        <tr>
                            <td>
                    <span style="font-size:1em;padding-right:5px;">Select Group Status</span>
                            </td>
                            <td>
                    <select id="search1" class="form-control">
                        <option value="" style="visibility:0;"></option>
                        <option value="">None</option>
                        <option value="Waiting for Submission">Waiting for Submission</option>
                        <option value="Submitted to Content Adviser">Submitted to Content Adviser</option>
                        <option value="Corrected by Content Adviser">Corrected by Content Adviser</option>
                        <option value="Waiting for Schedule Request">Waiting for Schedule Request</option>
                        <option value="Waiting for Schedule Approval">Waiting for Schedule Approval</option>
                        <option value="Waiting for Final Schedule">Waiting for Final Schedule</option>
                        <option value="Ready for Defense">Ready for Defense</option>
                        <option value="Waiting for Project Approval">Waiting for Project Approval</option>
                        <option value="Corrected by Panel Members">Corrected by Panel Members</option>
                        <option value="Ready for Next Stage">Ready for Next Stage</option>
                        <option value="Waiting for Project Completion">Waiting for Project Completion</option>
                        <option value="Finished">Finished</option>
                    </select>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            <hr class="my-4">
            @if(isset($data) && count($data))
            <?php 
            $model = new App\models\Group;
            ?>
             @foreach($data as $grp)
                <div class="form-row card bx2 card1 jumbotron">
                    <div class="col-md-12"> 
                        <table class="table table-sm table-responsive-sm table-responsive-md">
                                <thead>
                                    <tr>
                                        <th scope="col">Group Details</th>
                                        <th scope="col">Project Details</th>
                                        <th scope="col">Options</th>
                                    </tr>
                                </thead>
                                <tbody>
                        <tr scope="row">
                            <td>
                                <table class="table-sm table-hover table-striped">
                                <tr>
                                    <td>
                                        Group Name : <span data-content="{{$grp->groupName}}" data-toggle="popover" data-placement="top">{{(substr($grp->groupName, 0, 25) . '..')}}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Group Type : {{$grp->groupType}}</td>
                                </tr>
                                <tr>
                                    <td>Group Status : {{$grp->groupStatus}}</td>
                                </tr>
                                <tr>
                                    <td>Group Content Adviser : <span data-content="{{$grp->accTitle}} {{$grp->accFName}} {{$grp->accMInitial}} {{$grp->accLName}}" data-toggle="popover" data-placement="top">{{$grp->accLName}}, {{$model->initials($grp->accFName)}}</span></td>
                                </tr>
                                
                                </table>
                            </td>
                            
                            <td>
                                <table class="table-sm table-hover table-striped">
                                <tr><td>
                                Project View : <a href="/projects/{{$grp->groupID}}" class="btn btn-warning" title="{{$grp->projName}}" data-toggle="popover" data-content="View project details" data-placement="top"><span><i class="fas fa-project-diagram"></i></span> {{(substr($grp->projName, 0, 10) . '..')}}</a>
                                </td></tr>
                                <tr><td>
                                Project Stage : {{$grp->stageName}}
                                </td></tr>
                                <tr><td>
                                Panel Verdict : {{$grp->pVerdictDescription}}
                                </td></tr>
                                </table>
                            </td>

                            <td>
                                    <?php 
                                    $forCompletion = false;
                                    $stg = DB::table('stage')
                                    ->join('project','project.projStageNo','=','stage.stageNo')
                                    ->join('group','group.groupID','=','project.projGroupID')
                                    ->select('stage.stageNo')
                                    ->first();
                                    $totalStage = DB::table('stage')->count();
                                    if(($stg->stageNo + 1) > $totalStage) {
                                        $forCompletion = true;
                                    }
                                    ?>
                                    <table class="table-sm">
                                    @if(!(in_array($grp->projPVerdictNo,['2','3','7'])) && in_array($grp->groupStatus,['Waiting for Schedule Request']))
                                    <tr><td>
                                    <a href="{!! route('request-schedule', ['id'=>$grp->groupID]) !!}" class="btn btn-success btn-sm" data-toggle="popover" data-content="Create schedule" data-placement="top"><span><i class="far fa-calendar-plus"></i></span> Create Schedule</a>
                                    </td></tr>
                                    @elseif(!(in_array($grp->projPVerdictNo,['2','3','7'])) && (in_array($grp->groupStatus,['Waiting for Schedule Approval','Waiting for Final Schedule','Ready for Defense'])))
                                    <tr><td>
                                    <a href="/quick-view/{{$grp->groupID}}/edit" class="btn btn-success btn-sm" data-toggle="popover" data-content="Modify schedule" data-placement="top"><span><i class="far fa-calendar-plus"></i></span> Modify Schedule</a>
                                    </td></tr>
                                    @endif
                                    <tr><td>
                                        <a href="/groups/{{$grp->groupID}}/edit" class="btn btn-secondary btn-sm" data-toggle="popover" data-content="Modify Group Details" data-placement="top"><span><i class="far fa-edit"></i></span> Modify Group Details</a>
                                    </td></tr>
                                    @if((in_array($grp->projPVerdictNo,['2','3'])) && (in_array($grp->groupStatus,['Waiting for Project Approval','Corrected by Panel Members'])))
                                    <tr><td>
                                    <a href="{!! route('modifyProjApp', ['id'=>$grp->groupID]) !!}" class="btn btn-info btn-sm" data-toggle="popover" data-content="Modify the group's Project Approval Details." data-placement="top"><span><i class="far fa-edit"></i></span> Modify Project Approval</a>
                                    </td></tr>
                                    @endif
                                    @if(!(in_array($grp->projPVerdictNo,['2','3','7'])) && (in_array($grp->groupStatus,['Waiting for Final Schedule'])))
                                    <tr><td>
                                    <form action="{!! action('QuickViewController@finalizeSchedule') !!}" method="post" class="form1">{{csrf_field()}}
                                    <button type="submit" name="grp" value="{{$grp->groupID}}" class="btn btn-info btn-sm" data-toggle="popover" data-content="The group's schedule request is ready to be finalized." data-placement="top" onclick="return confirm('Are you sure');"><span><i class="far fa-edit" ></i></span> Finalize Schedule</button>
                                    <input type="hidden" name="_method" value="PUT">
                                    </form>
                                    </td></tr>

                                    @elseif(in_array($grp->groupStatus,['Waiting for Project Completion']))
                                    <tr><td>
                                    {!!Form::open(['action' => ['QuickViewController@setToProjComplete'], 'method' => 'POST','class'=>'form1']) !!}
                                    <button type="submit" name="grp" value="{{$grp->groupID}}" class="btn btn-info btn-sm" data-toggle="popover" data-content="The group has finished the project." data-placement="top" onclick="return confirm('Are you sure');"><span><i class="fas fa-forward" ></i></span> Set to Project Complete</button>
                                    <input type="hidden" name="_method" value="PUT">
                                    {!!Form::close() !!}
                                    </td></tr>   
                                    @endif
                                    
                                    @if(!in_array($grp->groupStatus,['Finished']))
                                    <tr><td>
                                    {!!Form::open(['action' => ['QuickViewController@nextStage'], 'method' => 'POST','class'=>'form1']) !!}
                                    <button type="submit" name="grp" value="{{$grp->groupID}}" class="btn btn-danger btn-sm" data-toggle="popover" data-content="The group is ready for the next stage." data-placement="top" onclick="return confirm('Are you sure');"><span><i class="fas fa-forward" ></i></span> Set to Next Stage</button>
                                    <input type="hidden" name="_method" value="PUT">
                                    {!!Form::close() !!}
                                    </td></tr> 
                                    @endif

                                    @if(!in_array($grp->groupStatus,['Finished']))
                                    <tr><td>
                                        <a href="{!! route('setPanelVerdictIndex', ['groupID'=>$grp->groupID]) !!}" class="btn btn-dark btn-sm" data-toggle="popover" data-content="Set the group's panel verdict" data-placement="top"><span><i class="far fa-edit"></i></span> Set Panel Verdict</a>
                                    </td></tr>
                                    
                                    @endif
                                    </table>
                                </td>

                        </tr>
                        </tbody>
                        </table>
                    </div>
                </div>
                    @endforeach
                </tbody>

            </table>
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
$('#search1').select2();
$(document).ready(function () {
    $('#search1').change(function () {
        x = $('#search1').val();
        $('#query').val(x);
        $('#form-search').submit();
    });
});

</script>
@endsection