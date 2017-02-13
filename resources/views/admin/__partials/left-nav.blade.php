<div id="aside" class="app-aside modal fade nav-dropdown"> 
    <!-- fluid app aside -->
    <div class="left navside dark dk" layout="column">
      <div class="navbar no-radius"> 
        <!-- brand --> 
        <a class="navbar-brand">
        {{--<div ui-include="{{url('/assets/images/logo_wh.png')}}"></div>--}}
        <img src="{{url('/assets/images/logo_wh.png')}}" alt="." class=""> <span class="hidden-folded inline hide">Grit</span> </a>
        <!-- / brand --> 
      </div>
      <div flex class="hide-scroll">
        <nav class="scroll nav-light">
          <ul class="nav">
            {{--<li class="nav-header hidden-folded"> <small class="text-muted">Main</small> </li>--}}
            <li> <a href="{{route('admin.dashboard')}}" > <span class="nav-icon"> <i class="fa fa-cube"></i> </span> <span class="nav-text">Dashboard</span> </a> </li>
            <li> <a href="{{route('admin.members.members')}}" > <span class="nav-icon"> <i class="fa fa-user-circle-o"></i> </span> <span class="nav-text">Members</span> </a> </li>
            <li> <a href="{{route('admin.reservations.reservations')}}" > <span class="nav-icon"> <i class="fa fa-calendar"></i> </span> <span class="nav-text">Reservations</span> </a> </li>
            <li> <a href="{{route('admin.profile.profile')}}" > <span class="nav-icon"> <i class="fa fa-user"></i> </span> <span class="nav-text">Profile / Settings</span> </a> </li>
            <li> <a href="{{route('admin.shop.shop')}}" > <span class="nav-icon"> <i class="fa fa-shopping-basket"></i> </span> <span class="nav-text">Shop</span> </a> </li>
            <li> <a href="{{route('admin.segments.segments')}}" > <span class="nav-icon"> <i class="fa fa-pie-chart"></i> </span> <span class="nav-text">Segments</span> </a> </li>
            <li> <a href="{{route('admin.beacon.beacon')}}" > <span class="nav-icon"> <i class="fa fa-pie-chart"></i> </span> <span class="nav-text">Beacon</span> </a> </li>
            <li> <a href="{{route('admin.rewards.rewards')}}" > <span class="nav-icon"> <i class="fa fa-star"></i> </span> <span class="nav-text">Offer / Rewards</span> </a> </li>
            <li> <a href="{{route('admin.notifications.notifications')}}" > <span class="nav-icon"> <i class="fa fa-bullhorn"></i> </span> <span class="nav-text">Notifications</span> </a> </li>
            <li> <a href="{{route('admin.social.social')}}" > <span class="nav-icon"> <i class="fa fa-thumbs-up"></i> </span> <span class="nav-text">Social</span> </a> </li>
            <li> <a href="{{route('admin.staff.staff')}}" > <span class="nav-icon"> <i class="fa fa-users"></i> </span> <span class="nav-text">Staff</span> </a> </li>
            <li> <a href="#." > <span class="nav-icon"> <i class="fa fa-graduation-cap"></i> </span> <span class="nav-text">Trainings</span> </a> </li>
            <li> <a href="{{route('admin.coaches.coaches')}}" > <span class="nav-icon"> <i class="fa fa-podcast"></i> </span> <span class="nav-text">Coaches</span> </a> </li>
            <li> <a href="#." > <span class="nav-icon"> <i class="fa fa-gamepad"></i> </span> <span class="nav-text">Leagues</span> </a> </li>
          </ul>
        </nav>
      </div>
    </div>
  </div>