@extends('layouts.master')

@section('content')
    <h1 class="title">{{ $project->name }}</h1>
    <div class="tabs is-centered">
        <ul>
            <li @if(Str::startsWith(Route::currentRouteName(), 'projects.show'))class="is-active"@endif>
                <a href="{{ route('projects.show', compact('project')) }}">
                    <span class="icon is-small"><i class="fas fa-th-large" aria-hidden="true"></i></span>
                    <span>@lang('general.overview')</span>
                </a>
            </li>
            <li @if(Str::startsWith(Route::currentRouteName(), 'projects.assignments'))class="is-active"@endif>
                <a href="{{ route('projects.assignments', compact('project')) }}">
                    <span class="icon is-small"><i class="fas fa-thumbtack" aria-hidden="true"></i></span>
                    <span>
                    @lang('projects.assignments')
                        @if($unfinishedAssignments > 0)
                            &nbsp;<span class="tag is-link is-rounded">{{ $unfinishedAssignments }}</span>
                        @endif
                    </span>
                </a>
            </li>
            <li @if(Str::startsWith(Route::currentRouteName(), 'projects.members'))class="is-active"@endif>
                <a href="{{ route('projects.members', compact('project')) }}">
                    <span class="icon is-small"><i class="fas fa-users" aria-hidden="true"></i></span>
                    <span>@choice('labels.projects.members', 0)</span>
                </a>
            </li>
        </ul>
    </div>

    @yield('project-content')
@endsection
