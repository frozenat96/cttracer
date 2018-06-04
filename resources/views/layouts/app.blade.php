<?php
    if(session_id() == '' || !isset($_SESSION)) {
    session_start();
	}
?>
<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <link rel="stylesheet" href="{{asset('css/bootstrap.min.css')}}">
        <link rel="stylesheet" href="{{asset('css/bootstrap-submenu.css')}}">
        <link rel="stylesheet" href="{{asset('css/header.css')}}">
        <link rel="stylesheet" href="{{asset('css/footer.css')}}">
        <link rel="stylesheet" href="{{asset('css/default.css')}}">
        <link rel="stylesheet" href="{{asset('css/fontawesome/web-fonts-with-css/css/fontawesome-all.min.css')}}">

        <script src="{{asset('js/jquery-3.3.1.slim.min.js')}}"></script>
        <script src="{{asset('js/popper.min.js')}}"></script>
        <script src="{{asset('js/bootstrap.min.js')}}"></script>
        <script src="{{asset('js/bootstrap-submenu.js')}}"></script>
        @yield('includes')
        <title>{{config('app.name','cttracer')}}</title>
        <style type="text/css">
            @yield('style')
        </style>
    </head>
    <body>
        @include('inc.navbar')
        <wrapper class="d-flex flex-column">
                <main class="flex-fill">
                    <div class="container" id="c1">
                        

        
            @yield('content')
    

                        
                    </div>
                </main>
        @include('inc.footer')        
        </wrapper>
    </body>
</html>
