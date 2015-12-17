<!DOCTYPE html>
<html lang="en">
<head>
	@include('includes.club.header')
	{{HTML::style('css/main.css')}}
	@yield('style')
</head>
<body>
	@include('includes.default.nav-non-fixed')
	@include('includes.club.nav')
	@include('includes.club.nav-sub')
	@yield('content')
	@include('includes.club.footer')
	@yield('script')
</body>
</html>