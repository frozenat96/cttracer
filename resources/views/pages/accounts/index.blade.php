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
        <h4><span class="alert bg2">MANAGE ACCOUNT SETTINGS</span></h4>
        <br class="my-4">
            <!--Search bar-->
            <div class="row">
                <div class="col-md-12">
                    <table class="table table-responsive-sm table-responsive-md">
                        <tr>
                            <td style="min-width: 360px;width:85%;">
                        <form id="form-search" method="post" action="/acc-search-results" accept-charset="UTF-8" role="search">
                            {{csrf_field()}} 
                        <div class="input-group">
                            <input type="text" class="form-control search-bar1" name="q" list="accounts1" placeholder="Search Accounts"> 
                            <?php 
                            $a1 = DB::table('account')->get();
                            $at1 = DB::table('account_type')->pluck('accTypeDescription');
                            ?>
                            <datalist id="accounts1" class="datalist scrollable">
                                @foreach($a1 as $a2)
                                    <option value="{{$a2->accFName}} {{$a2->accMInitial}} {{$a2->accLName}}">
                                @endforeach
                                @foreach($at1 as $at2)
                                    <option value="{{$at2}}">
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
                    <a href="/accounts/create" class="btn btn-success btn-lg" data-toggle="popover" data-content="Add a new account" data-placement="top"><span><i class="fas fa-plus"></i> Add</span></a>
                </td>
                    </tr>
                </table>
                </div>
            </div>
            <!-- search bar-->
            <hr class="my-4">
            @if(isset($data) && count($data))
            <table class="table table-striped table-hover table-sm table-responsive-sm table-responsive-md">
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
                            <td><span @if($acc->isChairPanelAll) data-toggle="popover" data-content="The chair panel for all panel members" data-placement="top" @endif> {{$acc->accTitle}} {{$acc->accFName}} {{$acc->accMInitial}} {{$acc->accLName}}
                            </span>
                            
                            </td>
                            <td>@if($acc->isChairPanelAll) Chair @endif{{$acc->accTypeDescription}}</td>
                            <td><a href="/accounts/{{$acc->accID}}/edit" class="btn btn-secondary" data-toggle="popover" data-content="Edit account details" data-placement="top"><span><i class="far fa-edit"></i> Edit</span></a></td>
                            <td>
                                {!!Form::open(['action' => ['AccountController@deleteUpdate',$acc->accID], 'method' => 'POST','class'=>'form1']) !!}
                                <button  type="submit" class="btn btn-danger" name="submit" data-toggle="popover" data-content="Delete this account" data-placement="top" onclick="return confirm('Are You Sure?')"><span><i class="fas fa-minus"></i> Delete</span></button>
                                <input type="hidden" name="_method" value="DELETE">
                                {!!Form::close() !!}
                            </td>
                        </tr>
                    @endforeach
                </tbody>

            </table>
            {!! $data->render(); !!}
                
            @else
            <table class="table"><tr><td><span>No results found</span></td></tr></table>
            @endif
        </div>
    </div>
</div>
@endsection
@section('includes2')

@endsection
