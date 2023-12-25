<?php 
  $arrUsers = Auth::user();
?>
<aside class="main-sidebar">
  <section class="sidebar">
    <div class="user-panel">
      <div class="pull-left image">
        <img src="{{ asset('public/Backend/images/user.png') }}" class="img-circle" alt="User Image">
      </div>
      <div class="pull-left info">
        <p>{{$arrUsers->name}}</p>
        <a href="{{ url('/admin/dashboard') }}"><i class="fa fa-circle text-success"></i> Online</a>
      </div>
    </div>
    <ul class="sidebar-menu" data-widget="tree">

      @if(count($arrModules) > 0)
        @foreach($arrModules as $key => $val)
          @if(checkAccess($val->slug,'list') == true)
            <li class="{{ active('admin/'.$val->slug.'*') }}">
              <a href="{{ url('/admin/'.$val->slug.'') }}">
                @if($val->slug == 'dashboard')
                  <i class="fa fa-dashboard"></i>
                @else
                  <i class="fa fa-circle-o text-aqua"></i>
                @endif
                <span>{{ $val->name }}</span>
              </a>
            </li>
          @endif
        @endforeach
      @endif
      <!-- <li class="{{ active('admin/dashboard*') }}">
        <a href="{{ url('/admin/dashboard') }}">
          <i class="fa fa-dashboard"></i><span>Dashboard</span>
        </a>
      </li>

      <li class="{{ active('admin/reports*') }}">
        <a href="{{ url('/admin/reports') }}">
          <i class="fa fa-circle-o text-aqua"></i><span>Manage Reports</span>
        </a>
      </li>

      <li class="{{ active('admin/category*') }}">
        <a href="{{ url('/admin/category') }}">
          <i class="fa fa-circle-o text-aqua"></i><span>Manage Category</span>
        </a>
      </li>

      <li class="{{ active('admin/transactions*') }}">
        <a href="{{ url('/admin/transactions') }}">
          <i class="fa fa-circle-o text-aqua"></i><span>Manage Transactions</span>
        </a>
      </li>

      <li class="{{ active('admin/publisher*') }}">
        <a href="{{ url('/admin/publisher') }}">
          <i class="fa fa-circle-o text-aqua"></i><span>Manage Publisher</span>
        </a>
      </li>

      <li class="{{ active('admin/leads*') }}">
        <a href="{{ url('/admin/leads') }}">
          <i class="fa fa-circle-o text-aqua"></i><span>Manage Leads</span>
        </a>
      </li>

      <li class="{{ active('admin/reviews*') }}">
        <a href="{{ url('/admin/reviews') }}">
          <i class="fa fa-circle-o text-aqua"></i><span>Manage Client Reviews</span>
        </a>
      </li>

      <li class="{{ active('admin/source*') }}">
        <a href="{{ url('/admin/source') }}">
          <i class="fa fa-circle-o text-aqua"></i><span>Manage Source</span>
        </a>
      </li>

      <li class="{{ active('admin/regions*') }}">
        <a href="{{ url('/admin/regions') }}">
          <i class="fa fa-circle-o text-aqua"></i><span>Manage Regions</span>
        </a>
      </li>

      <li class="{{ active('admin/blogs*') }}">
        <a href="{{ url('/admin/blogs') }}">
          <i class="fa fa-circle-o text-aqua"></i><span>Manage Blogs</span>
        </a>
      </li>

      <li class="{{ active('admin/faq*') }}">
        <a href="{{ url('/admin/faq') }}">
          <i class="fa fa-circle-o text-aqua"></i><span>Manage Faq</span>
        </a>
      </li>

      <li class="{{ active('admin/pages*') }}">
        <a href="{{ url('/admin/pages') }}">
          <i class="fa fa-circle-o text-aqua"></i><span>Manage Pages</span>
        </a>
      </li>

      <li class="{{ active('admin/email_templates*') }}">
        <a href="{{ url('/admin/email_templates') }}">
          <i class="fa fa-circle-o text-aqua"></i><span>Manage Email Templetes</span>
        </a>
      </li>

      <li class="{{ active('admin/popup*') }}">
        <a href="{{ url('/admin/popup') }}">
          <i class="fa fa-circle-o text-aqua"></i><span>Manage Popup</span>
        </a>
      </li>

      <li class="{{ active('admin/user_access*') }}">
        <a href="{{ url('/admin/user_access') }}">
          <i class="fa fa-circle-o text-aqua"></i><span>Manage User Access</span>
        </a>
      </li>
      
      <li class="{{ active('admin/users*') }}">
        <a href="{{ url('/admin/users') }}">
          <i class="fa fa-circle-o text-aqua"></i><span>Manage Users</span>
        </a>
      </li>
      
      <li class="{{ active('custom_reports/bulk_download*') }}">
        <a href="{{ url('/admin/custom_reports/bulk_download') }}">
          <i class="fa fa-circle-o text-aqua"></i><span>Manage Bulk Download</span>
        </a>
      </li>
      
      <li class="{{ active('custom_reports/custom_download*') }}">
        <a href="{{ url('/admin/custom_reports/custom_download') }}">
          <i class="fa fa-circle-o text-aqua"></i><span>Manage Custom Download</span>
        </a>
      </li>

      <li class="{{ active('custom_reports/bulk_uploading*') }}">
        <a href="{{ url('/admin/custom_reports/bulk_uploading') }}">
          <i class="fa fa-circle-o text-aqua"></i><span>Manage Bulk Uploading</span>
        </a>
      </li>

      <li class="{{ active('admin/site_setting*') }}">
        <a href="{{ url('/admin/site_setting') }}">
          <i class="fa fa-circle-o text-aqua"></i><span>Manage Setting</span>
        </a>
      </li> -->

    </ul>
  </section>
</aside>