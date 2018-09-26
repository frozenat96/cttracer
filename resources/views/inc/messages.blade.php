@if(session('success'))
    @if(is_array(session('success')))
        <div class="flash-message container" style="padding-bottom:20px;">
            <div class="row alert alert-success flash-message">
                <div class="col-md-11">
                    <table>
                        @foreach ((session('success')) as $s)
                        <tr>
                            <td>
                                {{ $s }}
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
        <p class="alert alert-success">{{session()->get('success')}} <a href="#" class="close text-right" data-dismiss="alert" aria-label="close">&times;</a></p>
    </div>
    @endif
@elseif ($errors->any())
    <div class="flash-message" style="padding-bottom:20px;">
        <div class="row alert alert-danger">
            <div class="col-md-11">
            <table>
        @foreach ($errors->all() as $error)
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
@endif
<div class="flash-message" style="padding-bottom:20px;">
        @foreach (['danger', 'warning', 'success', 'info'] as $msg)
         @if(Session::has('alert-' . $msg))
        <p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }} <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></p>
        @endif
    @endforeach
</div>

@if(isset($success2))
    @if(is_array($success2))
        <div class="flash-message container" style="padding-bottom:20px;">
            <div class="row alert alert-success flash-message">
                <div class="col-md-11">
                    <table>
                        @foreach ($success2 as $s)
                        <tr>
                            <td>
                                {{ $s }}
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
    <div class="flash-message" style="padding-bottom:20px;">
        <p class="alert alert-success">{{$success2}} <a href="#" class="close text-right" data-dismiss="alert" aria-label="close">&times;</a></p>
    </div>
    @endif
@endif



