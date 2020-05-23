@extends('layouts.master')

@section('content')
    <h1 class="title">{{ $project->name }}</h1>
    <div class="tabs is-centered">
        <ul>
            <li @if(Str::startsWith(Route::currentRouteName(), 'projects.show'))class="is-active"@endif>
                <a href="{{ route('projects.show', compact('project')) }}">@lang('general.overview')</a>
            </li>
            <li @if(Str::startsWith(Route::currentRouteName(), 'projects.assignments'))class="is-active"@endif>
                <a href="{{ route('projects.assignments', compact('project')) }}">
                    @lang('projects.assignments')
                    @if($unfinishedAssignments > 0)
                        &nbsp;<span class="tag is-link is-rounded">{{ $unfinishedAssignments }}</span>
                    @endif
                </a>
            </li>
            <li @if(Str::startsWith(Route::currentRouteName(), 'projects.members'))class="is-active"@endif>
                <a href="{{ route('projects.members', compact('project')) }}">@choice('labels.projects.members', 0)</a>
            </li>
        </ul>
    </div>

    @yield('project-content')
@endsection
