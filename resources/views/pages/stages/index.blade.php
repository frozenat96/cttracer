@extends('layouts.app')

@section('style')

@endsection

@section('content')
<div class="row">
    <div class="col-md-12 justify-align-center" id="index_content1">
        <div class="jumbotron bg1">
        @include('inc.messages')
        <h4><span class="alert bg2 f-title1">MANAGE STAGE SETTINGS</span></h4>
            <br class="my-4">
            <div class="row">
                <div class="col-md-10">
            <form method="post" action="/stage-search-results" accept-charset="UTF-8" role="search">
                {{csrf_field()}} 
                <div class="input-group">
                    <input type="text" class="form-control" name="q" placeholder="Search Stages" list="stage1"> 
                    <?php 
                    $s1 = DB::table('stage')->pluck('stageName');
                    ?>
                    <datalist id="stage1" class="datalist scrollable">
                        @foreach($s1 as $s2)
                            <option value="{{$s2}}">
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
                <div class="col-md-1 text-right">
                        <a href="/stage-settings/create" class="btn btn-success btn-lg" data-toggle="popover" data-content="Add a new stage" data-placement="top"><span><i class="fas fa-plus"></i> Add</span></a>
                </div>
            </div>
            
            <hr class="my-4">
            @if(isset($data) && count($data))
            <table class="table table-striped table-hover table-sm table-responsive-sm table-responsive-md">
                <thead>
                    <tr>
                        <th scope="col">Stage No.</th>
                        <th scope="col">Stage Name</th>
                        <th scope="col">Defense Duration</th>
                        <th scope="col">Panel Members</th>
                        <th scope="col">Edit</th>
                        <th scope="col">Delete</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $stage)
                        <tr scope="row">
                            <td>{{$stage->stageNo}}</td>
                            <td>{{$stage->stageName}}</td>
                            <td>{{$stage->stageDefDuration}} minutes</td>
                            <td>{{$stage->stagePanel}}</td>
                            <td><a href="/stage-settings/{{$stage->stageNo}}/edit" class="btn btn-secondary" data-toggle="popover" data-content="Edit stage details" data-placement="top"><span><i class="far fa-edit"></i> Edit</span></a></td>
                            <td>
                            {!!Form::open(['action' => ['StageController@destroy',$stage->stageNo], 'method' => 'POST','class'=>'form1']) !!}
                            <button  type="submit" class="btn btn-danger" name="submit" data-toggle="popover" data-content="Delete this stage" data-placement="top" onclick="return confirm('Are You Sure?')"><span><i class="fas fa-minus"></i> Delete</span></button>
                            <input type="hidden" name="_method" value="DELETE">
                            {!!Form::close() !!}
                            </td>
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