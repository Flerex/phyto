@extends('projects.partials.layout')

@section('project-content')
    @if(count($members))
        <div class="box">
            <table class="table is-fullwidth">
                <thead>
                <tr>
                    <th>@choice('labels.projects.members', 1)</th>
                    <th>@lang('labels.task.unfinished_assignments')</th>
                    <th>@lang('labels.task.assignments')</th>
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
                        <td>{{ $member->unfinishedAssignments->count() }}</td>
                        <td>{{ $member->assignments->count() }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="notification is-info">@lang('projects.empty_assignments')</div>
    @endif
@endsection
