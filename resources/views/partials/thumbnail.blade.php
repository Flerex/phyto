@if($image->thumbnail_path)
    <img class="thumbnail" src="{{ asset($image->thumbnail_path) }}">
@else
    <div class="thumbnail nonexistent" data-tippy-content="@lang('projects.unprocessed_image')"></div>
@endif
