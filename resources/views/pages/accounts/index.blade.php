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
        <h4><span class="alert bg2">ADD ACCOUNTS</span></h4>
        <br class="my-4">
            <div class="row">
                <div class="col-md-10">
            <form method="post" action="/acc-search-results" accept-charset="UTF-8" role="search">
                {{csrf_field()}} 
                <div class="input-group">
                    <input type="text" class="form-control" name="q" placeholder="Search Accounts"> 
                    <span class="input-group-btn">
                        <button type="submit" class="btn btn-info btn-lg">
                            <span><i class="fas fa-search"></i> Search</span>
                        </button>
                    </span>
                    
                </div>
            </form>
                </div>
                <div class="col-md-1">
                        <a href="/accounts/create" class="btn btn-success btn-lg" data-toggle="popover" data-content="Add a new account" data-placement="top"><span><i class="fas fa-plus"></i> Add</span></a>
                </div>
            </div>
            <hr class="my-4">
            @if(isset($data) && count($data))
            <table class="table table-striped table-hover table-sm">
                <thead>
                    <tr>
                        <th scope="col">Name</th>
                        <th scope="col">Role</th>
                        <th scope="col">Edit</th>
                        <th scope="col">Delete</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $acc)
                        <tr scope="row">
                            <td>{{$acc->accTitle}} {{$acc->accFName}} {{$acc->accMInitial}} {{$acc->accLName}}</td>
                            <td>{{$acc->accTypeDescription}}</td>
                            <td><a href="/accounts/{{$acc->accNo}}/edit" class="btn btn-secondary" data-toggle="popover" data-content="Edit account details" data-placement="top"><span><i class="far fa-edit"></i> Edit</span></a></td>
                            <td>
                                {!!Form::open(['action' => ['AccountController@deleteUpdate',$acc->accNo], 'method' => 'POST']) !!}
                                <button  type="submit" class="btn btn-danger" name="submit" data-toggle="popover" data-content="Delete this account" data-placement="top" onclick="return confirm('Are You Sure?')"><span><i class="fas fa-minus"></i> Delete</span></button>
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
@section('includes2')

@endsection
