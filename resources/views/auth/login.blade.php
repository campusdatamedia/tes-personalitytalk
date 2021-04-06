<!DOCTYPE HTML>
<html lang="en">
  <head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Assesmen | Tes Online</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
  <link rel="stylesheet" type="text/css" href="https://www.psikologanda.com/templates/qbs/bootstrap/style.min.css">
  <link rel="stylesheet" type="text/css" href="https://www.psikologanda.com/templates/qbs/bootstrap/homev2.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/lykmapipo/themify-icons@0.1.2/css/themify-icons.css" integrity="sha256-8g4waLJVanZaKB04tvyhKu2CZges6pA5SUelZAux/1U=" crossorigin="anonymous">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
  <link rel="stylesheet" type="text/css" href="https://www.psikologanda.com/assets/css/login.css">
  <link rel="stylesheet" type="text/css" href="https://www.psikologanda.com/assets/css/style.css">
  <link rel="stylesheet" type="text/css" href="{{asset('assets/css/style.css')}}">
</head>

<body>
    <div class="main-wrapper">
      <div class="wrapper">
        <div id="content">
          <div id="sidebar-main"></div>
          <div id="navbar-main"></div>
          <div class="main-wrapper">
              <div class="auth-wrapper d-flex no-block justify-content-center align-items-center">
                  <div class="container">
                      <div class="row">
                          <div class="col-lg-6 d-none d-lg-block">
                              <div class="d-flex align-items-center h-100">
                                  <img class="img-fluid" src="{{asset('assets/images/ilustrasi/undraw_wall_post_83ul.svg')}}">
                              </div>
                          </div>
                          <div class="col-lg-6">
                              <div class="wrapper">
                                  <div class="card border-0 shadow-sm rounded-1">
                                      <div class="card-header text-center pt-4 bg-transparent mx-4">
                                          <img width="200" class="mb-3" src="https://www.psikologanda.com/assets/images/logo/1598935898-logo.png">
                                          <h5 class="h2 mb-0">Selamat Datang</h5>
                                          <p class="m-0">Untuk tetap terhubung dengan kami, silakan login dengan informasi pribadi Anda melalui Username dan Password 🔔</p>
                                      </div>
                                      <div class="card-body">
                                          <form class="login-form" action="/login" method="post">
                                              {{ csrf_field() }}
                                              @if(isset($message))
                                              <div class="alert alert-danger">
                                                  {{ $message }}
                                              </div>
                                              @endif
                                              <div class="form-group ">
                                                  <label class="control-label">Username</label>
                                                  <div class="input-group input-group-lg">
                                                      <div class="input-group-prepend">
                                                          <span class="input-group-text" id="basic-addon1"><i class="ti-email"></i></span>
                                                      </div>
                                                      <input class="form-control {{ $errors->has('username') ? 'is-invalid' : '' }}" name="username" type="text" placeholder="Username" autofocus>
                                                  </div>
                                                  @if($errors->has('username'))
                                                  <div class="form-control-feedback text-danger">{{ ucfirst($errors->first('username')) }}</div>
                                                  @endif
                                              </div>
                                              <div class="form-group">
                                                  <label class="control-label">Password</label>
                                                  <div class="input-group input-group-lg">
                                                      <div class="input-group-prepend">
                                                          <span class="input-group-text" id="basic-addon1"><i class="ti-key"></i></span>
                                                      </div>
                                                      <input type="password" name="password" class="form-control {{ $errors->has('password') ? 'border-danger' : '' }}" placeholder="Password">
                                                      <div class="input-group-append">
                                                          <a href="#" class="input-group-text text-dark {{ $errors->has('password') ? 'border-danger bg-danger' : '' }}" id="btn-toggle-password"><i class="fa fa-eye"></i></a>
                                                      </div>
                                                  </div>
                                                  @if($errors->has('password'))
                                                  <div class="form-control-feedback text-danger">{{ ucfirst($errors->first('password')) }}</div>
                                                  @endif
                                              </div>
                                              <div class="form-group btn-container">
                                                  <button type="submit" class="btn btn-primary btn-lg rounded px-4 shadow-sm btn-block">Masuk</button>
                                              </div>
                                          </form>
                                      </div>
                                  </div>
                              </div>
                          </div>
                      </div>
                  </div>
              </div>
          </div>
        </div>
      </div>
    </div>
    <div id="footer-main"></div>
  @include('template/applicant/_js')
  <script src="https://psikologanda.com/templates/matrix-admin/assets/libs/jquery/dist/jquery.min.js"></script>
  <script src="https://psikologanda.com/assets/partials/template.js"></script>
  <script type="text/javascript">
    $(document).on('click','#sidebarCollapse',function(e){
      e.preventDefault();
      $('#sidebar').hasClass('active') 
        ? $('#sidebar').removeClass('active') 
        : $('#sidebar').addClass('active');
      $(this).find('i').hasClass('ti-menu') 
        ? $(this).find('i').removeClass('ti-menu').addClass('ti-close') 
        : $(this).find('i').addClass('ti-menu').removeClass('ti-close');
    })

    $(document).on("click", "#sidebar > .sidebar-menu > .menu-label.sidebar-dropdown > a", function(e){
      e.preventDefault();
      $(this).parent(".menu-label").hasClass("active") 
        ? $(this).parent(".menu-label").removeClass("active") 
        : $(this).parent(".menu-label").addClass("active");
    })
  </script>
  <style type="text/css">
    .btn.btn-primary.btn-lg{background-color: var(--color-1)!important; border-color: var(--color-1)!important}
    form .alert.alert-danger {margin: 0 0 1em 0}
  </style>
</body>

</html>
