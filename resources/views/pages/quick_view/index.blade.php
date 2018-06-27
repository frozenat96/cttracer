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
        @include('inc.messages')
        <h4><span class="alert bg2">SEARCH GROUPS</span></h4>
        <br class="my-4">
            <div class="row">
                <div class="col-md-12">
            <form method="post" action="/quick-view-search-results" accept-charset="UTF-8" role="search">
                {{csrf_field()}} 
                <div class="input-group">
                    <input type="text" class="form-control" name="q" placeholder="Search Groups"> 
                    <span class="input-group-btn">
                        <button type="submit" class="btn btn-info">
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
             @foreach($data as $grp)
                <div class="form-row card bx2 card1 jumbotron">
                    <div class="col-md-12"> 
                        <table class="table table-sm">
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
                                    <td>Group Type : {{$grp->grpType}}</td>
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
                                Project View : <a href="/projects/{{$grp->groupNo}}" class="btn btn-warning" title="{{$grp->projName}}" data-toggle="popover" data-content="View project details" data-placement="top"><span><i class="fas fa-project-diagram"></i></span> {{(substr($grp->projName, 0, 10) . '..')}}</a>
                                </td></tr>
                                <tr><td>
                                Project Stage : {{$grp->stageName}}
                                </td></tr>
                                <tr><td>
                                Project Status : {{$grp->pVerdictDescription}}
                                </td></tr>
                                </table>
                            </td>

                            <td>
                                    <table class="table-sm table-hover table-striped">
                                    <tr><td>
                                    <a href="/quick-view/{{$grp->groupNo}}/edit" class="btn btn-success btn-sm" data-toggle="popover" data-content="Modify schedule" data-placement="top"><span><i class="far fa-calendar-plus"></i></span> Modify Schedule</a>
                                    </td></tr>
                                    <tr><td>
                                        <a href="/groups/{{$grp->groupNo}}/edit" class="btn btn-secondary btn-sm" data-toggle="popover" data-content="Modify Group Details" data-placement="top"><span><i class="far fa-edit"></i></span> Modify Group Details</a>
                                    </td></tr>
                                    @if((in_array($grp->projPVerdictNo,['2','3'])) && (in_array($grp->groupStatus,['Submitted to Panel Members','Corrected by Panel Members'])))
                                    <tr><td>
                                    <a href="{!! route('modifyProjApp', ['id'=>$grp->groupNo]) !!}" class="btn btn-info btn-sm" data-toggle="popover" data-content="Modify the Group's Project Approval Details" data-placement="top"><span><i class="far fa-edit"></i></span> Modify Project Approval</a>
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