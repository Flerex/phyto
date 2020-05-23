@extends('layouts.master')

@section('content')
    {{-- Useful links strip --}}
    @can(Permissions::PANEL_ACCESS()->getValue())
        <section class="home_buttons">
            <span class="home_buttons_feedback">@lang('general.useful_links')</span>
            @can(Permissions::USER_MANAGEMENT()->getValue())
                <a class="home_button has-background-primary" href="{{ route('panel.users.index') }}">
                    <span class="icon is-size-6"><i class="fas fa-users"></i></span>
                    <div class="home_button__label">@lang('panel.users.management')</div>
                </a>
            @endcan
            @can(Permissions::CATALOG_MANAGEMENT()->getValue())
                <a class="home_button has-background-warning" href="{{ route('panel.catalogs.index') }}">
                    <span class="icon is-size-6"><i class="fas fa-book"></i></span>
                    <div class="home_button__label">@lang('panel.catalogs.management')</div>
                </a>
            @endcan
            @can(Permissions::SPECIES_MANAGEMENT()->getValue())
                <a class="home_button has-background-info" href="{{ route('panel.species.index') }}">
                    <span class="icon is-size-6"><i class="fas fa-paw"></i></span>
                    <div class="home_button__label">@lang('panel.species.management')</div>
                </a>
            @endcan
            @can(Permissions::PROJECT_MANAGEMENT()->getValue())
                <a class="home_button has-background-danger" href="{{ route('panel.projects.index') }}">
                    <span class="icon is-size-6"><i class="fas fa-briefcase"></i></span>
                    <div class="home_button__label">@lang('panel.projects.management')</div>
                </a>
            @endcan
        </section>
    @endcan

    <div id="dashboard" class="columns">
        <div class="column is-half">

            {{-- Your Projects --}}
            <section>
                <h1 class="title is-5">@lang('projects.my_projects')</h1>
                @if(count($projects))
                    <table class="table is-boxed is-fullwidth">
                        <thead>
                        <th colspan="2">@choice('labels.projects.projects', 1)</th>
                        </thead>
                        <tbody>
                        @foreach($projects as $project)
                            <tr>
                                <td>
                                    <a href="{{ route('projects.show', compact('project')) }}">
                                        @if($project->manager->getKey() === Auth::user()->getKey())
                                            <span class="tag is-light is-light manager__feedback icon">
                                            <i class="fa fa-chess-king" aria-label="@lang('projects.manager')"
                                               data-tippy-content="@lang('projects.managed_by_you')"
                                               data-tippy-arrow="true"></i>
                                        </span>
                                        @endif
                                        {{ $project->name }}
                                    </a>
                                </td>
                                <td class="has-text-right">
                                    @if($project->unfinished_assignments_count)
                                        <span class="tag is-link is-rounded is-light has-text-weight-bold">
                                        {{ $project->unfinished_assignments_count }}
                                    </span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                @else
                    <span class="is-italic">@lang('projects.no_projects')</span>
                @endif
            </section>

            {{-- Your processes --}}
            <section>
                <h1 class="title is-5">@lang('projects.my_processes')</h1>
                @if(count($processes))
                    <table class="table is-boxed is-fullwidth">
                        <thead>
                        <th colspan="2">@choice('labels.task.processes', 1)</th>
                        </thead>
                        <tbody>
                        @foreach($processes as $process)
                            <tr>
                                <td>
                                    {{ $process->getKey() }}
                                </td>
                                <td class="has-text-right">
                                    @if($process->unfinished_assignments_count)
                                        <span class="tag is-link is-rounded is-light has-text-weight-bold">
                                        {{ $process->unfinished_assignments_count }}
                                    </span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                @else
                    <span class="is-italic">@lang('projects.no_processes')</span>
                @endif
            </section>
        </div>
        <section class="column is-half">
            <h1 class="title is-5">@lang('labels.task.unfinished_assignments')</h1>
            @if(count($assignments))
                <table class="table is-boxed is-fullwidth">
                    <thead>
                    <th>@choice('labels.image.images', 1)</th>
                    <th>@choice('labels.projects.projects', 1)</th>
                    <th class="has-text-right">@choice('labels.task.processes', 1)</th>
                    </thead>
                    <tbody>
                    @foreach($assignments as $assignment)
                        <tr>
                            <td><img class="thumbnail" src="{{ asset($assignment->image->thumbnail_path) }}"></td>
                            <td>{{ $assignment->project->name }}</td>
                            <td class="has-text-right">{{ $assignment->process->getKey() }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                @if($assignmentsCount > config('phyto.random_assignments_count'))
                    <div class="is-italic has-text-grey">
                        @lang('projects.unfinished_assignments_count', ['number' => $assignmentsCount])
                    </div>
                @endif
            @else
                <span class="is-italic">@lang('projects.user_empty_assignments')</span>
            @endif
        </section>
    </div>

@endsection
