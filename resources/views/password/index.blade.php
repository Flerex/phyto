@extends('layouts.master')

@section('content')
    <form action="{{ route('password.change') }}" method="POST">
        @csrf

        {{-- Passowrd --}}
        <div class="field">
            <label for="password" class="label">{{ @trans('auth.password') }}</label>

            <div class="control has-icons-left{{ $errors->has('passowrd') ? ' has-icons-right' : '' }}">
                <input id="password" type="password" class="input{{ $errors->has('password') ? ' is-danger' : '' }}"
                       name="password" value="" required autofocus>

                <span class="icon is-small is-left"><i class="fas fa-asterisk"></i></span>
                @if ($errors->has('password'))
                    <span class="icon is-small is-right"><i class="fas fa-exclamation-triangle"></i></span>
                @endif
            </div>

            @if ($errors->has('password'))
                <p class="help is-danger">
                    {{ $errors->first('password') }}
                </p>
            @endif
        </div>

        {{-- Passowrd Confirmation --}}
        <div class="field">
            <label for="password-confirm" class="label">{{ @trans('auth.password_confirmation') }}</label>

            <div class="control has-icons-left{{ $errors->has('password') ? ' has-icons-right' : '' }}">
                <input id="password-confirm" type="password" class="input"
                       name="password_confirmation" value="" required autofocus>

                <span class="icon is-small is-left"><i class="fas fa-asterisk"></i></span>
            </div>
        </div>

        {{-- Actions --}}
        <div class="field is-grouped">
            <div class="control">
                <button type="submit" class="button is-primary">@lang('general.send')</button>
            </div>
        </div>

    </form>
@endsection
