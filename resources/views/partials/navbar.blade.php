<nav id="navbar" class="navbar is-link is-spaced{{ View::hasSection('full-width') ? ' is-fullwidth' : '' }}">
    <div class="container">
        <div class="navbar-brand">
            <a href="{{ route('home') }}" class="navbar-item">{{ config('app.name') }}</a>
            <div role="button" class="navbar-burger" data-target="navMenu" aria-label="menu" aria-expanded="false">
                <span aria-hidden="true"></span>
                <span aria-hidden="true"></span>
                <span aria-hidden="true"></span>
            </div>
        </div>


        <div class="navbar-menu" id="navMenu">
            <div class="navbar-end">
                @if(Auth::check())
                    <div class="navbar-item has-dropdown">
                        <a class="navbar-link">
                            <span class="icon"><i class="fas fa-user"></i></span>
                            {{ Auth::user()->name }}</a>
                        @include('partials.navbar.dropdown')
                    </div>
                    <a href="{{ route('panel') }}" class="navbar-item"><span class="icon"><i
                                class="fas fa-sliders-h"></i></span>@lang('panel.panel')</a>
                @else
                    <a href="{{ route('login') }}" class="navbar-item">@lang('auth.login')</a>
                @endif

            </div>
        </div>
    </div>
</nav>
