@extends('layouts.app')

@section('style')

@endsection

@section('content')
<div class="row">
    <div class="col-md-12 justify-align-center" id="index_content1">
        <div class="jumbotron bg1">
        <h4><span class="alert bg2">ADD GROUPS</span></h4>
        <br class="my-4">
            <div class="row">
                <div class="col-md-10">
            <form method="post" action="/group-search-results" accept-charset="UTF-8" role="search">
                {{csrf_field()}} 
                <div class="input-group">
                    <input type="text" class="form-control" name="q" placeholder="Search Groups"> 
                    <span class="input-group-btn">
                        <button type="submit" class="btn btn-danger">
                            <span><i class="fas fa-search"></i> Search</span>
                        </button>
                    </span>
                    
                </div>
            </form>
                </div>
                <div class="col-md-1">
                        <a href="/groups/create" class="btn btn-primary"><span><i class="fas fa-plus"></i> Add</span></a>
                </div>
            </div>
            <hr class="my-4">
            @if(isset($data) && count($data))
            <?php 
            $model = new App\models\Group;
            ?>
            <table class="table table-striped table-hover table-sm">
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
                            <td><span title="{{$grp->groupName}}">{{(substr($grp->groupName, 0, 25) . '..')}}</span></td>
                            <td>{{$grp->grpType}}</td>
                            <td>{{$grp->groupStatus}}</td>
                            <td><span title="{{$grp->accTitle}} {{$grp->accFName}} {{$grp->accMInitial}} {{$grp->accLName}}">{{$grp->accLName}}, {{$model->initials($grp->accFName)}}</span></td>
                            <td><a href="#" class="btn btn-info" title="{{$grp->projName}}" ><span><i class="fas fa-project-diagram"></i></span> {{(substr($grp->projName, 0, 10) . '..')}}</a></td>
                            <td><a href="#" class="btn btn-warning"><span><i class="far fa-edit"></i> Edit</span></a></td>
                            <td><a href="#" class="btn btn-danger"><span><i class="fas fa-minus"></i> Delete</span></a></td>
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