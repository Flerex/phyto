@extends('panel.projects.partials.layout')

@section('project_content')

    @if($image->path)
        <div id="boxer"
             data-assignment="{{ $assignment->getKey() }}"
             data-image="{{ asset($image->path) }}"
             data-boxes="{{ $boxes->toJson() }}"
             data-user="{{ Auth::user()->toJson() }}"
             data-tree="{{ $tree->toJson()  }}"
             data-view-only="{{ true }}"
        ></div>
    @else
        <div class="notification is-warning is-light">@lang('projects.unprocessed_image')</div>
    @endif
@endsection
