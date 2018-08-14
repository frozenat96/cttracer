@extends('layouts.app')

@section('includes')

@endsection

@section('style')
@endsection

@section('content')
<div class="row" id="app">
    <?php  $user = new App\User; $user = $user->current(); ?>
    <div class="col-md-12 justify-align-center" id="index_content1">
        <div class="jumbotron bg1">
        @include('inc.messages')
        <h4><span class="alert bg2">PROJECT ARCHIVE</span></h4>
        <br class="my-4">
            <div class="row">
            <div @if($user[0]->accType=='1') class="col-md-10" @else class="col-md-12" @endif>
                <form method="post" action="/proj-archive-search-results" accept-charset="UTF-8" role="search">
                    {{csrf_field()}} 
                    <div class="input-group">
                        <input type="text" class="form-control" name="q" placeholder="Search Projects"> 
                        <span class="input-group-btn">
                            <button type="submit" class="btn btn-info btn-lg">
                                <span><i class="fas fa-search"></i> Search</span>
                            </button>
                        </span>  
                    </div>
                </form>
            </div>
                @if($user[0]->accType=='1')
                <div class="col-md-1 text-right">
                    <a href="/project-archive/create" class="btn btn-success btn-lg" data-toggle="popover" data-content="Add a new project archive" data-placement="top"><span><i class="fas fa-plus"></i> Add</span></a>
                </div>
                @endif
            </div>
        <hr class="my-4">
        @if(isset($data) && count($data))
        <table class="table table-striped table-hover table-responsive-sm">
            <thead>
                <tr>
                    <th scope="col">Project Name</th>
                    <th scope="col">Project Document</th>
                    @if(isset($user) && $user[0]->accType=='1')
                    <th scope="col">Edit</th>
                    <th scope="col">Delete</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                <?php $user = new App\User;
                $user = $user->current();
                ?>
                @foreach($data as $proj)
                    <tr scope="row">
                        <td>
                            <span tabindex="0" class="" data-toggle="popover" data-content="{{$proj->projName}}" data-placement="top">{{(substr($proj->projName, 0, 20) . '..')}}</span>
                        </td>
                        <td>
                            <a href="{{($proj->projDocumentLink)}}" target="_blank">
                                <span class="badge badge-info"> 
                                    <i class="fas fa-external-link-alt"></i>
                                     Document Link
                                </span>
                            </a>     
                        </td>
                        @if(isset($user) && $user[0]->accType=='1')
                        <td>
                            <a href="/project-archive/{{$proj->projID}}/edit" class="btn btn-secondary" data-toggle="popover" data-content="Edit project archive details" data-placement="top"><span><i class="far fa-edit"></i> Edit</span></a>
                        </td>
                        <td>
                            {!!Form::open(['action' => ['ProjSearchController@destroy',$proj->projID], 'method' => 'POST','class'=>'form1']) !!}
                            <button  type="submit" class="btn btn-danger" name="submit" data-toggle="popover" data-content="Delete this project" data-placement="top" onclick="return confirm('Are You Sure?')"><span><i class="fas fa-minus"></i> Delete</span></button>
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
        <!--
        <project-search></project-search>
        -->
        </div>
    </div>
    
</div>

@section('paginator')
    <!--
    <div class="container justify-align-center">
    </div> 
    -->
@endsection

@endsection


