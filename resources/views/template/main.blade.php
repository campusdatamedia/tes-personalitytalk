<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	@include('template/_head')
	<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/style.css') }}">
	@yield('css-extra')
</head>
<body>
    @include('template/_navbar')
    @yield('content')
    @include('template/_js')
    @include('template/admin/_js')
    @yield('js-extra')
</body>
</html>