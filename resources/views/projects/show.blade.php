@extends('projects.partials.layout')

@section('project-content')
    <div class="columns is-multiline">
        @foreach($images as $image)
            <div class="column is-4">
                <a href="{{ route('projects.images.show', compact('project', 'image')) }}">
                    <div class="box">
                        <figure class="image">
                            @include('partials.thumbnail', compact('image'))
                        </figure>
                    </div>
                </a>
            </div>
        @endforeach
    </div>
@endsection
