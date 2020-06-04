@extends('panel.projects.partials.layout')

@section('project_content')

    <div id="boxer"
         data-assignment="{{ $assignment->getKey() }}"
         data-image="{{ asset($image->path) }}"
         data-boxes="{{ $boxes->toJson() }}"
         data-user="{{ Auth::user()->toJson() }}"
         data-tree="{{ $tree->toJson()  }}"
         data-view-only="{{ true }}"
    ></div>
@endsection
