@extends('panel.master')

@section('panel_content')
    <h1 class="title">@lang('panel.label.species')</h1>
    <div id="taxonomy-editor" data-tree="{{ $tree->toJson() }}"></div>
@endsection
