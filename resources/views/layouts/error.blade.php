<!doctype html>
<html lang="en">

<head>
    <title>@yield('title') | {{ settings('site_title') }}</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="icon" href="{{ asset('assets/uploads') }}/{{ settings('favicon') }}">

    <link rel="stylesheet" href="{{ asset('assets/error/css/style.css') }}">

</head>

<body>
    <div id="notfound">
		@yield('content')
	</div>
</body>

</html>
