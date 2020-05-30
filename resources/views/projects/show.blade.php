@extends('projects.partials.layout')

@section('project-content')
    <div class="columns is-multiline">
        @foreach($images as $image)
            <div class="column is-4">
                <a href="{{ route('projects.images.tag', compact('project', 'image')) }}">
                    <div class="box">
                        <figure class="image">
                            <img class="thumbnail" src="{{ asset($image->thumbnail_path) }}">
                        </figure>
                    </div>
                </a>
            </div>
        @endforeach
    </div>
@endsection
