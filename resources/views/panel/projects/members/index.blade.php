@extends('panel.projects.partials.layout')

@section('project_content')
    <div class="button-strip">
        <a href="{{ route('panel.projects.members.create', compact('project')) }}"
           class="button is-rounded is-link">
            <span class="icon is-left"><i class="fas fa-user-plus"></i></span>
            <span>@lang('panel.projects.add_users')</span>
        </a>
    </div>
    @if(count($members))
        <table class="table is-fullwidth is-boxed">
            <thead>
            <th>@lang('labels.name')</th>
            <th>@lang('labels.status')</th>
            <th>@lang('panel.projects.members.added_on')</th>
            <th class="has-text-right">@lang('general.actions')</th>
            </thead>

            <tbody>
            @foreach($members as $member)
                <tr>
                    <td>{{ $member->name }}</td>
                    <td>
                        @if($member->pivot->active)
                            @lang('general.active')
                        @else
                            @lang('general.disabled')
                        @endif
                    </td>
                    <td>{{ $member->pivot->created_at->diffForHumans() }}</td>
                    <td class="has-text-right">
                        <form
                            action="{{ route('panel.projects.members.change_status', compact('project', 'member')) }}"
                            method="POST">
                            <input type="hidden" name="active" value="{{ (int) !$member->pivot->active }}">
                            @if($member->pivot->active)
                                <button type="submit" class="button is-rounded is-light is-danger is-small"
                                        title="{{ trans('panel.projects.members.disable') }}">
                                    <span class="icon"><i class="fas fa-user-minus"></i></span>
                                </button>
                            @else
                                <button type="submit" class="button is-rounded is-light is-small"
                                        title="{{ trans('panel.projects.members.enable') }}">
                                    <span class="icon"><i class="fas fa-user-plus"></i></span>
                                </button>
                            @endif
                            @csrf
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @else
        <div class="message is-info">
            <div class="message-body">@lang('panel.projects.members.empty')</div>
        </div>
    @endif
@endsection
