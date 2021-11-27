<nav class="navbar navbar-expand navbar-dark bg-theme-1 fixed-top">
    <div class="container">
        <ul class="nav navbar-nav">
            <li class="nav-item" style="{{ is_int(strpos(Request::path(), 'dashboard')) ? 'visibility:hidden' : 'visibility:visible' }}">
                <a class="nav-link fw-bold" href="/"><i class="fa fa-arrow-left"></i> Kembali</a>
            </li>
        </ul>
        <a class="navbar-brand mx-auto" href="/">
            <img src="{{ asset('assets/images/logo-2.png') }}" height="30" alt="img">
        </a>
        <ul class="nav navbar-nav">
            <li class="nav-item">
                <a class="nav-link fw-bold" id="btn-logout" href="#">Keluar <i class="fa fa-sign-out"></i></a>
                <form id="form-logout" class="d-none" method="post" action="/logout">{{ csrf_field() }}</form>
            </li>
        </ul>
    </div>
</nav>