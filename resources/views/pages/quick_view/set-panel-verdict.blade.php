@extends('layouts.app')

@section('includes')

@endsection

@section('style')
 
@endsection

@section('content')
<?php $grpModel = new App\models\Group; $userModel = new App\User; $user1=$userModel->current();?>
<div class="row">
    <div class="col-md-12 justify-align-center" id="index_content1">   
        <div class="row justify-content-center">
            <div class="col-md-9 jumbotron bx2">
                @include('inc.messages')
                {!!Form::open(['action' => ['QuickViewController@setProjectVerdict'], 'method' => 'POST','class'=>'form1']) !!}
                        <fieldset>
                            <h4 class="text-left"><span class="alert bg2">SET PANEL VERDICT</span><hr class="my-4"></h4>
                            
                            {{csrf_field()}} 
                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <label for=""><b>For group of : {{$data['group']->groupName}}</b></label>
                        </div>
                    </div> 
               
                    <div class="form-row">
                        <div class="form-group col-md-6" id="grp">
                            <label for="panel_verdict">Panel Verdict</label>
                            <select id="panel_verdict" class="form-control" name="panel_verdict" autocomplete="Panel Verdict" required="yes">
                                @foreach($data['panel_verdict'] as $verdict)
                                <option value="{{$verdict->panelVerdictNo}}" @if(!is_null(old('panel_verdict'))) @if(old('panel_verdict')==$verdict->panelVerdictNo) selected @endif @elseif(($data['group']->projPVerdictNo)== $verdict->panelVerdictNo) selected @endif><span>{{$verdict->pVerdictDescription}}</span></option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <!-- options -->
                    <hr class="my-4">
                    <div class="form-row">
                        <div class="col-md-12 text-right">
                        <table class="table-responsive-md" style="float:right;">
                        <tr>
                            <td style="padding-right:3px;">
                                <a class="btn btn-secondary btn-lg" class="back-button" href="/quick-view"><i class="fas fa-arrow-left"></i> Back</a>
                            </td>
                            <td style="padding-right:3px;">    
                                <button type="reset" class="btn btn-info btn-lg">
                                <span><i class="fas fa-recycle"></i> Reset Values</span>
                                </button>
                            </td>
                            <td>
                                <button type="button" id="sub1" class="btn btn-success btn-lg" data-toggle="modal" data-target="#confirm1">
                                    <span><i class="far fa-edit"></i> Save Changes</span>
                                </button>
                                <button id="sub2" type="submit" name="grp" value="{{$data['group']->groupID}}" style="display:none;"></button>
                            </td>
                        </tr>
                        </table>
                        </div>
                    </div>
                    <!-- options -->
                        </fieldset>
                        <input type="hidden" name="_method" value="PUT">
                {!!Form::close() !!}
            </div>
        </div>
    </div>
</div>
@endsection
@section('includes2')
<script type="text/javascript">
$(document).ready(function () {

});

//$('#group_type').select2({allowClear:true,selectOnClose:true});

</script>
@endsection