@extends('layouts.app')

@section('style')
@endsection

@section('content')
<div class="row" id="app">
    <div class="col-md-12 justify-align-center" id="index_content1">
        <h4>PROJECT SEARCH</h4>
        <form>
            <div class="form-row">
                <div class="col-7">
                    <input type="text" class="form-control" placeholder="Search" id="search">
                    </div>
                    <div class="col-1">
                    <input type="button" class="form-control btn btn-danger" value="Search" id="btn-search" data-bind="click: callToServer">
                    </div>
                </div>
            </form>
       
        <div id="output1">
            @if(isset($data) && count($data) > 0)
            <div id="load" style="position: relative;">
                    @foreach($data as $proj)
                        <div>
                            <h3>
                                {{$proj->projName }}
                            </h3>
                        </div>
                    @endforeach
                    </div>
            @endif
            
        </div>
      
    </div>
    
</div>
@endsection

@section('includes2')
<script type="text/javascript">
    var ViewModels = ViewModels || {};
    function ProjectVM() {
        this.Project = ko.observableArray([]);
        this.pagination = ko.observableArray([]);
        this.url = '{{route('projSearch')}}';

        this.callToServer = function () {
            var self = this;
            var url = self.url;
            var token = '{{Session::token()}}';
            var Query= {};
            Query.Search = document.getElementById ("search").value || "";
    
            $.ajax({
               url: url,
               type: 'get',
               data: {'search':Query.Search,_token:token},
               success:function(data){
                console.log(data);
               }
            });
        }.bind(this);
    }  
    $(document).ready(function () {
        ViewModels.ProjectVM = new ProjectVM();
        ko.applyBindings(ViewModels.ProjectVM);
        //console.log("Customers", ViewModels.CustomerViewModel.Customers());
    });
    </script>
@endsection


