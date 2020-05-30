@extends('panel.projects.partials.layout')

@section('project_content')
    <div class="definitions box">

        {{-- Description --}}
        <div class="definition">
            <div class="definition__label">@lang('labels.description')</div>
            <div class="definition__value">{{ $project->description }}</div>
        </div>

        {{-- Manager --}}
        <div class="definition">
            <div class="definition__label">@lang('labels.projects.manager')</div>
            <div class="definition__value">{{ $project->manager->name }}</div>
        </div>

        {{-- Catalogs --}}
        <div class="definition">
            <div class="definition__label">@lang('labels.projects.catalogs')</div>
            <div class="definition__value">
                @foreach($project->catalogs as $catalog)
                    {{ $catalog->name }}@if(!$loop->last), @endif
                @endforeach
            </div>
        </div>
    </div>
    <div class="blocks">
        {{-- Total members --}}
        <div class="block box">
            <div class="block__value">{{ $stats->totalMembers }}</div>
            <div class="block__label">{{ trans_choice('panel.projects.members.label', $stats->totalMembers) }}</div>
        </div>

        {{-- Total samples --}}
        <div class="block box">
            <div class="block__value">{{ $stats->totalSamples }}</div>
            <div class="block__label">{{ trans_choice('panel.projects.samples.label', $stats->totalSamples) }}</div>
        </div>

        {{-- Total images --}}
        <div class="block box">
            <div class="block__value">{{ $stats->totalImages }}</div>
            <div class="block__label">{{ trans_choice('panel.projects.images.label', $stats->totalImages) }}</div>
        </div>

        {{-- Total tasks --}}
        <div class="block box">
            <div class="block__value">{{ $stats->totalTasks }}</div>
            <div class="block__label">{{ trans_choice('panel.projects.tasks.label', $stats->totalTasks) }}</div>
        </div>

        {{-- Total processes --}}
        <div class="block box">
            <div class="block__value">{{ $stats->totalProcesses }}</div>
            <div class="block__label">{{ trans_choice('labels.task.processes', $stats->totalProcesses) }}</div>
        </div>

        {{-- Total unfinished assignments --}}
        <div class="block box">
            <div class="block__value">{{ $stats->unfinishedAssignments }}</div>
            <div class="block__label">{{ trans_choice('labels.task.unfinished_assignments', $stats->unfinishedAssignments) }}</div>
        </div>
    </div>
@endsection
