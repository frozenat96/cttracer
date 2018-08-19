@extends('layouts.app')

@section('style')

@endsection

@section('content')
<div class="row">
    <div class="col-md-12 justify-align-center" id="index_content1">
        <div class="jumbotron bg1">
        @include('inc.messages')
        <div class="row">
            <div class="col-md-12">
                <h4><span class="alert bg2">MANAGE GROUP SETTINGS</span></h4>
            </div>
        </div>
        <br class="my-4">

            <!--Search bar-->
            <div class="row">
                <div class="col-md-12">
                <table class="table table-responsive-sm table-responsive-md">
                    <tr>
                        <td style="min-width: 360px;width:85%;">
                    <form id="form-search" method="post" action="/group-search-results" accept-charset="UTF-8" role="search">
                        {{csrf_field()}} 
                    <div class="input-group">
                        <input type="text" class="form-control search-bar1" name="q" list="list1" placeholder="Search Groups"> 
                        
                        <datalist id="list1" class="datalist scrollable">
                            @foreach($data as $data1)
                            <option value="{{$data1->groupName}}">
                            @endforeach
                            @foreach($data as $data2)
                                <option value="{{$data2->projName}}">
                            @endforeach
                        </datalist>
                
                        <span class="input-group-btn">
                            <button type="submit" class="btn btn-info btn-lg">
                                <span><i class="fas fa-search"></i> Search</span>
                            </button>
                        </span>
                    </div>
                </form>
                </td>
            <td class="text-left" style="">
                <a href="/groups/create" class="btn btn-success btn-lg" data-toggle="popover" data-content="Add a new group" data-placement="top"><span><i class="fas fa-plus"></i> Add</span></a>
            </td>
                </tr>
            </table>
            </div>
        </div>
        <!-- search bar-->
            <hr class="my-4">
            @if(isset($data) && count($data))
            <?php 
            $model = new App\models\Group;
            ?>
            <table class="table table-striped table-hover table-sm table-responsive-sm">
                <thead>
                    <tr>
                        <th scope="col">Group Name</th>
                        <th scope="col">Group Type</th>
                        <th scope="col">Group Status</th>
                        <th scope="col">Content Adviser</th>
                        <th scope="col">View Project</th>
                        <th scope="col">Edit</th>
                        <th scope="col">Delete</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $grp)
                        <tr scope="row">
                            <td><span data-content="{{$grp->groupName}}" data-toggle="popover" data-placement="top">{{(substr($grp->groupName, 0, 25) . '..')}}</span></td>
                            <td>{{$grp->groupType}}</td>
                            <td>{{$grp->groupStatus}}</td>
                            <td><span data-content="{{$grp->accTitle}} {{$grp->accFName}} {{$grp->accMInitial}} {{$grp->accLName}}" data-toggle="popover" data-placement="top">{{$grp->accLName}}, {{$model->initials($grp->accFName)}}</span></td>
                            <td><a href="/projects/{{$grp->groupID}}" class="btn btn-warning" title="{{$grp->projName}}" data-toggle="popover" data-content="View project details" data-placement="top"><span><i class="fas fa-project-diagram"></i></span> {{(substr($grp->projName, 0, 10) . '..')}}</a></td>
                            <td><a href="/groups/{{$grp->groupID}}/edit" class="btn btn-secondary" data-toggle="popover" data-content="Edit group details" data-placement="top"><span><i class="far fa-edit"></i> Edit</span></a></td>
                            <td>
                            {!!Form::open(['action' => ['GroupController@destroy',$grp->groupID], 'method' => 'POST','class'=>'form1']) !!}
                            <button  type="submit" class="btn btn-danger" name="submit" data-toggle="popover" data-content="Delete this group" data-placement="top" onclick="return confirm('Are You Sure?')"><span><i class="fas fa-minus"></i> Delete</span></button>
                            <input type="hidden" name="_method" value="DELETE">
                            {!!Form::close() !!}
                            </td>
                        </tr>
                    @endforeach
                </tbody>

            </table>
            {!! $data->render() !!}    
            <div class="row">
                <div class="col-md-12 text-right">
                    <table class="table-responsive-md" style="float:right;">
                        <tr><td>
                        <form action="/deleteFinishedGroups" class="form1">
                        <span data-toggle="popover" data-content="Delete all groups that are already finished with their project that are under a Capstone Coordinator." data-placement="top">
                        <button id="sub1" type="button" class="btn btn-danger btn-lg" data-toggle="modal" data-target="#confirm1">
                                <span><i class="fas fa-minus"></i> Delete All Finished Groups</span>
                        </button>
                        </span>
                        <button id="sub2" type="submit" class="btn btn-success btn-lg" style="display:none;"></button>
                        </form>
                        </td></tr>
                    </table>
                </div>
            </div>
            @else
            <table class="table"><tr><td><span>No results found</span></td></tr></table>
            @endif
        </div>
    </div>
</div>
@endsection