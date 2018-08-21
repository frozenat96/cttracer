@extends('layouts.app')

@section('style')
    #index_content1 {
        background-color: #FFC694;
        padding: 20px;
    }
    #index_content1 p {
        
    }
    #index_content1 h4 {
        font-weight: bold;
    }

    #indexbg {
        
    }
@endsection

@section('content')

<div class="row">
<div class="col-md-12 jumbotron" id="index_content1">
        @if(session('status'))
        <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif
        <div class="row">
            <div class="col-md-12 text-left">
                <h4>Instructions for Submission of Documents</h4>
                <ol class="list-group">
                    <li class="list-group-item">
                        Click the Open Documents Folder button.
                    </li>
                    <li class="list-group-item">
                        Upload your file in word or pdf format.
                    </li>
                    <li class="list-group-item">
                        Right click on the uploaded file and click the 'Share' button option from the dropdown selection.<hr>
                        <img src="{{asset('img/submit-instructions/3.png')}}" class="img-fluid border" alt="Background image">
                    </li>
                    <li class="list-group-item">
                        Add the email addresses of your panel members and content adviser.<hr>
                        <img src="{{asset('img/submit-instructions/4.png')}}" class="img-fluid border" alt="Background image">
                    </li>
                    <li class="list-group-item">
                        Right click on the uploaded file and click the 'Share' button option from the dropdown selection and click the 'Advanced button'.<hr>
                        <img src="{{asset('img/submit-instructions/5.png')}}" class="img-fluid border" alt="Background image">
                    </li>
                    <li class="list-group-item">
                        Click the 'change' link.<hr>
                        <img src="{{asset('img/submit-instructions/6.png')}}" class="img-fluid border" alt="Background image">
                    </li>
                    <li class="list-group-item">
                        Select 'On - Anyone with the link' from the link sharing options, change the access to 'Can View', and click the 'Save' button.<hr>
                        <img src="{{asset('img/submit-instructions/7.png')}}" class="img-fluid border" alt="Background image">
                    </li>
                    <li class="list-group-item">
                        Click the Edit Icon button of the Capstone Coordinator email and select 'Is Owner' option from the selection.<hr>
                        <img src="{{asset('img/submit-instructions/8.png')}}" class="img-fluid border" alt="Background image">
                    </li>
                    <li class="list-group-item">
                        Remove your email by clicking on the 'x' icon, click the 'Save changes' button, then click the 'Yes' button to confirm.<hr>
                        <img src="{{asset('img/submit-instructions/9.png')}}" class="img-fluid border" alt="Background image">
                    </li>
                    <li class="list-group-item">
                        Right click on the uploaded file and click the 'Get shareable link' button and copy the link.<hr>
                        <img src="{{asset('img/submit-instructions/12.png')}}" class="img-fluid border" alt="Background image">
                    </li>
                    <li class="list-group-item">
                        Paste the link into the Document link input field, click on the 'Save Changes' button, then click the 'OK' button to confirm.<hr>
                        <img src="{{asset('img/submit-instructions/13.png')}}" class="img-fluid border" alt="Background image">
                    </li>
                </ol>

            </div>	
        </div>
</div>
@endsection
