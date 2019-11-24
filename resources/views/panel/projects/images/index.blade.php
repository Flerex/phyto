@extends('panel.projects.partials.layout')

@section('project_content')
    @foreach($images as $image)
        <img src="{{ asset($image->preview_path) }}">
    @endforeach
@endsection
