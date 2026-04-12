<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>@yield('header-title')</title>
   @vite(['resources/css/app.css', 'resources/js/app.js'])
   <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body>
    <header>
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <h1 class="header-title">@yield('header-title')</h1>
    </header>
            
    @yield('content')
    @yield('scripts')
</body>

</html>