@extends('layouts.master')

@section('content')
    <div class="columns">
        <aside class="menu column is-one-fifth">
            <p class="menu-label">@lang('panel.label.users')</p>
            <ul class="menu-list">
                <li>
                    <a href="{{ route('panel.users.index') }}"@if(str_begins_with('panel.users.', Route::currentRouteName())) class="is-active"@endif>
                        @lang('panel.users.management')
                    </a>
                </li>
            </ul>
            {{-- TODO: remove harcoded translations & add routes --}}
            <p class="menu-label">@lang('panel.label.catalogs_species')</p>
            <ul class="menu-list">
                <li><a href="{{ route('panel.catalogs.index') }}"@if(str_begins_with('panel.catalogs.', Route::currentRouteName())) class="is-active"@endif>@lang('panel.catalogs.management')</a></li>
                <li><a href="{{ route('panel.species.index') }}"@if(str_begins_with('panel.species.', Route::currentRouteName())) class="is-active"@endif>@lang('panel.species.management')</a></li>
            </ul>
            <p class="menu-label">@lang('panel.label.projects')</p>
            <ul class="menu-list">
                <li><a href="{{ route('panel.projects.index') }}"@if(str_begins_with('panel.projects.', Route::currentRouteName())) class="is-active"@endif>@lang('panel.projects.management')</a></li>
            </ul>
        </aside>
        <div class="column">
            @yield('panel_content')
        </div>
    </div>
@endsection
