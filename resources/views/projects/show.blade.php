@extends('layouts.master')

@section('content')
    <div class="tabs is-centered">
        <ul>
            <li class="is-active"><a>@lang('general.overview')</a></li>
            <li><a>OUTRO LINK</a></li>
            <li><a>OUTRO LINK</a></li>
            <li><a>OUTRO LINK</a></li>
        </ul>
    </div>

    <div class="columns is-multiline is-3">
        @foreach($images as $image)
            <div class="column is-6">
                <a href="{{ route('projects.images.tag', compact('project', 'image')) }}">
                    <div class="box">
                        <figure class="image">
                            <img src="{{ asset($image->path) }}">
                        </figure>
                    </div>
                </a>
            </div>
        @endforeach
    </div>
@endsection
