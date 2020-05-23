@extends('projects.partials.layout')

@section('project-content')
    @if(count($assignments))
    <div class="box">
        <table class="table is-fullwidth">
            <thead>
            <tr>
                <th>@choice('labels.image.images', 1)</th>
                <th>@choice('labels.task.processes', 1)</th>
                <th>@lang('general.status')</th>
            </tr>
            </thead>
            <tbody>
            @foreach($assignments as $assignment)
                <tr>
                    <td><img class="thumbnail" src="{{ asset($assignment->image->thumbnail_path) }}"></td>
                    <td>{{ $assignment->process->getKey() }}</td>
                    <td>
                        @if($assignment->finished)
                            <span class="tag is-light is-success">@lang('labels.task.finished')</span>
                        @else
                            <span class="tag is-light is-warning">@lang('labels.task.pending')</span>
                        @endif
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    @else
        <div class="notification is-info">@lang('projects.empty_assignments')</div>
    @endif
@endsection
