<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <title>Login | Work Log Portal</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <!-- AdminLTE CSS -->
  <link rel="stylesheet" href="{{ asset('adminlte/plugins/fontawesome-free/css/all.min.css') }}" />
  <link rel="stylesheet" href="{{ asset('adminlte/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}" />
  <link rel="stylesheet" href="{{ asset('adminlte/css/adminlte.min.css') }}" />
</head>

<body class="hold-transition login-page">
  <div class="login-box">
    <div class="login-logo">
      <i class="fas fa-user"></i>
      <b>Work Log</b> Portal
    </div>
    <div class="card">
      <div class="card-body login-card-body">
         
        <form method="POST" action="{{ route('login.post') }}">
          @csrf
          <div class="input-group mb-3">
            <input
              type="email"
              id="email"
              name="email"
              value="{{ old('email') }}"
              class="form-control"
              placeholder="Email"
              required
              autofocus />
            <div class="input-group-append">
              <div class="input-group-text"><span class="fas fa-envelope"></span></div>
            </div>
          </div>
          @error('email')
          <span class="text-danger small">{{ $message }}</span>
          @enderror

          <div class="input-group mb-3">
            <input
              type="password"
              name="password"
              id="password"
              class="form-control"
              placeholder="Password"
              required />
            <div class="input-group-append">
              <div class="input-group-text"><span class="fas fa-lock"></span></div>
            </div>
          </div>
          @error('password')
          <span class="text-danger small">{{ $message }}</span>
          @enderror

          <div class="row">
            <!-- <div class="col-8">
              <div class="icheck-primary">
                <input type="checkbox" id="remember" name="remember" />
                <label for="remember">Remember Me</label>
              </div>
            </div> -->
            <div class="col-4">
              <button type="submit" class="btn btn-primary btn-block" onclick="login()">Sign In</button>
            </div>
          </div>
        </form>

        <!-- <p class="mb-1 mt-3">
          <a href="x">Forgot Password ?</a>
        </p> -->
      </div>
    </div>
  </div>

  <!-- Scripts -->
  <script src="{{ asset('adminlte/plugins/jquery/jquery.min.js') }}"></script>
  <script src="{{ asset('adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset('adminlte/js/adminlte.min.js') }}"></script>
  <script>
     
  </script>
</body>

</html>