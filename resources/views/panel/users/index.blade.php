@extends('panel.master')

@section('panel_content')
    <div class="level">
        <div class="level-left">
            <div class="level-item">
                <h1 class="title">@lang('panel.label.users')</h1>
            </div>
        </div>
        <div class="level-right">
            <div class="level-item">
                <a href="{{ route('panel.users.create') }}" class="button is-gray is-pulled-right is-rounded">
                    <span class="icon is-left"><i class="fas fa-user-plus"></i></span>
                    <span>@lang('panel.users.create')</span>
                </a>
            </div>
        </div>
    </div>

    <table class="table is-boxed is-fullwidth">
        <thead>
        <th class="has-text-right">@lang('labels.id')</th>
        <th>@lang('panel.users.user')</th>
        <th>@lang('labels.user.role')</th>
        <th class="has-text-centered">@lang('panel.users.joined')</th>
        <th>@lang('panel.users.registered')</th>
        <th class="has-text-right" style="white-space: nowrap">@lang('panel.users.reset_password') @include('partials.info', ['info' => trans('panel.users.reset_password_info')])</th>
        </thead>

        <tbody>
        @foreach($users as $user)
            <tr>
                <th class="has-text-right">{{ $user->id }}</th>
                <td>
                    <div class="is-flex" style="align-items: center">
                            @include('partials.avatar', compact('user'))
                        <div>
                            <span>{{ $user->name }}</span>
                            <br>
                            <a href="mailto:{{ $user->email }}">{{ $user->email }}</a>
                        </div>
                    </div>
                </td>
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
                <td class="has-text-right">
                    <div class="button-confirmation" data-type="link"
                         data-route="{{ route('panel.users.password_reset', [ 'id' => $user->id ]) }}"
                         data-action="@lang('general.reset')" data-class="has-text-weight-bold"></div>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    {{ $users->links() }}
@endsection
