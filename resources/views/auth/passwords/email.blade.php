@extends('layouts.master')

@section('content')
    <div class="columns is-centered">
        <div class="column is-half">
            @if (session('status'))
                <div class="message is-success">
                    <div class="message-body">{{ session('status') }}</div>
                </div>
            @endif
            <h1 class="title">@lang('auth.reset_password')</h1>
            <div class="box">
                <form method="POST" action="{{ route('password.email') }}">
                    @csrf

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
                    {{-- Login button --}}
                    <div class="field">
                        <button type="submit" class="button is-primary is-medium is-rounded is-fullwidth">@lang('auth.send_password_reset')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
