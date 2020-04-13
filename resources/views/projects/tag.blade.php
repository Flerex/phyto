@extends('layouts.master')

@section('content')
    <div id="tagger"
         data-image-key="{{ $image->getKey() }}"
         data-image="{{ asset($image->preview_path) }}"
         data-boxes="{{ json_encode($boxes) }}"
         data-lang="{{ json_encode($lang) }}"
         data-user="{{ Auth::user()->name }}"
    ></div>
@endsection
