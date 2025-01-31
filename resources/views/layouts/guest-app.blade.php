<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
   <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <!-- CSRF Token -->
      <meta name="csrf-token" content="{{ csrf_token() }}">
      <title>{{ config('app.name', 'Laravel') }}</title>
      <!-- Fonts -->
      <link rel="dns-prefetch" href="//fonts.bunny.net">
      {{--<link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">--}}
      <!-- Scripts -->
      <link rel="stylesheet" href="{{ asset('css/app.css') }}">
   </head>
   <body class="d-flex flex-column">
    <div class="page page-center">
        @yield('content')
    </div>
    <script src="{{ asset('js/app.js') }}" defer></script>
   </body>
</html>