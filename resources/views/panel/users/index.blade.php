@extends('panel.master')

@section('panel_content')
    <h1 class="title">@lang('panel.label.users')</h1>
    <a href="{{ route('panel.users.create') }}" class="button is-primary is-pulled-right">
        <span class="icon is-left"><i class="fas fa-user-plus"></i></span>
        <span>@lang('panel.users.create')</span>
    </a>
    <table class="table is-fullwidth">
        <thead>
        <th>@lang('labels.id')</th>
        <th>@lang('labels.user.name')</th>
        <th>@lang('labels.user.email')</th>
        <th>@lang('labels.user.role')</th>
        <th class="has-text-centered">@lang('panel.users.joined')</th>
        <th>@lang('panel.users.registered')</th>
        <th class="has-text-centered">@lang('panel.users.reset_password') @include('partials.info', ['info' => trans('panel.users.reset_password_info')])</th>
        </thead>

        <tbody>
        @foreach($users as $user)
            <tr>
                <th>{{ $user->id }}</th>
                <td>
                    @include('partials.avatar', compact('user'))<span>{{ $user->name }}</span></td>
                <td><a href="mailto:{{ $user->email }}">{{ $user->email }}</a></td>
                <td>
                    @forelse($user->roles as $role)
                        {{ trans('auth.roles.' . $role->name) }}@if(!$loop->last), @endif
                    @empty
                        @lang('general.none')
                    @endforelse
                </td>
                <td class="has-text-centered">
                    @if(is_null($user->email_verified_at))
                        <i class="fas fa-times"></i>

                    @else
                        <i class="fas fa-check"></i>
                    @endif
                </td>
                <td>
                    {{ $user->created_at->diffForHumans() }}
                </td>
                <td class="has-text-centered">
                    <form method="POST" action="{{ route('panel.users.password_reset', [ 'id' => $user->id ]) }}">
                        @csrf
                        <button type="submit" class="button is-outlined is-link is-small">
                            @lang('general.reset')
                        </button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    {{ $users->links() }}
@endsection
