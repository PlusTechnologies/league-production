<!DOCTYPE html>
<html lang="en">
<head>
	@include('includes.public.header')
	@yield('style')
</head>
<body>
	@include('includes.public.nav')
	@yield('content')
	@include('includes.public.footer')
	@yield('script')
</body>
</html>