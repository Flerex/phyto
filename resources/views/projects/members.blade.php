@extends('projects.partials.layout')

@section('project-content')
    @if(count($members))
        <table class="table is-boxed is-fullwidth">
            <thead>
            <tr>
                <th>@choice('labels.projects.members', 1)</th>
                <th class="has-text-right">@choice('labels.task.unfinished_assignments', 0)</th>
                <th class="has-text-right">@lang('labels.task.assignments')</th>
            </tr>
            </thead>
            <tbody>
            @foreach($members as $member)
                <tr>
                    <td>
                        {{ $member->name }}
                        @if(Auth::user()->getKey() === $member->getKey())
                            &nbsp;<span class="tag is-primary is-light is-rounded is-small">@lang('general.you')</span>
                        @endif
                    </td>
                    <td class="has-text-right">{{ $member->unfinishedAssignments->count() }}</td>
                    <td class="has-text-right">{{ $member->assignments->count() }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @else
        <div class="notification is-info">@lang('projects.empty_assignments')</div>
    @endif
@endsection
