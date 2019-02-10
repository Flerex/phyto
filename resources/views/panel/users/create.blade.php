@extends('panel.master')

@section('panel_content')
    <h1 class="title">@lang('panel.users.create_label')</h1>
    <form method="POST" action="{{ route('panel.users.store') }}">
        @csrf

        {{-- Name --}}
        <div class="field">
            <label for="name" class="label">{{ @trans('auth.name') }}</label>

            <div class="control has-icons-left{{ $errors->has('name') ? ' has-icons-right' : '' }}">
                <input id="name" type="text" class="input{{ $errors->has('name') ? ' is-danger' : '' }}"
                       name="name" value="{{ old('name') }}" required autofocus>

                <span class="icon is-small is-left"><i class="fas fa-user"></i></span>
                @if ($errors->has('name'))
                    <span class="icon is-small is-right"><i class="fas fa-exclamation-triangle"></i></span>
                @endif
            </div>

            @if ($errors->has('name'))
                <p class="help is-danger">
                    {{ $errors->first('name') }}
                </p>
            @endif
        </div>

        {{-- Email --}}
        <div class="field">
            <label for="email" class="label">{{ @trans('auth.email') }}</label>

            <div class="control has-icons-left{{ $errors->has('email') ? ' has-icons-right' : '' }}">
                <input id="email" type="email" class="input{{ $errors->has('email') ? ' is-danger' : '' }}"
                       name="email" value="{{ old('email') }}" required autofocus>

                <span class="icon is-small is-left"><i class="fas fa-envelope"></i></span>
                @if ($errors->has('email'))
                    <span class="icon is-small is-right"><i class="fas fa-exclamation-triangle"></i></span>
                @endif
            </div>

            @if ($errors->has('email'))
                <p class="help is-danger">
                    {{ $errors->first('email') }}
                </p>
            @endif
        </div>

        {{-- Role --}}
        @if(!empty($roles))
            <div class="field">
                <label class="label">{{ @trans('auth.role') }}</label>
                <div class="control">
                    <div class="select">
                        <select name="role">
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}"{{ $role->isDefault ? ' selected' : '' }}>{{ trans('auth.roles.' . $role->name) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        @endif

        {{-- Actions --}}
        <div class="field is-grouped">
            <div class="control">
                <button type="submit" class="button is-primary">@lang('general.create')</button>
            </div>
            <div class="control">
                <a href="{{ route('panel.users.index') }}" class="button is-light">@lang('general.cancel')</a>
            </div>
        </div>
    </form>

@endsection
