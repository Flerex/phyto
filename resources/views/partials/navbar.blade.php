<nav class="navbar is-link">
    <div class="navbar-brand">
        <a href="{{ route('home') }}" class="navbar-item">{{ config('app.name') }}</a>
    </div>

    <div class="navbar-menu">
        <div class="navbar-end">
            @if(Auth::check())
                <div class="navbar-item has-dropdown is-hoverable">
                    <a class="navbar-link">
                        <span class="icon"><i class="fas fa-user"></i></span>
                        {{ Auth::user()->name }}</a>
                    <div class="navbar-dropdown">
                        <a href="#" class="navbar-item"
                           onclick="document.getElementById('logoutForm').submit()">@lang('auth.logout')</a>
                        <form id="logoutForm" action="{{ route('logout') }}" method="POST">
                            @csrf
                        </form>
                    </div>
                </div>
                <a href="{{ route('panel') }}" class="navbar-item"><span class="icon"><i
                                class="fas fa-sliders-h"></i></span>@lang('panel.panel')</a>
            @else
                <a href="{{ route('login') }}" class="navbar-item">@lang('auth.login')</a>
            @endif

        </div>
    </div>
</nav>