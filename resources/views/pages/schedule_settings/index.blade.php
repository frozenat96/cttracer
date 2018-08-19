@extends('layouts.app')

@section('style')

@endsection

@section('content')
<div class="row">
    <div class="col-md-12 justify-align-center" id="index_content1">
        <div class="jumbotron bg1">
        @include('inc.messages')
        <h4><span class="alert bg2">SCHEDULE SETTINGS</span></h4>
        <br class="my-4">
            <div class="row">
                <div class="col-md-10">
            <form id="form-search" method="post" action="/schedule-search-results" accept-charset="UTF-8" role="search">
                {{csrf_field()}} 
                <div class="input-group">
                    <input type="text" class="form-control search-bar1" name="q" placeholder="Search Groups"> 
                    <span class="input-group-btn">
                        <button type="submit" class="btn btn-info btn-lg">
                            <span><i class="fas fa-search"></i> Search</span>
                        </button>
                    </span>
                    
                </div>
            </form>
                </div>
                <div class="col-md-1">
                        <a href="/schedule-settings/create" class="btn btn-success btn-lg" data-toggle="popover" data-content="Add a new schedule setting" data-placement="top"><span><i class="fas fa-plus"></i> Add</span></a>
                </div>
            </div>
            <hr class="my-4">
            @if(isset($data) && count($data))
            <?php 
            $model = new App\models\Group;
            ?>
            <table class="table table-striped table-hover table-sm table-responsive-sm">
                <thead>
                    <tr>
                        <th scope="col">Date</th>
                        <th scope="col">Starting Time</th>
                        <th scope="col">Ending Time</th>
                        <th scope="col">Group Type</th>
                        <th scope="col">Edit</th>
                        <th scope="col">Delete</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $dts)
                        <tr scope="row">
                            <td>{{$dts->dtsDate}}</td>
                            <td>{{$dts->dtsStartTime}}</td>
                            <td>{{$dts->dtsEndTime}}</td>
                            <td>{{$dts->dtsGroupType}}</td>
                            <td><a href="#" class="btn btn-secondary" data-toggle="popover" data-content="Edit schedule setting details" data-placement="top"><span><i class="far fa-edit"></i> Edit</span></a></td>
                            <td><a href="#" class="btn btn-danger" data-toggle="popover" data-content="Delete this setting" data-placement="top"><span><i class="fas fa-minus"></i> Delete</span></a></td>
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