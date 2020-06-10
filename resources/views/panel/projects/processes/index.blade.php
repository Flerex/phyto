@extends('panel.projects.partials.layout')

@section('project_content')
    <h2 class="title is-4">@choice('labels.task.processes', 0)</h2>
    <table class="table is-fullwidth is-boxed">
        <thead>
        <tr>
            <th>@lang('labels.id')</th>
            <th>@lang('general.status')</th>
            <th>@lang('general.progress')</th>
            <th class="has-text-right">@lang('general.actions')</th>
        </tr>

        </thead>
        <tbody>
        @foreach($processes as $process)
            <tr>
                <td>{{ $process->getKey() }}</td>
                <td>
                    @if($process->finished)
                        <span class="tag is-light is-success">@lang('labels.task.finished')</span>
                    @else
                        <span class="tag is-light is-warning">@lang('labels.task.pending')</span>
                    @endif
                </td>
                <td>
                    <progress class="progress is-link is-small" value="{{ $process->completenessPercentage }}"
                              max="100">{{ $process->completenessPercentage }}%
                    </progress>
                </td>
                <td class="has-text-right">
                    <a href="{{ route('panel.projects.tasks.show_process', array_merge(['task' => $process->task], compact('project', 'process'))) }}"
                       class="button is-link is-rounded is-light is-small has-text-weight-bold">@lang('general.view')</a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
