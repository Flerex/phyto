<div class="navbar-dropdown is-right">
    <div class="navbar-item">
        <div class="media">
            <div class="media-left">
                @include('partials.avatar', ['user' => Auth::user()])
            </div>
            <div class="media-content">
                <p class="has-text-weight-bold">{{ Auth::user()->name }}</p>
                <p class="has-text-grey-light">{{ Auth::user()->email }}</p>
            </div>
        </div>
    </div>
    <hr class="navbar-divider">
    <a href="#" class="navbar-item" onclick="document.getElementById('logoutForm').submit()">
        <span class="icon"><i class="fas fa-sign-out-alt"></i></span> @lang('auth.logout')</a>
    <form id="logoutForm" action="{{ route('logout') }}" method="POST">
        @csrf
    </form>
</div>
