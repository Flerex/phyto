@extends('panel.projects.partials.layout')

@section('project_content')
    <h1 class="title is-4" style="margin-bottom: 0">{{ $sample->name }}</h1>
    <p style="margin-bottom: 1rem;">{{ $sample->description }}</p>
    @if(count($images) !== $totalImages)
        <div class="notification is-warning has-icon">
            <div class="notification__icon">
                <span class="icon"><span class="fas fa-spinner fa-pulse fa-lg"></span></span>
            </div>
            <div>
                <p>@lang('panel.projects.images.normalizing_in_progress')</p>
                <p style="margin-top: 10px">
                    <strong>@lang('general.progress'):</strong> {{ count($images) }}&nbsp;/&nbsp;{{ $totalImages }}
                </p>
            </div>
        </div>
    @endif
    <div class="columns is-multiline is-3">
        @foreach($images as $image)
            <div class="column is-6">
                <div class="box">
                    <figure class="image">
                        <img src="{{ asset($image->thumbnail_path) }}">
                    </figure>
                </div>
            </div>
        @endforeach
    </div>
@endsection
