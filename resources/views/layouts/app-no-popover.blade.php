<?php
    $ac = new App\models\AccessControl;
    $accesscontrols = $ac->status;
    if(session_id() == '' || !isset($_SESSION)) {
    session_start();
    }
    if(!is_null(Auth::user()) && isset($_SESSION['user'])){
        
    $user = DB::table('account')
    ->join('account_type','account_type.accTypeNo','=','account.accType')
    ->select('account.*','account_type.*')
    ->where('account.accID','=',Auth::user()->getId())->get();
    } else {
        Auth::logout();
        unset($_SESSION['user']);
        return view('/')->with('error','Something went wrong on loading the page.');
    }
?>
<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="role" content="{{Auth::user() ? Auth::user()->accType : ''}}">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        
        <link rel="shortcut icon" href="{{asset('img/design/logo/logo_L3R_icon.ico')}}">
        <link rel="stylesheet" href="{{asset('css/multi-select.css')}}">
        <link rel="stylesheet" href="{{asset('css/bootstrap.min.css')}}">
        <link rel="stylesheet" href="{{asset('css/bootstrap-submenu.css')}}">   
        <link rel="stylesheet" href="{{asset('css/footer.css')}}">
        <link rel="stylesheet" href="{{asset('css/default.css')}}">
        <link rel="stylesheet" href="{{asset('css/fontawesome/web-fonts-with-css/css/fontawesome-all.min.css')}}">
        <link rel="stylesheet" href="{{asset('css/header.css')}}">

        <script src="{{asset('js/jquery-3.3.1.min.js')}}"></script>
        <script src="{{asset('js/vm/notifications.vm.js')}}"></script>
        <script src="{{asset('js/popper.min.js')}}"></script>
        <script src="{{asset('js/bootstrap.min.js')}}"></script>
        <script src="{{asset('js/jquery.multi-select.js')}}"></script>
        <script src="{{asset('js/knockout-3.4.2.js')}}"></script>   
        <link rel="stylesheet" href="{{asset('css/search-select.min.css')}}">
        <script src="{{asset('js/search-select.min.js')}}"></script>
        
        
        @yield('includes')
        <title>{{config('app.name','cttracer')}}</title>
        <style type="text/css">
            @yield('style')
            #cont1 {
                margin-top: 50px;
                background-color: white;
            }
        </style>
    </head>
    <body>
        <div id="pageloader">
            <img src="{{asset('img/ajax-loader.gif')}}" alt="processing..." />
        </div>
        @if($accesscontrols == true)
        @include('inc.navbar')
        @else
        @include('inc.navbar2')
        @endif
        <wrapper class="d-flex flex-column" >
                <main class="flex-fill"> 
                    <div class="container" id="cont1">
                    
            @yield('content')
    

            @include('inc.confirm')  
                    </div>
                </main>
        @yield('paginator')
        @include('inc.footer')        
        </wrapper>
   
    </body>
</html>
@yield('includes2')

<script type="text/javascript">
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

$('notificationMenuList1').hide();
function btnConfirm2(f) {
    if(confirm('Are you sure?')) {
        f.submit();
        $("#pageloader").show();
    }
}

$(document).ready(function () {
   
    $(".form1").on("submit", function(){
        $("#pageloader").show();
    });

    $('.search-bar1').change(function () {
        $('#form-search').submit();
    });
    
    function btnConfirm() {
        if(confirm('Are you sure?')) {
            $("#pageloader").show();
        }
    }
    $('.popover-dismiss').popover({
    trigger: 'click'
    });
    $("form").bind("keypress", function(e) {
        if (e.keyCode == 13 && $("form").prop('id')!="form-search") {
            $('#confirm1').modal('show');
            return false;
        }
    });
});
</script>
<script src="{{asset('js/bootstrap-submenu.js')}}"></script>
<script src="{{asset('js/app.js')}}"></script>
