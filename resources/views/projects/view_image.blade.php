@extends('projects.partials.layout')

@section('project-content')
    <div id="image-viewer"><img src="{{ asset($image->path) }}"></div>
    <div class="image-scroll" data-images="{{ $images->toJson() }}"></div>
@endsection
