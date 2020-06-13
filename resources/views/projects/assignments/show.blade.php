@extends('layouts.master')

@section('content')
    @if($image->path)
        <div id="boxer"
             data-assignment="{{ $assignment->getKey() }}"
             data-image="{{ asset($image->path) }}"
             data-boxes="{{ $boxes->toJson() }}"
             data-user="{{ Auth::user()->toJson() }}"
             data-catalogs="{{ $catalogs->toJson()  }}"
             data-tree="{{ $tree->toJson()  }}"
             data-view-only="{{ $assignment->finished }}"
        ></div>
        @if(!$assignment->finished)
            <div class="detached-boxer-strip">
                <div class="button-confirmation" data-type="primary" data-method="POST"
                     data-route="{{ route('projects.assignments.finish', compact('assignment')) }}"
                     data-action="@lang('projects.mark_as_finished')" data-class="button is-primary is-rounded"
                     data-override-styles></div>
            </div>
        @endif
    @else
        <div class="notification is-warning is-light">@lang('projects.unprocessed_image')</div>
    @endif

    <div class="image-scroll" data-images="{{ $images->toJson() }}"></div>
@endsection
