<!DOCTYPE html>
<html lang="en">

<head>

  @include('template/applicant/_head')

  <title>Assesmen | Tes Online</title>

  <style type="text/css">
    body {height: calc(100vh); background-repeat: no-repeat; background-size: cover; background-position: center;}
    .wrapper {background: rgba(0,0,0,.3);}
    .card {width: 500px; background-color: rgba(0,0,0,.6);}
    .form-control, .form-control:focus {background-color: transparent; color: #fff;}
    .input-group .form-control {border-right-width: 0;}
    .input-group-append .btn {color: #fff; border: 1px solid #d1d3e2; border-left-width: 0; border-radius: 10rem;}
    .custom-checkbox .custom-control-label::before {background-color: transparent;}
  </style>

</head>

<body background="{{ asset('assets/images/background/applicant.jpg') }}">

  <div class="wrapper h-100">
    <div class="d-flex justify-content-center h-100">
      <div class="card my-auto">
        <div class="card-body">
          <div class="col px-sm-5 px-4 mb-5">
            <a href="https://psikologanda.com">
              <img class="img-fluid" src="{{ asset('assets/images/logo-2.png') }}">
            </a>
          </div>
          <!--<div class="text-center">-->
          <!--  <h1 class="h4 text-white mb-5">Welcome Back!</h1>-->
          <!--</div>-->
          @if(isset($message))
          <div class="alert alert-danger">
            {{ $message }}
          </div>
          @endif
          <form class="user" method="post" action="/login">
            {{ csrf_field() }}
            <div class="form-group">
              <input type="text" class="form-control form-control-user {{ $errors->has('username') ? 'border-danger' : '' }}" name="username" placeholder="Masukkan Email atau Username..." value="{{ old('username') }}">
              @if($errors->has('username'))
                <small class="text-danger">{{ $errors->first('username') }}</small>
              @endif
            </div>
            <div class="form-group">
              <div class="input-group">
                <input type="password" class="form-control form-control-user {{ $errors->has('password') ? 'border-danger' : '' }}" name="password" placeholder="Password">
                <div class="input-group-append">
                  <button class="btn btn-toggle-password show {{ $errors->has('password') ? 'border-danger text-danger' : '' }}" type="button"><i class="fa fa-eye"></i></button>
                </div>
              </div>
              @if($errors->has('password'))
                <small class="text-danger">{{ $errors->first('password') }}</small>
              @endif
            </div>
            <button type="submit" class="btn btn-outline-primary btn-user btn-block">
              Login
            </button>
			<!--
            <a href="/daftar" class="btn btn-outline-success btn-user btn-block">
              Daftar
            </a>
			-->
          </form>
          <hr>
<!--           <div class="text-center">
            <a class="small" href="forgot-password.html">Forgot Password?</a>
          </div> -->
<!--           <div class="text-center">
            <a class="small" href="register.html">Create an Account!</a>
          </div> -->
        </div>
      </div>
    </div>
  </div>

  @include('template/applicant/_js')
  
  <script type="text/javascript">
    // Button toggle password
    $(document).on("click", ".btn-toggle-password", function(e){
      e.preventDefault();
      $(this).hasClass("show") ? $("input[name=password]").attr("type","text") : $("input[name=password]").attr("type","password");
      $(this).hasClass("show") ? $(this).find(".fa").removeClass("fa-eye").addClass("fa-eye-slash") : $(this).find(".fa").removeClass("fa-eye-slash").addClass("fa-eye");
      $(this).hasClass("show") ? $(this).removeClass("show").addClass("hide") : $(this).removeClass("hide").addClass("show");
    });
  </script>

</body>

</html>
