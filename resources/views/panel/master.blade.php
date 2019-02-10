@extends('layouts.master')

@section('content')
    <div class="columns">
        <aside class="menu column is-one-fifth">
            <p class="menu-label">@lang('panel.label.users')</p>
            <ul class="menu-list">
                <li>
                    <a href="{{ route('panel.users') }}" @if(Route::currentRouteName() == 'panel.users')class="is-active"@endif>
                        @lang('panel.users.manage')
                    </a>
                </li>
            </ul>
            {{-- TODO: remove harcoded translations & add routes --}}
            <p class="menu-label">Catalogs & Species</p>
            <ul class="menu-list">
                <li><a href="#">Catalog management</a></li>
                <li><a href="#">Species management</a></li>
            </ul>
            <p class="menu-label">Projects</p>
            <ul class="menu-list">
                {{-- TODO: quizáis deixar aquí a lista de proxectos que o usuario manexe?? --}}
                <li><a href="#"></a></li>
                <li><a href="#"></a></li>
            </ul>
        </aside>
        <div class="column">
            @yield('panel_content')
        </div>
    </div>
@endsection
