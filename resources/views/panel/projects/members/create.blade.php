@extends('panel.master')

@section('panel_content')
    <h1 class="title">@lang('panel.projects.add_users')</h1>
    <form method="POST" action="{{ route('panel.projects.members.store', compact('project')) }}">
        @csrf

        <div class="box">


            {{-- Users --}}
            <div class="field">
                <label class="label">@choice('labels.projects.members', 0)</label>

                <div class="control">
                    <div id="user_selector"></div>
                </div>

                @if ($errors->has('users'))
                    <p class="help is-danger">
                        {{ $errors->first('users') }}
                    </p>
                @endif
            </div>

        </div>

        {{-- Actions --}}
        <div class="has-text-centered">
            <button type="submit" class="button is-rounded is-primary">
                <span class="icon"><i class="fas fa-plus"></i></span>
                <span>@lang('general.add')</span>
            </button>
            <a href="{{ route('panel.projects.show', compact('project')) }}"
               class="button is-rounded is-light">@lang('general.cancel')</a>
        </div>
    </form>

@endsection
