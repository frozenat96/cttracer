@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-12 justify-align-center" id="index_content1">
        <div class="jumbotron bg1">
        @include('inc.messages')
        <h4><span class="alert bg2">GROUP HISTORY</span></h4>
        <br class="my-4">
            <div class="row">
                <div class="col-md-12">
            <form id="form-search" method="post" action="/group-history-search-results" accept-charset="UTF-8" role="search">
                {{csrf_field()}} 
                <div class="input-group">
                <input type="text" class="form-control search-bar1" list="groups1" name="q" value="{{isset($q) ? $q : ''}}" placeholder="Search Groups"> 

                    <?php 
                    $g1 = DB::table('group')->pluck('group.groupName');
                    $p1 = DB::table('project')
                    ->join('group','group.groupID','=','project.projGroupID')
                    ->where('project.projPVerdictNo','!=','7')
                    ->pluck('project.projName'); 
                    ?>
                    <datalist id="groups1" class="datalist scrollable">
                        @foreach($g1 as $g2)
                            <option value="{{$g2}}">
                        @endforeach
                        @foreach($p1 as $p2)
                            <option value="{{$p2}}">
                        @endforeach
                    </datalist>


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
            <?php $grpModel = new App\models\Group; $user=DB::table('account')
            ->join('account_type','account_type.accTypeNo','=','account.accType')
            ->select('account.*','account_type.*')
            ->where('account.accID','=',Auth::user()->getId())->first();?>

            <table class="table table-striped table-hover table-sm table-responsive-sm table-responsive-md">
                <thead>
                    <tr>
                        <th scope="col"><small><b>Group name</b></small></th>
                        <th scope="col"><small><b>Project Name</b></small></th>
                        <th scope="col"><small><b>Project Type</b></small></th>
                        <th scope="col"><small><b>Activity</b></small></th>
                        <th scope="col"><small><b>Revision History</b></small></th>
                        <th scope="col"><small><b>Timestamp</b></small></th>
                        <th scope="col"><small><b>Added by</b></small></th>
                        @if($user->accType=='1')
                        <th scope="col"><small><b>Delete</b></small></th>
                        <th scope="col"><small><b>Delete All</b></small></th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $d)
                        <tr scope="row">
                            <td><small><span data-content="{{$d->groupHGroupName}}" data-toggle="popover" data-placement="top">{{(substr($d->groupHGroupName, 0, 16) . '..')}}</span></small></td>
                            <td><small><span data-content="{{$d->groupHProjName}}" data-toggle="popover" data-placement="top">{{(substr($d->groupHProjName, 0, 16) . '..')}}</span></small></td>
                            <td><small>{{$d->groupHProjType}}</small></td>
                            <td><textarea style="font-size:12px;" rows="2" cols="50" readonly>{{$d->groupHActivity}}</textarea></td>
                            <td>
                                <a class="btn btn-dark" href="/revision-history-search-results/{{$d->groupHGroupName}}" target="_blank" data-content="View Revision History" data-toggle="popover" data-placement="top"><i class="far fa-eye"></i> View</a>
                            </td>
                            <td>
                                <small>{{date_format(new Datetime($d->groupHTimestamp),"M-d-Y")}}</small><br><small>{{date_format(new Datetime($d->groupHTimestamp),"g:i A")}}</small>
                            </td>
                            <td><small>{{$d->groupHAddedBy}}</small></td>
                            @if($user->accType=='1')
                        
                            <td>
                                {!!Form::open(['action' => ['GrpHistoryController@destroy',$d->groupHistID], 'method' => 'POST','class'=>'form1']) !!}
                                <button  type="submit" class="btn btn-danger" name="submit" data-toggle="popover" data-content="Delete this data in the group history" data-placement="top" onclick="return confirm('Are You Sure?')"><span><i class="fas fa-minus"></i></span></button>
                                <input type="hidden" name="_method" value="DELETE">
                                {!!Form::close() !!}
                            </td>
                            <td>
                                {!!Form::open(['action' => ['GrpHistoryController@deleteAllByGroup',$d->groupHGroupID], 'method' => 'POST','class'=>'form1']) !!}
                                <button  type="submit" class="btn btn-warning" name="submit" data-toggle="popover" data-content="Delete all data in the group history for this group" data-placement="top" onclick="return confirm('Are You Sure?')"><span><i class="fas fa-trash"></i></span></button>
                                <input type="hidden" name="_method" value="DELETE">
                                {!!Form::close() !!}
                            </td>
                            @endif
                        </tr>
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

