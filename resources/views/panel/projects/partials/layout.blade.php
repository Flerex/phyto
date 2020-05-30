@extends('panel.master')

@section('panel_content')

    <h1 class="title">{{ $project->name }}</h1>

    <div class="tabs">
        <ul>
            <li @if(Str::startsWith(Route::currentRouteName(), 'panel.projects.show'))class="is-active"@endif>
                <a href="{{ route('panel.projects.show', compact('project')) }}">
                    <span class="icon is-small"><i class="fas fa-chart-line" aria-hidden="true"></i></span>
                    <span>@lang('general.general')</span>
                </a>
            </li>
            <li @if(Str::startsWith(Route::currentRouteName(), 'panel.projects.samples.index'))class="is-active"@endif>
                <a href="{{ route('panel.projects.samples.index', compact('project')) }}">
                    <span class="icon is-small"><i class="fas fa-microscope" aria-hidden="true"></i></span>
                    <span>{{ trans_choice('panel.projects.samples.label', 0) }}</span>
                </a>
            </li>
            <li @if(Str::startsWith(Route::currentRouteName(), 'panel.projects.members.index'))class="is-active"@endif>
                <a href="{{ route('panel.projects.members.index', compact('project')) }}">
                    <span class="icon is-small"><i class="fas fa-users" aria-hidden="true"></i></span>
                    <span>{{ trans_choice('panel.projects.members.label', 0) }}</span>
                </a>
            </li>
            <li @if(Str::startsWith(Route::currentRouteName(), 'panel.projects.tasks.'))class="is-active"@endif>
                <a href="{{ route('panel.projects.tasks.index', compact('project')) }}">
                    <span class="icon is-small"><i class="fas fa-tasks" aria-hidden="true"></i></span>
                    <span>{{ trans_choice('panel.projects.tasks.label', 0) }}</span>
                </a>
            </li>
        </ul>
    </div>

    @yield('project_content')
@endsection

