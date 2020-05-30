@extends('panel.projects.partials.layout')

@section('project_content')

    <div class="button-strip">
        <div class="buttons has-addons">
            <a href="{{ route('panel.projects.tasks.create', compact('project')) }}"
               class="button is-link is-rounded">
                <span class="icon is-left"><i class="fas fa-tasks"></i></span>
                <span>@lang('panel.projects.tasks.create')</span>
            </a>
            <a href="#" class="button is-link is-rounded" disabled>
                <span class="icon is-left"><i class="fas fa-robot"></i></span>
                <span>@lang('panel.projects.tasks.automated_create')</span>
            </a>
        </div>
    </div>
    @if(count($tasks))
        <div class="box">
            <table class="table is-fullwidth">
                <thead>
                <th>@lang('labels.samples.sample')</th>
                <th>@lang('general.progress')</th>
                <th>@lang('panel.projects.members.added_on')</th>
                <th class="has-text-right">@lang('general.actions')</th>
                </thead>

                <tbody>
                @foreach($tasks as $task)
                    <tr>
                        <td>{{ $task->sample->name }}</td>
                        <td style="max-width: 100px">
                            @if($task->finished)
                                @lang('labels.task.finished')
                            @else
                                <progress class="progress is-link is-small" value="{{ $task->completenessPercentage }}"
                                          max="100">{{ $task->completenessPercentage }}%
                                </progress>
                            @endif
                        </td>
                        <td>{{ $task->created_at->diffForHumans() }}</td>
                        <td class="has-text-right">
                            <a href="{{ route('panel.projects.tasks.show', compact('project', 'task')) }}"
                               class="button is-link is-rounded is-light is-small has-text-weight-bold">@lang('general.view')</a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="message is-info">
            <div class="message-body">@lang('panel.projects.tasks.empty')</div>
        </div>
    @endif
@endsection
