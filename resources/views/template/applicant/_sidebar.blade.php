
    <!-- Sidebar -->
    <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

      <!-- Sidebar - Brand -->
      <a class="sidebar-brand d-flex align-items-center justify-content-center" href="/dashboard">
        <div class="sidebar-brand-icon">
          <img src="{{ asset('assets/images/psikologanda.png') }}" id="logo-full" height="30" class="img-fluid d-none d-md-block" title="PersonalityTalk">
          <img src="{{ asset('assets/images/icon.png') }}" id="logo-mini" width="32" class="img-fluid d-block d-md-none" title="PersonalityTalk">
        </div>
      </a>

      <!-- Divider -->
      <hr class="sidebar-divider my-0">

      <!-- Nav Item - Dashboard -->
      <li class="nav-item active">
        <a class="nav-link" href="/dashboard">
          <i class="fas fa-fw fa-tachometer-alt"></i>
          <span>Dashboard</span></a>
      </li>
      
      <!-- Divider -->
      <hr class="sidebar-divider">

      @if($global_tes != false)
          <!-- Heading -->
          <div class="sidebar-heading">
            Test
          </div>
    
          <!-- Nav Item - Pages Collapse Menu -->
          @foreach($global_tes as $data)
          <li class="nav-item">
            <a class="nav-link" href="/tes/{{ $data->id_tes }}">
              <i class="fas fa-fw fa-clipboard"></i>
              <span>{{ $data->nama_tes }}</span></a>
          </li>
          @endforeach
      @endif

      <!-- Divider -->
      <!-- <hr class="sidebar-divider d-none d-md-block"> -->

      <!-- Sidebar Toggler (Sidebar) -->
      <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle" title="Show/Hide Sidebar"></button>
      </div>

    </ul>
    <!-- End of Sidebar -->