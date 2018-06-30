@if(session('success')) 
    @if(is_array(session('success')))
        <div class="flash-message container">
            <div class="row alert alert-success flash-message">
                <div class="col-md-11">
                    <table>
                        @foreach (session('success') as $success)
                        <tr>
                            <td>
                                {{ $success }}
                            </td>
                        </tr>
                        @endforeach
                    </table>
                </div>
                <div class="col-md-1">
                    <a href="#" class="close btn" data-dismiss="alert" aria-label="close">&times;</a>
                </div>
            </div>
        </div>
    @else
    <div class="flash-message">
        <p class="alert alert-success">{{session('success')}} <a href="#" class="close text-right" data-dismiss="alert" aria-label="close">&times;</a></p>
    </div>
    @endif
@elseif(session('error')) 
    @if(is_array(session('error')))
        <div class="flash-message container">
            <div class="row alert alert-danger flash-message">
                <div class="col-md-11">
                    <table>
                        @foreach (session('error') as $error)
                        <tr>
                            <td>
                                {{ $error }}
                            </td>
                        </tr>
                        @endforeach
                    </table>
                </div>
                <div class="col-md-1">
                    <a href="#" class="close btn" data-dismiss="alert" aria-label="close">&times;</a>
                </div>
            </div>
        </div>
    @else
    <div class="flash-message">
        <p class="alert alert-danger">{{session('error')}} <a href="#" class="close text-right" data-dismiss="alert" aria-label="close">&times;</a></p>
    </div>
    @endif
@elseif ($errors->any())
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
@else
<div class="flash-message">
        @foreach (['danger', 'warning', 'success', 'info'] as $msg)
         @if(Session::has('alert-' . $msg))
        <p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }} <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></p>
        @endif
    @endforeach
</div>
@endif



