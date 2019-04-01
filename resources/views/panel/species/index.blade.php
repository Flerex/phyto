@extends('panel.master')

@section('panel_content')
    <h1 class="title">@lang('panel.label.species')</h1>
    <div id="hierarchy_selector" data-lang="{{ json_encode($hierarchySelectorLang) }}"></div>
@endsection
