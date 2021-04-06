<!DOCTYPE html>
<html>
<head>
    @include('template/_head')
    @yield('css-extra')
</head>
<body style="background-color:#eeeeee;">
    @include('template/_navbar')
    <div class="container mt-5">
        @include('template/_welcome')
        @yield('content')
    </div>
    @include('template/_js')
    @yield('js-extra')
</body>
</html>