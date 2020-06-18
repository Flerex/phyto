@extends('panel.master')

@section('panel_content')
    <div class="level">
        <div class="level-left">
            <div class="level-item">
                <h1 class="title">@lang('panel.label.species')</h1>
            </div>
        </div>
        <div class="level-right">
            <div class="level-item">
                <a href="{{ route('panel.species.download') }}" class="button is-rounded is-small">
                    <span class="icon"><i class="fas fa-download"></i></span>
                </a>
            </div>
        </div>
    </div>
    <div id="taxonomy-editor" data-tree="{{ $tree->toJson() }}"></div>
@endsection
