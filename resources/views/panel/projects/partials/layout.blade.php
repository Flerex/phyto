@extends('panel.master')

@section('panel_content')
    <div class="level">
        <div class="level-left">
            <div class="level-item">
                <h1 class="title">{{ $project->name }}</h1>
            </div>
        </div>
        <div class="level-right">
            <div class="level-item">
                <a href="{{ route('panel.projects.samples.create', compact('project')) }}" class="button is-pulled-right">
                    <span class="icon is-left"><i class="fas fa-flask"></i></span>
                    <span>@lang('panel.projects.samples.create')</span>
                </a>
            </div>
            <div class="level-item">
                <a href="{{ route('panel.projects.add_user', compact('project')) }}" class="button is-pulled-right">
                    <span class="icon is-left"><i class="fas fa-user-plus"></i></span>
                    <span>@lang('panel.projects.add_users')</span>
                </a>
            </div>
        </div>
    </div>

    <div class="tabs">
        <ul>
            <li @if(Str::startsWith(Route::currentRouteName(), 'panel.projects.show'))class="is-active"@endif>
                <a href="{{ route('panel.projects.show', compact('project')) }}">@lang('general.general')</a>
            </li>
            <li @if(Str::startsWith(Route::currentRouteName(), 'panel.projects.samples.index'))class="is-active"@endif>
                <a href="{{ route('panel.projects.samples.index', compact('project')) }}">
                    {{ trans_choice('panel.projects.samples.label', 0) }}
                </a>
            </li>
        </ul>
    </div>

    @yield('project_content')
@endsection

