@extends('layouts.app')

@section('style')
    #btnAdd {
        padding-left:10px;
    }
@endsection

@section('content')
<div class="row">
    <div class="col-md-12 justify-align-center" id="index_content1">
        <h4>ADD ACCOUNTS</h4>
            <div class="row">
                <div class="col-md-10"
            <form method="post" action="/acc-search-results" accept-charset="UTF-8" role="search">
                {{csrf_field()}} 
                <div class="input-group">
                    <input type="text" class="form-control" name="q" placeholder="Search Accounts"> 
                    <span class="input-group-btn">
                        <button type="submit" class="btn btn-danger">
                            <span><i class="fas fa-search"></i></span>
                        </button>
                    </span>
                    
                </div>
            </form>
                </div>
                <div class="col-md-1">
                        <a href="/accounts/create" class="btn btn-primary"><span><i class="fas fa-plus"></i> Add</span></a>
                </div>
            </div>
            @if(isset($data) && count($data))
            <table class="table table-striped table-hover">
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
@endsection