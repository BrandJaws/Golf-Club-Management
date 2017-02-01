<div id="aside" class="app-aside modal fade nav-dropdown"> 
    <!-- fluid app aside -->
    <div class="left navside dark dk" layout="column">
      <div class="navbar no-radius"> 
        <!-- brand --> 
        <a class="navbar-brand">
        <div ui-include="'{{url('/assets/images/logo_wh.png')}}'"></div>
        <img src="{{url('/assets/images/logo_wh.png')}}" alt="." class=""> <span class="hidden-folded inline hide">Grit</span> </a>
        <!-- / brand --> 
      </div>
      <div flex class="hide-scroll">
        <nav class="scroll nav-light">
          <ul class="nav">
            <li class="nav-header hidden-folded"> <small class="text-muted">Main</small> </li>
            <li> <a href="{{route('admin.dashboard')}}" > <span class="nav-icon"> <i class="material-icons">&#xe3fc; <span ui-include="'{{asset('assets/images/i_0.svg')}}'"></span> </i> </span> <span class="nav-text">Dashboard</span> </a> </li>
            <li> <a href="{{route('admin.members.members')}}" > <span class="nav-icon"> <i class="material-icons">&#xe8d2; <span ui-include="'{{asset('assets/images/i_3.svg')}}'"></span> </i> </span> <span class="nav-text">Members</span> </a> </li>
            <li> <a href="{{route('admin.reservations.reservations')}}" > <span class="nav-icon"> <i class="material-icons">&#xe8d2; <span ui-include="'{{asset('assets/images/i_3.svg')}}'"></span> </i> </span> <span class="nav-text">Reservations</span> </a> </li>
            <li> <a href="{{route('admin.profile.profile')}}" > <span class="nav-icon"> <i class="material-icons">&#xe8d2; <span ui-include="'{{asset('assets/images/i_3.svg')}}'"></span> </i> </span> <span class="nav-text">Profile / Settings</span> </a> </li>
            <li> <a href="#." > <span class="nav-icon"> <i class="material-icons">&#xe8d2; <span ui-include="'{{asset('assets/images/i_3.svg')}}'"></span> </i> </span> <span class="nav-text">Shop</span> </a> </li>
            <li> <a href="{{route('admin.segments.segments')}}" > <span class="nav-icon"> <i class="material-icons">&#xe8d2; <span ui-include="'{{asset('assets/images/i_3.svg')}}'"></span> </i> </span> <span class="nav-text">Segments</span> </a> </li>
            <li> <a href="{{route('admin.rewards.rewards')}}" > <span class="nav-icon"> <i class="material-icons">&#xe8d2; <span ui-include="'{{asset('assets/images/i_3.svg')}}'"></span> </i> </span> <span class="nav-text">Offer / Rewards</span> </a> </li>
            <li> <a href="{{route('admin.notifications.notifications')}}" > <span class="nav-icon"> <i class="material-icons">&#xe8d2; <span ui-include="'{{asset('assets/images/i_3.svg')}}'"></span> </i> </span> <span class="nav-text">Notifications</span> </a> </li>
            <li> <a href="{{route('admin.social.social')}}" > <span class="nav-icon"> <i class="material-icons">&#xe8d2; <span ui-include="'{{asset('assets/images/i_3.svg')}}'"></span> </i> </span> <span class="nav-text">Social</span> </a> </li>
            <li> <a href="#." > <span class="nav-icon"> <i class="material-icons">&#xe8d2; <span ui-include="'{{asset('assets/images/i_3.svg')}}'"></span> </i> </span> <span class="nav-text">Staff</span> </a> </li>
            <li> <a href="#." > <span class="nav-icon"> <i class="material-icons">&#xe8d2; <span ui-include="'{{asset('assets/images/i_3.svg')}}'"></span> </i> </span> <span class="nav-text">Trainings</span> </a> </li>
            <li> <a href="#." > <span class="nav-icon"> <i class="material-icons">&#xe8d2; <span ui-include="'{{asset('assets/images/i_3.svg')}}'"></span> </i> </span> <span class="nav-text">Coaches</span> </a> </li>
            <li> <a href="#." > <span class="nav-icon"> <i class="material-icons">&#xe8d2; <span ui-include="'{{asset('assets/images/i_3.svg')}}'"></span> </i> </span> <span class="nav-text">Leagues</span> </a> </li>
          </ul>
        </nav>
      </div>
    </div>
  </div>