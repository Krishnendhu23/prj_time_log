<nav class="main-header navbar navbar-expand navbar-white navbar-light">
  <ul class="navbar-nav ml-auto">
    <li class="nav-item">
      <div class="nav-link">Logged in as: {{ Auth::user()->name }}</div>
    </li>
    <li class="nav-item">
      <form method="POST" action="{{ route('logout.post') }}">
        @csrf
        <button type="submit" class="btn btn-sm btn-danger">Logout</button>
      </form>
    </li>
  </ul>
</nav>