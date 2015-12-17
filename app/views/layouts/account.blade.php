<!DOCTYPE html>
<html lang="en">
<head>
	@include('includes.account.header')
	{{HTML::style('css/main.css')}}
	@yield('style')
</head>
<body>
	@include('includes.default.nav-non-fixed')
	@include('includes.account.nav')
	@include('includes.account.nav-sub')
	@yield('content')
		@include('includes.account.footer')
	@yield('script')
</body>
</html>