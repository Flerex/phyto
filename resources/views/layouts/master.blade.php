<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name') }}</title>

    <!-- Styles -->
    <link href="{{ mix('css/vendors.css') }}" rel="stylesheet">
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
</head>
<body>
@include('partials.navbar')

@hasSection('full-width')
    <section id="wrap">
        @yield('content')
    </section>
@else
    <div id="wrap" class="container">
        <section class="section">
            <main id="main" class="container is-widescreen">
                @yield('content')
            </main>
        </section>
    </div>
@endif

@include('partials.footer')

@include('partials.flash')

<!-- Scripts -->
<script src="{{ asset('js/app.js') }}"></script>
@stack('scripts')
</body>
</html>
