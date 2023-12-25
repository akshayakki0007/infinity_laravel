<?php 
  $arrUsers = Auth::user();
?>
<header class="main-header">
  <!-- Logo -->
  <a href="{{ url('admin/dashboard') }}" class="logo">
    <span class="logo-mini"><b>A</b>LT</span>
    <span class="logo-lg"><b>Admin</b>LTE</span>
  </a>
  <nav class="navbar navbar-static-top">
    <a href="{{ url('admin/dashboard') }}" class="sidebar-toggle" data-toggle="push-menu" role="button">
      <span class="sr-only">Toggle navigation</span>
    </a>
    <div class="navbar-custom-menu">
      <ul class="nav navbar-nav">
        <li class="dropdown user user-menu">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">
            <img src="{{ asset('public/Backend/images/user.png') }}" class="user-image" alt="User Image">
            <span class="hidden-xs">{{$arrUsers->name}}</span>
          </a>
          <ul class="dropdown-menu">
            <li class="user-footer">
              <div class="pull-left">
                <a href="{{ url('admin/site_setting') }}" class="btn btn-default btn-flat">Setting</a>
              </div>
              <div class="pull-right">
                <form method="POST" action="{{ route('logout') }}">
                  @csrf
                  <button type="submit" class="btn btn-default btn-flat">Logout</button>
                </form>
              </div>
            </li>
          </ul>
        </li>
      </ul>
    </div>
  </nav>
</header>