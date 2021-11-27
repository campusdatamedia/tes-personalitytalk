<!DOCTYPE HTML>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Assesmen | Tes Online</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.1/font/bootstrap-icons.css">
  <link rel="stylesheet" type="text/css" href="https://psikologanda.com/assets/css/style.css">
</head>
<body id="spandiv">
  <div id="content">
    <div id="navbar-main"></div>
    <div class="main-wrapper my-lg-2 my-3">
      <div class="container">
        <div class="row login-wrapper align-items-center justify-content-between">
          <div class="col-lg-5 d-none d-lg-block">
              <div class="d-flex align-items-center h-100">
                  <img class="img-fluid" src="{{asset('assets/images/ilustrasi/exams-animate.svg')}}">
              </div>
          </div>
          <div class="col-lg-5">
              <div class="wrapper">
                  <div class="card border-0 shadow-sm rounded-1">
                      <div class="card-header text-center pt-4 bg-transparent mx-4">
                          <img width="200" class="mb-3" src="https://www.psikologanda.com/assets/images/logo/1598935898-logo.png">
                          <h5 class="h2 mb-0">Selamat Datang</h5>
                          <p class="m-0">Untuk melakukan tes online, silakan login dengan informasi pribadi Anda melalui Username dan Password 🔔</p>
                      </div>
                      <div class="card-body">
                        <form class="login-form" action="/login" method="post">
                            {{ csrf_field() }}
                            @if(isset($message))
                            <div class="alert alert-danger">
                                {{ $message }}
                            </div>
                            @endif
                            <div class="form-group  mb-4">
                                <label class="control-label">Username / Email</label>
                                <div class="input-group">
                                    <span class="input-group-text {{ $errors->has('username') ? 'border-danger' : '' }}"><i class="bi bi-person-fill"></i></span>
                                    <input class="form-control {{ $errors->has('username') ? 'is-invalid' : '' }}" name="username" type="text" placeholder="Username atau Email" autofocus>
                                </div>  
                                @if($errors->has('username'))
                                <div class="form-control-feedback text-danger">{{ ucfirst($errors->first('username')) }}</div>
                                @endif
                            </div>
                            <div class="form-group mb-4">
                                <label class="control-label">Password</label>
                                <div class="input-group">
                                    <span class="input-group-text {{ $errors->has('password') ? 'border-danger' : '' }}"><i class="bi bi-key-fill"></i></span>
                                    <input type="password" name="password" class="form-control {{ $errors->has('password') ? 'border-danger' : '' }}" placeholder="Password">
                                    <a class="input-group-text btn btn-light btn-toggle-password border {{ $errors->has('password') ? 'border-danger' : '' }}"><i class="bi bi-eye-fill"></i></a>
                                </div>
                                @if($errors->has('password'))
                                <div class="form-control-feedback text-danger">{{ ucfirst($errors->first('password')) }}</div>
                                @endif
                            </div>
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary rounded px-4 btn-block">Masuk</button>
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
  <div id="footer-main"></div>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
  <script src="https://psikologanda.com/assets/partials/template.js"></script>
  <style>
    .login-wrapper{min-height: calc(100vh - 19rem)}
  </style>
</body>
</html>
