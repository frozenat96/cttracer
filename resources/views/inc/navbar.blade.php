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
              <span style="font-size: 12px;">Logged in as Capstone Coordinator
              </span>
              </div>
                    <li class="nav-item dropdown">
                        <a class="nav-link" href="#" id="navbarDropdownMenuLink0" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span><img style="width: 40px;border-radius: 100%;" src="<?php echo $_SESSION['avatar']; ?>"></span> </a>
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
    <nav class="navbar navbar-expand-lg navbar-light" style="background-color: rgba(255,51,51,1);height: 100px;">
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
          <li class="nav-item active">
            <a class="nav-link" href="/">Home <span class="sr-only">(current)</span></a>
          </li>
          <li class="nav-item dropdown menu1">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink1" data-toggle="dropdown" data-submenu aria-expanded="true" aria-haspopup="true">
            Settings
            </a>
            <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink1">
              <div class="dropdown dropright dropdown-submenu menu1">
                <a class="dropdown-item dropdown-toggle d1l1" href="#" data-toggle="dropdown" >Project Settings</a>
                <div class="dropdown-menu">
                  <a class="dropdown-item" href="/stage-settings">Stage Settings</a>
                  <a class="dropdown-item" href="/schedule-settings">Schedule Settings</a>
                </div>
              </div>
              
              <a class="dropdown-item d1l1" href="/add-accounts">Add Accounts</a>
              <a class="dropdown-item d1l1" href="/add-groups">Add Groups</a>
              <a class="dropdown-item d1l1" href="/transfer-role">Transfer Role</a>
            </div>
          </li>
          <li class="nav-item dropdown menu1">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              Groups
            </a>
            <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink2">
              <a class="dropdown-item" href="/search-groups">Search Groups</a>
              <a class="dropdown-item" href="/advised-groups">Advised Groups</a>
            </div>
          </li>
          <li class="nav-item dropdown menu1">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink3" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              Schedule
            </a>
            <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink3">
              <a class="dropdown-item" href="/approve-schedules">Approve Schedules</a>
              <a class="dropdown-item" href="/schedule-settings">Schedule Settings</a>
            </div>
          </li>
        </ul>
         <ul class="nav navbar-nav navbar-right">
             <li class="nav-item dropdown menu1">
                 <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink4" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              Projects
            </a>
            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink4">
              <a class="dropdown-item" href="/project-search">Project Search</a>
              <a class="dropdown-item" href="/my-project">My Project</a>
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