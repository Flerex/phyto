@extends('layouts.master')

@section('content')
    <div id="tagger"
         data-image="{{ asset($image->preview_path) }}"
         data-create-bb-link="{{ route('projects.images.bounding_boxes.create', compact('project', 'image')) }}"
         data-boxes="{{ json_encode($boxes) }}"
         data-lang="{{ json_encode($lang) }}"
         data-user="{{ Auth::user()->name }}"
    ></div>
@endsection
