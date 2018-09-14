<nav class="navbar navbar-expand-lg navbar-light panel-menu-sm-hide" style="width: 100%;height: 100px;" >
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item active">
                  <a href="/"><img src="{{asset('img/design/logo/logo.png')}}" style="width: 100px;"></a>
                </li>
            </ul>

            
            
            <ul class="navbar-nav navbar-right">
                <?php 
                

                if(!Auth::user()) {
                 
              if(Route::currentRouteName()!="login") {
                ?>
                     <li><a class="btn btn-primary" href="/login" id="login1"></span> LOGIN</a></li>
                <?php } }
                else { ?>
             
              <div style="font-style:italic;position: absolute;top:60px;right: 30px;">
              <span style="font-size: 12px;">@if(Auth::user()){{$user[0]->accTypeDescription}}@endif
              </span>
              </div>
                    <li class="nav-item dropdown">
                        <a class="nav-link" href="#" id="navbarDropdownMenuLink0" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span data-toggle="popover" data-content="{{$user[0]->accTitle}} {{$user[0]->accFName}} {{$user[0]->accMInitial}} {{$user[0]->accLName}}" data-placement="bottom"><img style="width: 40px;border-radius: 100%;" src="<?php echo $_SESSION['user']['avatar']; ?>"></span> </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink0">
                        <a class="dropdown-item" href="/logout">Logout</a>
                    </div>
                    </li>
    
                <?php 
                }
                ?>
            </ul>
        </div> 
        
    </nav>
    <div class="panel-menu-sm" style="padding-top:20px;padding-bottom:20px;">
        <ul class="navbar-nav mr-auto text-center">
            <li class="nav-item active">
              <img src="{{asset('img/design/logo/logo.png')}}" style="width: 100px;">
            </li>
        </ul>
    </div>
    <nav class="navbar navbar-expand-lg navbar-light" class="nav-header2" style="background-color: #F85353;height: 70px;">
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
          <li class="nav-item">
            <a class="nav-link" href="/"><i class="fas fa-home"></i> Home <span class="sr-only"></span></a>
          </li>
          <li class="nav-item dropdown menu1">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink1" data-toggle="dropdown" data-submenu aria-expanded="true" aria-haspopup="true">
                <i class="fas fa-cogs"></i> Settings
            </a>
            <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink1">
              <a class="dropdown-item d1l1" href="/stage-settings"><i class="fas fa-list"></i> Manage Stage Settings</a>
              <a class="dropdown-item d1l1" href="/accounts"><i class="fas fa-user-cog"></i> Manage Account Settings</a>
              <a class="dropdown-item d1l1" href="/groups"><i class="fas fa-users-cog"></i> Manage Group Settings</a>
              <a class="dropdown-item d1l1" href="/transfer-role-index"><i class="fas fa-exchange-alt"></i> Transfer Role</a>
              <a class="dropdown-item d1l1" href="/application-settings"><i class="fas fa-sliders-h"></i> Application Settings</a>
            </div>
          </li>
          <li class="nav-item dropdown menu1">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-users"></i> Groups
            </a>
            <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink2">
              <a class="dropdown-item" href="/quick-view"><i class="fas fa-search"></i> View groups</a>
              <a class="dropdown-item" href="/advised-groups"><i class="fas fa-chalkboard-teacher"></i> Advised Groups</a>
              <a class="dropdown-item" href="/group-history"><i class="fas fa-history"></i> Group History</a>
            </div>
          </li>
          <li class="nav-item dropdown menu1">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink3" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <i class="fas fa-calendar"></i> Schedule
            </a>
            <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink3">
              <a class="dropdown-item" href="/approve-schedules"><i class="far fa-calendar-check"></i> Approve Schedules</a>
              <a class="dropdown-item" href="/final-schedule-list"><i class="far fa-calendar-alt"></i> Final Schedule List</a>
              <a class="dropdown-item" href="https://calendar.google.com/calendar/embed?src=3kimjs5576ib3r9js5qvm9d0to%40group.calendar.google.com&ctz=Asia%2FManila" target="_blank"><i class="far fa-calendar"></i> View Calendar</a>
            </div>
          </li>
        </ul>
        
         <ul class="nav navbar-nav navbar-right">
            <li class="nav-item dropdown menu1">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink5" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    
                  <i class="fas fa-bell"></i>  Notifications 
                  <!-- ko if: AllNotifications() -->
                  <span class="badge badge-pill badge-success" data-bind="text: AllNotifications()"></span>
                  <!-- /ko -->
                </a>

           <!-- ko if: AllNotifications() -->
           <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink5" id="notificationMenuList1">
            @if(in_array($user[0]->accType,['1']))
            <!-- ko if: NotifyCoordOnSchedRequest().length -->
            <a class="dropdown-item" href="/nd/NotifyCoordOnSchedRequest/QuickViewController@search/Waiting for Schedule Request">Schedule Requests <span class="badge badge-pill badge-success" data-bind="text: NotifyCoordOnSchedRequest().length"></span></a>
            <!-- /ko -->
            <!-- ko if: NotifyCoordOnNextStage().length -->
            <a class="dropdown-item" href="/nd/NotifyCoordOnNextStage/QuickViewController@search/Ready for Next Stage"> Groups for Next Stage <span class="badge badge-pill badge-success" data-bind="text: NotifyCoordOnNextStage().length"></span></a>
            <!-- /ko -->
            <!-- ko if: NotifyCoordOnSchedFinalize().length -->
            <a class="dropdown-item" href="/nd/NotifyCoordOnSchedFinalize/QuickViewController@search/Waiting for Final Schedule"> Schedules to be Finalized <span class="badge badge-pill badge-success" data-bind="text: NotifyCoordOnSchedFinalize().length"></span></a>
            <!-- /ko -->
            <!-- ko if: NotifyCoordOnProjectArchive().length -->
            <a class="dropdown-item" href="/nd/NotifyCoordOnProjectArchive/QuickViewController@search/Submitted to Capstone Coordinator"> Project archive submissions <span class="badge badge-pill badge-success" data-bind="text: NotifyCoordOnProjectArchive().length"></span></a>
            <!-- /ko -->
            @endif
            @if(in_array($user[0]->accType,['1','2']))
              <!-- ko if: NotifyPanelOnSchedRequest().length -->
                <a class="dropdown-item" href="/nd/NotifyPanelOnSchedRequest/SchedAppController@search/null"> Schedule Approvals (Panel) <span class="badge badge-pill badge-success" data-bind="text: NotifyPanelOnSchedRequest().length"></span></a>
              <!-- /ko -->
              <!-- ko if: NotifyAdviserOnSubmission().length --> 
              <a class="dropdown-item" href="/nd/NotifyAdviserOnSubmission/AdvisedGroupsController@search/null">Group Submissions <span class="badge badge-pill badge-success" data-bind="text: NotifyAdviserOnSubmission().length"></span></a>
              <!-- /ko -->
              <!-- ko if: NotifyPanelOnProjectApproval().length -->
              <a class="dropdown-item" href="/nd/NotifyPanelOnProjectApproval/ProjAppController@search/null">Project Approvals <span class="badge badge-pill badge-success" data-bind="text: NotifyPanelOnProjectApproval().length"></span></a>
              <!-- /ko --> 
            @endif
            @if(in_array($user[0]->accType,['3']))
            <!-- ko if: NotifyStudentOnAdvCorrected().length -->
            <a class="dropdown-item" href="/nd/NotifyStudentOnAdvCorrected/MyProjController@index/null">Your Content Adviser has correcttions to your submission</a>
            <!-- /ko -->
            <!-- ko if: NotifyStudentOnPanelCorrected().length -->
            <a class="dropdown-item" href="/nd/NotifyStudentOnPanelCorrected/MyProjController@index/null">The panel members have corrections your submission</a>
            <!-- /ko -->
            <!-- ko if: NotifyStudentOnSchedDisapproved().length -->
            <a class="dropdown-item" href="/nd/NotifyStudentOnSchedDisapproved/MyProjController@index/null">Your schedule request was not approved.</a>
            <!-- /ko -->
            <!-- ko if: NotifyStudentOnNextStage().length -->
            <a class="dropdown-item" href="/nd/NotifyStudentOnNextStage/MyProjController@index/null">Your stage has been set to the next stage.</a>
            <!-- /ko -->
            <!-- ko if: NotifyStudentOnCompletion().length -->
            <a class="dropdown-item" href="/nd/NotifyStudentOnCompletion/MyProjController@index/null">Your project is waiting for completion</a>
            <!-- /ko -->
            <!-- ko if: NotifyStudentOnFinish().length -->
            <a class="dropdown-item" href="/nd/NotifyStudentOnFinish/MyProjController@index/null">Congratulations! your project is now complete!</a>
            <!-- /ko -->
             <!-- ko if: NotifyAllOnReady().length -->
             <a class="dropdown-item" href="/nd/NotifyAllOnReady/ScheduleController@index/null"> Your group is now ready for defense.</a>
             <!-- /ko -->
            @endif
              <!-- ko if: NotifyAllOnReady().length -->
              <a class="dropdown-item" href="/nd/NotifyAllOnReady/ScheduleController@index/null"> Ready for Defense <span class="badge badge-pill badge-success" data-bind="text: NotifyAllOnReady().length"></span></a>
              <!-- /ko -->
           </div>
           <!-- /ko -->
           <!-- ko ifnot: AllNotifications() -->
           <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink5">
              <a class="dropdown-item" href="#"> No new notifications</a>
           </div>
           <!-- /ko -->
            </li>

             <li class="nav-item dropdown menu1">
                 <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink4" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-project-diagram"></i> Projects
            </a>
            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink4">
              <a class="dropdown-item" href="/project-archive"><i class="fas fa-archive"></i> Project Archive</a>
              <a class="dropdown-item" href="/projects"><i class="fas fa-search"></i> Project Search</a>
              <a class="dropdown-item" href="/my-project"><i class="far fa-object-group"></i> My Project</a>
              <a class="dropdown-item" href="/approve-projects"><i class="fas fa-unlock-alt"></i> Approve Projects</a>
            </div>
             </li>
         </ul>
      </div>
      <!-- responsive -->
        @include('inc.nav-responsive')
      <!-- responsive -->
    </nav>
    <script type="text/javascript">
        $(document).ready(function(){
          $('[data-submenu]').submenupicker();
        });
    
    
      <?php if($user = !Auth::user()) {
      ?>
    
       $(".dropdown-item").hide();
       $(".dropdown-menu").hide();
      <?php
      }
      ?>
    </script>