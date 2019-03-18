<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Tinker</title>

    <!-- Scripts -->
	<script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href=" {{asset('content/assets/css/themify-icons.css')}}" rel="stylesheet">
    <link rel="stylesheet" href=" {{asset('content/css/fontawesome-all.min.css') }}">
    <link rel="icon" type="image/png" sizes="100x200" href="{{ asset('content/imgs/favicon.png') }}">
    <link href="{{asset('css_admin/mdesigns.css')}}" rel="stylesheet">
</head>
<body>
<div id="app">
    
    <main class="py-4">
        @yield('content')
    </main>
</div>
</body>
</html>
