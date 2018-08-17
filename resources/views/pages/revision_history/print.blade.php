@extends('layouts.app_print')

@section('style')
    .list1 {
        list-style:none;
    }
    #font2 {
        font-size 20px;
    }
@endsection

@section('content')
<div class="row">
    <div class="col-md-12 justify-align-center" id="index_content1">
        <div class="container">
            
        
        <div class="row divHeader"><img src="{{asset('img/su_header_logo.jpg')}}" style="width:50%;height:50%;"></div>
        <div class="divFooter"><center><img src="{{asset('img/su_footer_logo.jpg')}}" style="width:75%;height:75%;"></center></div>

        <div class="jumbotron bg-transparent print">
                @include('inc.messages')
                
        @if(isset($data) && count($data))
        <!-- Project Revision Approval -->
        <div class="row">
            <div class="col-md-12">
                <center>
                    <span style="font-size:20px;"><u><b>CHAPTER CONTENT CONTROL FORM</b></u></span><br>
                    @if($data['projApp'][0]->groupType=='Capstone')
                    (FOR INFORMATION TECHNOLOGY GROUPS ONLY)
                    @elseif($data['projApp'][0]->groupType=='Thesis')
                    (FOR COMPUTER SCIENCE GROUPS ONLY)
                    @endif
                </center>
                <br>
            </div>
            <div class="col-md-12">
                <?php $stageMsg="";
                switch($data['projApp'][0]->stageNo) {
                    case 2:
                    case '2': $stageMsg = "Chapter 1: Introduction and Background of the Study, Chapter 2: Review of Related Literature and Systems, and Chapter 3: Methodology";break;
                    case 3:
                    case '3': $stageMsg = "Partial User Manual";break;
                    case 4:
                    case '4': $stageMsg = "Complete User Manual";break;
                    case 4:
                    case '4': $stageMsg = "Chapter 1: Introduction and Background of the Study, Chapter 2: Review of Related Literature and Systems, Chapter 3: Methodology, Chapter 4: Testing and Evaluation, and Chapter 5: Summary and Conclusions";break;
                }
                ?>
                <p>
                This is to certify that the group working on the project entitled <b>{{$data['projApp'][0]->projName}}</b>  has submitted a copy of their <b>{{$stageMsg}}</b> to their Content Adviser and the members of their Panel of Evaluators.
                </p>
                <p>
                    The said document contains comments and recommendations that the group is required to apply before a clean copy is given to the abovementioned evaluators for reading and approval.
                </p>      
            </div>
            <div class="col-md-12">
            <h5>Revision Details : </h5>
            <h6>For the group of : {{$data['projApp'][0]->groupName}}</h6>
            <h6>Stage : {{$data['projApp'][0]->stageNo}} - {{$data['projApp'][0]->stageName}}</h6>
            <h6>Revision {{$data['projApp'][0]->revNo}}</h6>
            <hr>
            </div>
        </div> 
        <!-- comment section -->
        <h5>Project Approval Details : </h5>
        <div class="row justify-content-center">
            <?php $grpModel = new App\models\Group; ?>
               <div class="col-md-12">
                <table class="table table-hover table-responsive-sm">
                <thead>
                    <th style="border-bottom:1px solid #ccc;">Panel Member</th>
                    <th style="border-bottom:1px solid #ccc;">Comment</th>
                </thead>
                <tbody>
                @foreach($data['projApp'] as $rev)
                <tr style="border:1px solid #ccc;">
                    <td style="width:30%;border-bottom:1px solid #ccc!important;">
                    <span title='{{$rev->accTitle}} {{$rev->accFName}} {{$rev->accMInitial}} {{$rev->accLName}}'>
                            {{$rev->accTitle}} {{$rev->accFName}} {{$rev->accMInitial}} {{$rev->accLName}}@if($rev->panelIsChair)
                        (Chair panel member) @endif
                        </span><br>
                        <small>
                        @if($rev->revStatus == 1)
                        Approved
                        @elseif($rev->revStatus == 2)
                        Returned with Corrections
                        @else
                        <span class="badge badge-secondary badge-pill">  Waiting </span>
                        @endif  
                        </small>
                        <br>
                        <small>
                        {{date_format(new Datetime($rev->revTimestamp),"M-d-Y -- g:i A")}}
                        </small>
                    </td>
                    <td style="border-bottom:1px solid #ccc!important;">
                        <textarea class="auto-fit-textarea" style="font-size:1em;width:100%;overflow: visible;">{{$rev->revComment}}</textarea>
                    </td>
                </tr>
                @endforeach
                </tbody>
                </table>
               </div>
               
        </div> <!-- End of comment section -->
        <div class="form-row" style="margin-top:50px;">
            
            <div class="form-group col-md-4">
            <?php $cc = DB::table('account')->where('accType','=','1')->first(); ?>
            <u>{{$cc->accTitle}} {{$cc->accFName}} {{$cc->accMInitial}} {{$cc->accLName}}</u>
            <br>
            Capstone Coordinator
            </div> 
            <div class="form-group col-md-4">
            <?php $adv = DB::table('group')
            ->join('account','account.accID','=','group.groupCAdviserID')
            ->where('group.groupID','=',$data['projApp'][0]->groupID)->first(); ?>
            <u>{{$adv->accTitle}} {{$adv->accFName}} {{$adv->accMInitial}} {{$adv->accLName}}</u>
            <br>
            Content Adviser
            </div>
        </div>
        <!-- End of Project Revision Approval -->
        <span class="text-right">
            <button  id="printPageButton" type="button" class="btn btn-warning btn-lg" data-toggle="popover" data-content="Print revision" data-placement="top" onclick="window.print();return false;"><span><i class="fas fa-print"></i> Print</span></button>
        </span>
        </div> <!-- End of div jumbotron -->
        </div> <!-- End of div container -->
        @else
        <span>No results found.</span>
        @endif
    </div>
    </div>
</div>
@endsection
@section('includes2')
<script type="text/javascript">
$(document).ready(function () {
    $("textarea").each(function(textarea) {
        if($(this).val().length > 250) {
            $(this).height( $(this)[0].scrollHeight);
        }
    });
});  
</script>
@endsection