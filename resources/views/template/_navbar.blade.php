<nav class="navbar navbar-expand navbar-dark bg-theme-1 fixed-top">
    <div class="container">
        @if(!is_int(strpos(Request::path(), 'dashboard')))
        <ul class="nav navbar-nav">
            <li class="nav-item">
                <a class="nav-link" href="/"><i class="fa fa-arrow-left"></i> Kembali</a>
            </li>
        </ul>
        @endif
        <a class="navbar-brand mx-auto" href="/">
            <img src="{{ asset('assets/images/logo-2.png') }}" height="30" alt="img">
        </a>
        <ul class="nav navbar-nav">
            <li class="nav-item">
                <a class="nav-link" href="#" onclick="event.preventDefault(); document.getElementById('form-logout').submit();">Keluar <i class="fa fa-sign-out"></i></a>
                <form id="form-logout" class="d-none" method="post" action="/logout">{{ csrf_field() }}</form>
            </li>
        </ul>
    </div>
</nav>