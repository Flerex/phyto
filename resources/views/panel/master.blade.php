@extends('layouts.master')

@section('content')
    <div class="h-100 columns">
        <aside class="menu column is-one-fifth has-background">
            <p class="menu-label">@lang('panel.label.users')</p>
            <ul class="menu-list">
                <li>
                    <a href="{{ route('panel.users.index') }}"
                       @if(Str::startsWith(Route::currentRouteName(), 'panel.users.')) class="is-active"@endif>
                        @lang('panel.users.management')
                    </a>
                </li>
            </ul>
            <p class="menu-label">@lang('panel.label.catalogs_species')</p>
            <ul class="menu-list">
                <li><a href="{{ route('panel.catalogs.index') }}"
                       @if(Str::startsWith(Route::currentRouteName(), 'panel.catalogs.')) class="is-active"@endif>@lang('panel.catalogs.management')</a>
                </li>
                <li><a href="{{ route('panel.species.index') }}"
                       @if(Str::startsWith(Route::currentRouteName(), 'panel.species.')) class="is-active"@endif>@lang('panel.species.management')</a>
                </li>
            </ul>
            <p class="menu-label">@lang('panel.label.projects')</p>
            <ul class="menu-list">
                <li><a href="{{ route('panel.projects.index') }}"
                       @if(Str::startsWith(Route::currentRouteName(), 'panel.projects.')) class="is-active"@endif>@lang('panel.projects.management')</a>
                </li>
            </ul>
        </aside>
        <div class="column">
            @yield('panel_content')
        </div>
    </div>
@endsection
