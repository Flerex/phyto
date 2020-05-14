@extends('layouts.master')

@section('content')
    <div class="tabs is-centered">
        <ul>
            <li @if(Str::startsWith(Route::currentRouteName(), 'projects.show'))class="is-active"@endif>
                <a href="{{ route('projects.show', compact('project')) }}">@lang('general.overview')</a>
            </li>
            <li @if(Str::startsWith(Route::currentRouteName(), 'projects.assignments'))class="is-active"@endif>
                <a href="{{ route('projects.assignments', compact('project')) }}">@lang('projects.assignments')</a>
            </li>
        </ul>
    </div>

    @yield('project-content')
@endsection
