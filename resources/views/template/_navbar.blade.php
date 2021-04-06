    <nav class="navbar navbar-expand-sm navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="/dashboard">
                <img src="{{ asset('assets/images/logo-2.png') }}" height="30" class="d-inline-block align-top" alt="">
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo02" aria-controls="navbarTogglerDemo02" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarTogglerDemo02">
                <div class="navbar-nav ml-auto mt-2 mt-sm-0">
                    <a href="/dashboard" class="btn btn-outline-light mr-sm-3 mr-0">Dashboard</a>
                    <button class="btn btn-outline-warning my-2 my-sm-0" onclick="event.preventDefault(); document.getElementById('form-logout').submit();">Logout</button>
                    <form id="form-logout" class="d-none" method="post" action="/logout">{{ csrf_field() }}</form>
                </div>
            </div>
        </div>
    </nav>