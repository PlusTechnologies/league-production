<!DOCTYPE html>
<html lang="en">
<head>
	@include('includes.default.header')
	@yield('style')
</head>
<body>
	@include('includes.default.nav')
	@yield('content')
	@include('includes.default.footer')
	@yield('script')
</body>
</html>