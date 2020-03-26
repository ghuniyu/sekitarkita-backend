<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title') SekitarKita.id</title>

    <!-- Styles -->
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
</head>
<body>
<div id="app" class="bg-white">
    <div class="header py-4">
        <div class="container-fluid">
            <div class="d-flex">
                <a class="header-brand" href="/">
                    <img
                        src="{{ asset('sekitarkitalogo.png') }}"
                        class="header-brand-img"
                        alt="tabler logo">
                </a>
                <div class="nav-item d-none d-md-flex">
                    <ul class="nav navbar-menu">
                        <li class="nav-item dropdown">
                            <a class="nav-link" data-toggle="dropdown"> Mapping <i class="mr-2 fe fe-chevron-down"></i></a>
                            <div class="dropdown-menu dropdown-menu-arrow">
                                <a href="{{ route('mapping.member') }}" class="dropdown-item ">Member</a>
                                <a href="{{ route('mapping.device') }}" class="dropdown-item ">Device</a>
                                <a href="{{ route('mapping.pdp') }}" class="dropdown-item ">PDP</a>
                                <a href="{{ route('mapping.odp') }}" class="dropdown-item ">ODP</a>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <main class="py-4">
        @yield('content')
    </main>
</div>
<!-- Scripts -->
<script src="{{ mix('js/app.js') }}"></script>
@stack('js')
</body>
</html>
