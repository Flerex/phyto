@extends('layouts.master')

@section('content')
    <div id="boxer"
         data-image-key="{{ $image->getKey() }}"
         data-image="{{ asset($image->path) }}"
         data-boxes="{{ json_encode($boxes) }}"
         data-user="{{ Auth::user()->name }}"
    ></div>
@endsection
