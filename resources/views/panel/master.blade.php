@extends('layouts.master')

@section('full-width', true)
@section('content')

    <div id="panel" class="columns">
        <div id="sidebar" class="column is-one-fifth">
            <aside class="menu">
                @can(\App\Domain\Enums\Permissions::USER_MANAGEMENT()->getValue())
                    <p class="menu-label">@lang('panel.label.users')</p>
                    <ul class="menu-list">
                        <li>
                            <a href="{{ route('panel.users.index') }}"
                               @if(Str::startsWith(Route::currentRouteName(), 'panel.users.')) class="is-active"@endif>
                                @lang('panel.users.management')
                            </a>
                        </li>
                    </ul>
                @endcan
                @canany([\App\Domain\Enums\Permissions::CATALOG_MANAGEMENT()->getValue(), \App\Domain\Enums\Permissions::SPECIES_MANAGEMENT()->getValue()])
                    <p class="menu-label">@lang('panel.label.catalogs_species')</p>
                    <ul class="menu-list">
                        @can(\App\Domain\Enums\Permissions::CATALOG_MANAGEMENT()->getValue())
                            <li><a href="{{ route('panel.catalogs.index') }}"
                                   @if(Str::startsWith(Route::currentRouteName(), 'panel.catalogs.')) class="is-active"@endif>@lang('panel.catalogs.management')</a>
                            </li>
                        @endcan
                        @can(\App\Domain\Enums\Permissions::SPECIES_MANAGEMENT()->getValue())
                            <li><a href="{{ route('panel.species.index') }}"
                                   @if(Str::startsWith(Route::currentRouteName(), 'panel.species.')) class="is-active"@endif>@lang('panel.species.management')</a>
                            </li>
                        @endcan
                    </ul>
                @endcanany
                @canany([\App\Domain\Enums\Permissions::PROJECT_MANAGEMENT()->getKey(), \App\Domain\Enums\Permissions::MANAGE_ALL_PROJECTS()])
                    <p class="menu-label">@lang('panel.label.projects')</p>
                    <ul class="menu-list">
                        <li><a href="{{ route('panel.projects.index') }}"
                               @if($projectManagementIsActive) class="is-active"@endif>@lang('panel.projects.management')</a>
                        </li>
                    </ul>

                    @if(count($projects))
                        <p class="menu-label">@lang('panel.managed_projects')</p>
                        <ul class="menu-list">
                            @foreach($projects as $project)
                                <li><a href="{{ route('panel.projects.show', compact('project')) }}"
                                       @if(Request::is(trim(route('panel.projects.show', compact('project'), false), '/') . '*')) class="is-active"@endif>{{ $project->name}}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                @endcanany
            </aside>
        </div>
        <div class="column">
            <div class="container">
                @yield('panel_content')
            </div>
        </div>
    </div>
@endsection
