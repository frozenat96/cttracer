<div class="flash-message">
        @foreach (['danger', 'warning', 'success', 'info'] as $msg)
         @if(Session::has('alert-' . $msg))
        <p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }} <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></p>
        @endif
    @endforeach
</div>

@if ($errors->any())
    <div class="flash-message alert">
        <div class="row alert-danger">
            <div class="col-md-11">
        @foreach ($errors->all() as $error)
           
            <p class="alert">{{ $error }} </p>
            
        @endforeach
            </div>
            <div class="col-md-1">
                <a href="#" class="close btn" data-dismiss="alert" aria-label="close">&times;</a>
            </div>
        </div>
    </div>
@endif