<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>@yield('title')</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- AdminLTE CSS -->
  <link rel="stylesheet" href="{{ asset('adminlte/plugins/fontawesome-free/css/all.min.css') }}">
  <link rel="stylesheet" href="{{ asset('adminlte/css/adminlte.min.css') }}">
</head>

<body class="hold-transition sidebar-mini layout-fixed">
  <div class="wrapper">

    @include('layouts.header')
    @include('layouts.sidebar')

    <div class="content-wrapper">
      <section class="content pt-3 px-3">
        @yield('content')
      </section>
    </div>

    <footer class="main-footer text-center">
      <strong>User Portal</strong> &copy; {{ date('Y') }}
    </footer>
  </div>

  <!-- Scripts -->
  <script src="{{ asset('adminlte/plugins/jquery/jquery.min.js') }}"></script>
  <script src="{{ asset('adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset('adminlte/js/adminlte.min.js') }}"></script>
  @stack('scripts')
  <script>
    $(document).ajaxError(function(event, jqxhr, settings, thrownError) {
      if (jqxhr.status === 401) {
        // unset the token
        localStorage.removeItem("access_token");
        
        // Redirect to login page if unauthorized
        window.location.href = "{{ route('login') }}";
      }
    });
  </script>
</body>

</html>