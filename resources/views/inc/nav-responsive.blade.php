    <div class="btn-group panel-menu-sm">
        <a class="nav-link" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" id="navbarDropdownMenuLink-res1">
                <i class="fas fa-bars"></i>
        </a> 
        <div class="dropdown-menu scrollable-menu" aria-labelledby="navbarDropdownMenuLink-res1">
          <p class="dropdown-item"><img style="width: 40px;border-radius: 100%;" src="<?php echo $_SESSION['user']['avatar']; ?>">
          {{$user[0]->accTitle}} {{$user[0]->accFName}} {{$user[0]->accMInitial}} {{$user[0]->accLName}}
          </p>

          <span class="dropdown-item">
          -- @if(Auth::user()){{$user[0]->accTypeDescription}}@endif
          </span><hr>
          <a class="dropdown-item" href="/"><i class="fas fa-home"></i> Home <span class="sr-only"></span></a>
          @if(in_array($user[0]->accType,['1']))
            <a class="dropdown-item" href="/stage-settings"><i class="fas fa-list"></i> Manage Stage Settings</a>
            <a class="dropdown-item" href="/accounts"><i class="fas fa-user-cog"></i> Manage Account Settings</a>
            <a class="dropdown-item" href="/groups"><i class="fas fa-users-cog"></i> Manage Group Settings</a>
            <a class="dropdown-item" href="/transfer-role-index"><i class="fas fa-exchange-alt"></i> Transfer Role</a>
            <a class="dropdown-item" href="/application-settings"><i class="fas fa-sliders-h"></i> Application Settings</a>
            <a class="dropdown-item" href="/quick-view"><i class="fas fa-search"></i> Search groups</a>
            <a class="dropdown-item" href="/group-history"><i class="fas fa-history"></i> Group History</a>
          @endif
          <hr>
          @if(in_array($user[0]->accType,['1','2']))
            <a class="dropdown-item d1l1" href="/advised-groups"><i class="fas fa-chalkboard-teacher"></i> Advised Groups</a>
            <a class="dropdown-item d1l1" href="/approve-schedules"><i class="far fa-calendar-check"></i> Approve Schedules</a>
            <a class="dropdown-item d1l1" href="/approve-projects"><a class="dropdown-item" href="/approve-projects"><i class="fas fa-unlock-alt"></i> Approve Projects</a>
          @endif
          @if(in_array($user[0]->accType,['3']))
            <a class="dropdown-item" href="/my-project"><i class="far fa-object-group"></i> My Project</a>
          @endif
          <hr>
            <a class="dropdown-item" href="/final-schedule-list"><i class="far fa-calendar-alt"></i> Final Schedule List</a>
            <a class="dropdown-item" href="https://calendar.google.com/calendar/embed?src=3kimjs5576ib3r9js5qvm9d0to%40group.calendar.google.com&ctz=Asia%2FManila" target="_blank"><i class="far fa-calendar"></i> View Calendar</a>
            <a class="dropdown-item" href="/project-archive"><i class="fas fa-archive"></i> Project Archive</a>
            @if(in_array($user[0]->accType,['1','2']))
            <a class="dropdown-item" href="/projects"><i class="fas fa-search"></i> Project Search</a>
            @endif
          <hr>
            <a class="dropdown-item" href="/logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>
    </div>

    <div class="btn-group panel-menu-sm">
        <a class="dropdown-toggle nav-link dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" id="navbarDropdownMenuLink-res2"><i class="fas fa-bell"></i>  Notifications 
            <!-- ko if: AllNotifications() -->
            <span class="badge badge-pill badge-success" data-bind="text: AllNotifications()"></span>
            <!-- /ko -->
        </a> 
    <!-- ko if: AllNotifications() -->
    <div class="dropdown-menu dropdown-menu-right scrollable-menu notificationMenuList-res2" aria-labelledby="navbarDropdownMenuLink-res2" id="notificationMenuList1">
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
    <div class="dropdown-menu dropdown-menu-right notificationMenuList-res2" aria-labelledby="navbarDropdownMenuLink5">
        <a class="dropdown-item" href="#"> No new notifications</a>
    </div>
    <!-- /ko -->
    </div>
   