@extends('layouts.master')

@section('content')
    <div class="columns is-centered">
        <div class="column is-half">
            <h1 class="title">@lang('auth.reset_password')</h1>
            <div class="box">
                <form method="POST" action="{{ route('password.update') }}">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">

                    {{-- Email field --}}
                    <div class="field">
                        <div class="control has-icons-left{{ $errors->has('email') ? ' has-icons-right' : '' }}">
                            <input class="input is-medium" id="email" type="email"
                                   placeholder="{{ trans('auth.email') }}" name="email" required autofocus>
                            <span class="icon is-left"><i class="fas fa-envelope"></i></span>
                            @if ($errors->has('email'))
                                <span class="icon is-right"><i class="fas fa-exclamation-triangle"></i></span>
                            @endif
                        </div>

                        @if ($errors->has('email'))
                            <p class="help is-danger">
                                {{ $errors->first('email') }}
                            </p>
                        @endif
                    </div>

                    {{-- Password field --}}
                    <div class="field">
                        <div class="control has-icons-left{{ $errors->has('password') ? ' has-icons-right' : '' }}">
                            <input class="input is-medium" id="password" type="password" name="password"
                                   placeholder="{{ trans('auth.password') }}" required>
                            <span class="icon is-left"><i class="fas fa-key"></i></span>
                            @if ($errors->has('password'))
                                <span class="icon is-right"><i class="fas fa-exclamation-triangle"></i></span>
                            @endif
                        </div>

                        @if ($errors->has('password'))
                            <p class="help is-danger">
                                {{ $errors->first('password') }}
                            </p>
                        @endif
                    </div>

                    {{-- Password confirmation field --}}
                    <div class="field">
                        <div class="control has-icons-left">
                            <input class="input is-medium" id="password_confirmation" type="password" name="password_confirmation"
                                   placeholder="{{ trans('auth.password_confirmation') }}" required>
                            <span class="icon is-left"><i class="fas fa-key"></i></span>
                        </div>
                    </div>

                    {{-- Reset button --}}
                    <div class="field">
                        <button type="submit" class="button is-primary is-medium is-rounded is-fullwidth">@lang('auth.reset_password')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection