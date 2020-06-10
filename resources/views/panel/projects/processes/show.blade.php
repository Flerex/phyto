@extends('panel.projects.partials.layout')

@section('project_content')

    {{-- Assignments --}}
    <h2 class="title is-4">@lang('labels.task.assignments')</h2>
    <table class="table is-fullwidth is-boxed">
        <thead>
        <tr>
            <th>@choice('labels.image.images', 1)</th>
            <th>@lang('general.status')</th>
            <th>@choice('labels.user.users', 1)</th>
            <th class="has-text-right">@lang('general.actions')</th>
        </tr>

        </thead>
        <tbody>
        @foreach($assignments as $assignment)
            <tr>
                <td><img class="thumbnail" src="{{ asset($assignment->image->thumbnail_path) }}"></td>
                <td>
                    @if($assignment->finished)
                        <span class="tag is-light is-success">@lang('labels.task.finished')</span>
                    @else
                        <span class="tag is-light is-warning">@lang('labels.task.pending')</span>
                    @endif
                </td>
                <td>{{ $assignment->user->name }}</td>
                <td class="has-text-right">
                    <a href="{{ route('panel.projects.tasks.show_assignment', compact('project', 'task', 'process', 'assignment')) }}"
                       class="button is-rounded is-light is-link has-text-weight-bold is-small">
                        @lang('general.view')
                    </a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    {{ $assignments->links() }}

    {{-- Assignees --}}
    <h2 class="title is-4">@lang('labels.task.assignees')</h2>
    <table class="table is-boxed is-fullwidth">
        <thead>
        <tr>
            <th>@choice('labels.user.users', 1)</th>
            <th>@lang('general.progress')</th>
            <th class="has-text-right">@choice('labels.image.images', 0)</th>
        </tr>

        </thead>
        <tbody>
        @foreach($assignees as $assignee)
            <tr>
                <td>{{ $assignee->user }}</td>
                <td>
                    <progress class="progress is-link is-small" value="{{ $assignee->percentage }}"
                              max="100">{{ $assignee->percentage }}%
                    </progress>
                </td>
                <td class="has-text-right">{{ $assignee->images }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
