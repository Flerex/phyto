@extends('layouts.master')

@section('content')
    <div id="home-grid" class="columns is-centered">
        @can(Permissions::PANEL_ACCESS)
            <div class="column is-two-fifths">
                <section class="home_buttons">
                    @can(Permissions::USER_MANAGEMENT)
                        <a class="home_button has-background-primary" href="{{ route('panel.users.index') }}">
                            <span class="icon is-size-1"><i class="fas fa-users"></i></span>
                            <div class="home_button__label">@lang('panel.users.management')</div>
                        </a>
                    @endcan
                    @can(Permissions::CATALOG_MANAGEMENT)
                        <a class="home_button has-background-warning" href="{{ route('panel.catalogs.index') }}">
                            <span class="icon is-size-1"><i class="fas fa-book"></i></span>
                            <div class="home_button__label">@lang('panel.catalogs.management')</div>
                        </a>
                    @endcan
                    @can(Permissions::SPECIES_MANAGEMENT)
                        <a class="home_button has-background-info" href="{{ route('panel.species.index') }}">
                            <span class="icon is-size-1"><i class="fas fa-paw"></i></span>
                            <div class="home_button__label">@lang('panel.species.management')</div>
                        </a>
                    @endcan
                    @can(Permissions::PROJECT_MANAGEMENT)
                        <a class="home_button has-background-danger" href="{{ route('panel.projects.index') }}">
                            <span class="icon is-size-1"><i class="fas fa-briefcase"></i></span>
                            <div class="home_button__label">@lang('panel.projects.management')</div>
                        </a>
                    @endcan
                </section>
            </div>
        @endcan
        <div class="column is-one-fifth">
            <section class="project_list">
                <h1 class="is-size-7 is-uppercase has-text-weight-bold project_list__label has-text-grey">@lang('projects.my_projects')</h1>

                @if(count($projects))
                    <ul>
                        @foreach($projects as $project)
                            <li>
                                <a href="{{ route('projects.show', compact('project')) }}">
                                    @if($project->manager->getKey() === Auth::user()->getKey())
                                        <span class="icon has-text-grey">
                                            <i class="fa fa-chess-king" aria-label="@lang('projects.manager')" data-tippy="@lang('projects.manager')" data-tippy-arrow="true"></i>
                                        </span>
                                    @endif
                                    {{ $project->name }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <span class="is-italic">@lang('projects.no_projects')</span>
                @endif
            </section>
        </div>
    </div>
@endsection
