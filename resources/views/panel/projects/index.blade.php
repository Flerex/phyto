@extends('panel.master')

@section('panel_content')
    <div class="level">
        <div class="level-left">
            <div class="level-item">
                <h1 class="title">@lang('panel.label.projects')</h1>
            </div>
        </div>
        <div class="level-right">
            <div class="level-item">
                <a href="{{ route('panel.projects.create') }}" class="button is-link is-rounded is-pulled-right">
                    <span class="icon is-left"><i class="fas fa-briefcase"></i></span>
                    <span>@lang('panel.projects.create')</span>
                </a>
            </div>
        </div>
    </div>

    @if($canManageEverything)
        <article class="message is-warning">
            <div class="message-body">@lang('panel.projects.showing_everything_message')</div>
        </article>
    @endif

    @if(count($projects))

        <table class="table is-boxed is-fullwidth">
            <thead>
            <th>@lang('labels.name')</th>
            @if($canManageEverything)
                <th>@lang('labels.projects.manager')</th>
            @endif
            <th class="has-text-right">@choice('labels.projects.members', 0)</th>
            <th class="has-text-right">@lang('labels.projects.samples')</th>
            <th>@lang('labels.description')</th>
            </thead>

            <tbody>
            @foreach($projects as $project)
                <tr>
                    <td><a href="{{ route('panel.projects.show', compact('project')) }}">{{ $project->name }}</a>
                    </td>

                    @if($canManageEverything)
                        <td>{{ $project->manager->name }}</td>
                    @endif

                    <td class="has-text-right">{{ $project->users_count }}</td>
                    <td class="has-text-right">{{ $project->samples_count }}</td>
                    <td>{{ $project->description }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
        {{ $projects->links() }}
    @else
        <div class="message is-info">
            <div class="message-body">@lang('panel.projects.no_projects')</div>
        </div>
    @endif
@endsection
