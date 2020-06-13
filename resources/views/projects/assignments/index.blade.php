@extends('projects.partials.layout')

@section('project-content')
    @if(count($assignments))
        <div class="table-filter">
            <span>@lang('projects.showing_process')</span>
            <div id="process_filter" data-processes="{{ json_encode($processes) }}"
                 data-route="{{ route('projects.assignments.index', compact('project'), false) }}"
                 data-old="{{ $process ?? 'null' }}"></div>

        </div>
        <table class="table is-boxed is-fullwidth">
            <thead>
            <tr>
                <th>@choice('labels.image.images', 1)</th>
                <th class="has-text-right">@choice('labels.task.processes', 1)</th>
                <th class="has-text-right">@choice('labels.catalog.species', 0)</th>
                <th class="has-text-right">@lang('boxer.untagged')</th>
                <th>@lang('general.status')</th>
                <th class="has-text-right">@lang('general.actions')</th>
            </tr>
            </thead>
            <tbody>
            @foreach($assignments as $assignment)
                <tr>
                    <td>@include('partials.thumbnail', ['image' => $assignment->image])</td>
                    <td class="has-text-right">{{ $assignment->process->getKey() }}</td>
                    <td class="has-text-right">{{ $assignment->boxes_count }}</td>
                    <td class="has-text-right">{{ $assignment->untagged_count }}</td>
                    <td>
                        @if($assignment->finished)
                            <span class="tag is-light is-success">@lang('labels.task.finished')</span>
                        @else
                            <span class="tag is-light is-warning">@lang('labels.task.pending')</span>
                        @endif
                    </td>
                    <td class="has-text-right">

                        <a href="{{ route('projects.assignments.show', array_merge($process ? ['filtered'] : [], compact('project', 'assignment'))) }}"
                           class="button is-light {{ $assignment->finished ? '' : 'is-link' }} is-small is-rounded has-text-weight-bold">
                            @if($assignment->finished)
                                @lang('general.view')
                            @else
                                @lang('projects.tag')
                            @endif
                        </a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        {{ $assignments->links() }}
    @else
        <div class="notification is-info">@lang('projects.empty_assignments')</div>
    @endif
@endsection
