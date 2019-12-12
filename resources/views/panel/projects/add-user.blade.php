@extends('panel.master')

@section('panel_content')
    <h1 class="title">@lang('panel.projects.add_users')</h1>
    <form method="POST" action="{{ route('panel.projects.add_user_store', compact('project')) }}">
        @csrf

        {{-- Users --}}
        <div class="field">
            <label class="label">@lang('labels.projects.members')</label>

            <div class="control">
                <div id="user_selector"></div>
            </div>

            @if ($errors->has('users'))
                <p class="help is-danger">
                    {{ $errors->first('users') }}
                </p>
            @endif
        </div>


        {{-- Actions --}}
        <div class="field is-grouped">
            <div class="control">
                <button type="submit" class="button is-primary">
                    <span class="icon"><i class="fas fa-plus"></i></span>
                    <span>@lang('general.add')</span>
                </button>
            </div>
            <div class="control">
                <a href="{{ route('panel.projects.show', compact('project')) }}" class="button is-light">@lang('general.cancel')</a>
            </div>
        </div>
    </form>

@endsection
