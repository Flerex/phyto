@extends('layouts.master')

@section('content')
    {{-- FIXME: Show only those links to modules in which you have access --}}
    <div class="columns is-centered">
        <div class="tile is-ancestor is-6">

            <div class="tile is-vertical is-parent">
                <div class="tile is-child">
                    <a href="{{ route('panel.users.index') }}">
                        <div class="home_button notification is-primary has-text-centered">
                            <span class="icon is-size-1"><i class="fas fa-users"></i></span>
                            <div class="home_button__label is-uppercase has-text-weight-bold">
                                @lang('panel.users.management')
                            </div>
                        </div>
                    </a>
                </div>
                <div class="tile is-child">
                    <a href="{{ route('panel.catalogs.index') }}">
                        <div class="home_button notification is-warning has-text-centered">
                            <span class="icon is-size-1"><i class="fas fa-book"></i></span>
                            <div class="home_button__label is-uppercase has-text-weight-bold">
                                @lang('panel.catalogs.management')
                            </div>
                        </div>
                    </a>
                </div>
            </div>
            <div class="tile is-vertical is-parent">
                <div class="tile is-child">
                    <a href="{{ route('panel.species.index') }}">
                        <div class="home_button notification is-info has-text-centered">
                            <span class="icon is-size-1"><i class="fas fa-paw"></i></span>
                            <div class="home_button__label is-uppercase has-text-weight-bold">
                                @lang('panel.species.management')
                            </div>
                        </div>
                    </a>
                </div>
                <div class="tile is-child">
                    <a href="{{ route('panel.projects.index') }}">
                        <div class="home_button notification is-danger has-text-centered">
                            <span class="icon is-size-1"><i class="fas fa-briefcase"></i></span>
                            <div class="home_button__label is-uppercase has-text-weight-bold">
                                @lang('panel.projects.management')
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
