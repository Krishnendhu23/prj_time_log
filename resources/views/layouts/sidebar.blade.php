<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <a href="{{ route('dashboard') }}" class="brand-link text-center">
    <span class="brand-text font-weight-light">User Panel</span>
  </a>

  <div class="sidebar">
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" role="menu">
        <li class="nav-item">
          <a href="{{ route('dashboard') }}" class="nav-link">
            <i class="nav-icon fas fa-tachometer-alt"></i>
            <p>Dashboard</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="{{ route('work-log.index') }}" class="nav-link">
            <i class="nav-icon fas fa-clipboard-list"></i>
            <p>Work Log Management</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="{{ route('leave.index') }}" class="nav-link">
            <i class="nav-icon fas fa-calendar-alt"></i>
            <p>Leave Management </p>
          </a>
        </li>
      </ul>
    </nav>
  </div>
</aside>