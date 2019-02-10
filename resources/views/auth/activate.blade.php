@extends('layouts.master')

@section('content')
    <div class="columns is-centered">
        <div class="column is-half">
            <h1 class="title">Account setup</h1>
            <article class="message is-info">
                <div class="message-body">
                    Please set a password for your account to be able to log in and to verify your email account.
                </div>
            </article>
            <div class="box">
                <form action="{{ $link }}" method="POST">
                    @csrf

                    {{-- Password --}}
                    <div class="field">
                        <div class="control has-icons-left{{ $errors->has('password') ? ' has-icons-right' : '' }}">
                            <input id="password" type="password"
                                   class="input is-medium{{ $errors->has('password') ? ' is-danger' : '' }}"
                                   name="password" value="" placeholder="{{ trans('auth.password') }}" required
                                   autofocus>

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
                        <div class="control has-icons-left">
                            <input id="password-confirm" type="password" class="input is-medium"
                                   name="password_confirmation" value=""
                                   placeholder="{{ trans('auth.password_confirmation') }}" required autofocus>

                            <span class="icon is-small is-left"><i class="fas fa-asterisk"></i></span>
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="field">
                        <div class="control">
                            <button type="submit"
                                    class="button is-primary is-medium is-rounded is-fullwidth">@lang('general.send')</button>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
@endsection
