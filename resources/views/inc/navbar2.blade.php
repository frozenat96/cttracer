<nav class="navbar navbar-expand-lg navbar-light" style="width: 100%;height: 100px;">
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item active">
                  <img src="{{asset('img/design/logo/logo.png')}}" style="width: 100px;">
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
              <span style="font-size: 12px;">Logged in as @if(Auth::user()){{$user[0]->accTypeDescription}}@endif
              </span>
              </div>
                    <li class="nav-item dropdown">
                        <a class="nav-link" href="#" id="navbarDropdownMenuLink0" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span><img style="width: 40px;border-radius: 100%;" src="<?php echo $_SESSION['user']['avatar']; ?>"></span> </a>
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
              <a class="dropdown-item d1l1" href="/stage-settings"><i class="fas fa-list"></i> Stage Settings</a>
              <a class="dropdown-item d1l1" href="/accounts"><i class="fas fa-user-cog"></i> Add Accounts</a>
              <a class="dropdown-item d1l1" href="/groups"><i class="fas fa-users-cog"></i> Add Groups</a>
              <a class="dropdown-item d1l1" href="/transfer-role-index"><i class="fas fa-exchange-alt"></i> Transfer Role</a>
            </div>
          </li>
          <li class="nav-item dropdown menu1">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-users"></i> Groups
            </a>
            <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink2">
              <a class="dropdown-item" href="/quick-view"><i class="fas fa-search"></i> Search groups</a>
              <a class="dropdown-item" href="/advised-groups"><i class="fas fa-chalkboard-teacher"></i> Advised Groups</a>
            </div>
          </li>
          <li class="nav-item dropdown menu1">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink3" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="far fa-calendar-alt"></i> Schedule
            </a>
            <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink3">
              <a class="dropdown-item" href="/approve-schedules"><i class="far fa-calendar-check"></i> Approve Schedules</a>
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
           <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink5">
            @if($user[0]->accType == '1')

            @elseif($user[0]->accType == '2')
              <!-- ko if: NotifyPanelOnSchedRequest().length -->
                <a class="dropdown-item" href="/NotifyPanelOnSchedRequest_d"> Schedule Approvals (Panel) <span class="badge badge-pill badge-success" data-bind="text: NotifyPanelOnSchedRequest().length"></span></a>
              <!-- /ko -->

            @elseif($user[0]->accType == '3')
            @endif
              


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