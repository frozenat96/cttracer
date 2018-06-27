@if(session('success')) 
<div class="flash-message">
    <p class="alert alert-success">{{session('success')}} <a href="#" class="close text-right" data-dismiss="alert" aria-label="close">&times;</a></p>
</div>
@else
<div class="flash-message">
        @foreach (['danger', 'warning', 'success', 'info'] as $msg)
         @if(Session::has('alert-' . $msg))
        <p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }} <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></p>
        @endif
    @endforeach
</div>
@endif

@if ($errors->any())
    <div class="flash-message">
        <div class="row alert alert-danger">
            <div class="col-md-11">
        @foreach ($errors->all() as $error)
           
            <p class="">{{ $error }} </p>
            
        @endforeach
            </div>
            <div class="col-md-1">
                <a href="#" class="close btn" data-dismiss="alert" aria-label="close">&times;</a>
            </div>
        </div>
    </div>
@endif

