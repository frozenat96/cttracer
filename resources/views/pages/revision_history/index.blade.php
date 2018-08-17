@extends('layouts.app')

@section('style')
    #btnAdd {
        padding-left:10px;
    }
@endsection

@section('content')
<div class="row">
    <div class="col-md-12 justify-align-center" id="index_content1">
        <div class="jumbotron bg1">
        @include('inc.messages')
        <h4><span class="alert bg2">REVISION HISTORY</span></h4>
        <br class="my-4">
            <div class="row">
                <div class="col-md-12">
            <form method="post" action="/revision-history-search-results/null" accept-charset="UTF-8" role="search">
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
                        <th scope="col"><small>Group name</small></th>
                        <th scope="col"><small>Stage No.</small></th>
                        <th scope="col"><small>Revision No.</small></th>
                        <th scope="col"><small>Reviewed By</small></th>
                        <th scope="col"><small>Date Reviewed</small></th>
                        <th scope="col"><small>Status</small></th>
                        <th scope="col"><small>View</small></th>
                        @if($user->accType=='1')
                        <th scope="col"><small>Edit</small></th>
                        <th scope="col"><small>Delete</small></th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $d)
                        <tr scope="row">
                            <td><small><span data-content="{{$d->groupName}}" data-toggle="popover" data-placement="top">{{(substr($d->groupName, 0, 16) . '..')}}</span></small></td>
                            <td><small>{{$d->revStageNo}}</small></td>
                            <td><small>{{$d->revNo}}</small></td>
                            <td><small>
                                <span data-content="{{$d->accTitle}} {{$d->accFName}} {{$d->accMInitial}} {{$d->accLName}}" data-toggle="popover" data-placement="top">
                                {{$d->accLName}}, {{$grpModel->initials($d->accFName)}}@if($d->panelIsChair)* @endif</span></small>
                            </td>
                            <td>
                                <small>{{date_format(new Datetime($d->revTimestamp),"Y-m-d")}}</small><br><small>{{date_format(new Datetime($d->revTimestamp),"g:i A")}}</small>
                            </td>
                            <td>
                                @if($d->revStatus=='1')
                                <span class="badge badge-success badge-pill"> Approved</span>
                                @elseif($d->revStatus=='2')
                                <span class="badge badge-danger badge-pill">Corrected </span> <br><small><a href="{{($d->revLink)}}" target="_blank" data-content="Download document with corrections" data-toggle="popover" data-placement="top"><i class="fas fa-download"></i> download</a></small>
                                @endif
                                
                            </td>
                            <td>
                                {!!Form::open(['action' => ['RevHistoryController@view'], 'method' => 'POST']) !!}
                                <button  type="submit" class="btn btn-info" name="submit" data-toggle="popover" data-content="View revision" data-placement="top"><span><i class="far fa-eye"></i></span></button>
                                <input type="hidden" name="grp" value="{{$d->groupID}}">
                                <input type="hidden" name="stg" value="{{$d->revStageNo}}">
                                <input type="hidden" name="rev_no" value="{{$d->revNo}}">
                                {!!Form::close() !!}
                            </td>
                            @if($user->accType=='1')
                            <td>
                                <a href="/revision-history/{{$d->revID}}/edit" class="btn btn-secondary" data-toggle="popover" data-content="Modify Revision Details" data-placement="top"><span><i class="far fa-edit"></i></span> </a>
                            </td>
                            <td>
                                {!!Form::open(['action' => ['RevHistoryController@destroy',$d->revID], 'method' => 'POST','class'=>'form1']) !!}
                                <button  type="submit" class="btn btn-danger" name="submit" data-toggle="popover" data-content="Delete this revision" data-placement="top" onclick="return confirm('Are You Sure?')"><span><i class="fas fa-minus"></i></span></button>
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
@section('includes2')

@endsection
