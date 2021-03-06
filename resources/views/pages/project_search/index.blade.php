@extends('layouts.app')

@section('includes')

@endsection

@section('style')
@endsection

@section('content')
<div class="row" id="app">
    <?php  $user = new App\User; $user = $user->current(); ?>
    <div class="col-md-12 justify-align-center" id="index_content1">
        <div class="jumbotron bg1" style="padding:20px;padding-top:30px;">
        @include('inc.messages')
        <h4><span class="alert bg2">PROJECT ARCHIVE</span></h4>
        <br class="my-4">
            <!--Search bar-->
            <div class="row">
                <div class="col-md-12">
                <table class="table table-responsive-sm table-responsive-md">
                    <tr>
                        <td @if($user[0]->accType=='1') style="min-width: 360px;width:85%;" @else style="width:100%;" @endif>
                    <form id="form-search" method="post" action="/proj-archive-search-results" accept-charset="UTF-8" role="search">
                        {{csrf_field()}}  
                    <div class="input-group">
                            <input type="text" class="form-control search-bar1" list="list1" name="q" placeholder="Search Projects"> 

                            @if(isset($data) && count($data))
                            <datalist id="list1" class="datalist scrollable">
                                @foreach($data as $data1)
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
                </td>
            @if($user[0]->accType=='1')
            <td class="text-left" style="">
                <a href="/project-archive/create" class="btn btn-success btn-lg" data-toggle="popover" data-content="Add a new project archive" data-placement="top"><span><i class="fas fa-plus"></i> Add</span></a>
            </td>
            @endif
                </tr>
            </table>
            </div>
        </div>
        <!-- search bar-->

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


