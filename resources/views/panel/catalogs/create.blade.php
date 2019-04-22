@extends('panel.master')

@section('panel_content')
    <h1 class="title">@lang('panel.catalogs.create_label')</h1>
    <form method="POST" action="{{ route('panel.catalogs.store') }}">
        @csrf

        {{-- Name --}}
        <div class="field">
            <label for="name" class="label">@lang('labels.name')</label>

            <div class="control has-icons-left{{ $errors->has('name') ? ' has-icons-right' : '' }}">
                <input id="name" type="text" class="input{{ $errors->has('name') ? ' is-danger' : '' }}"
                       name="name" placeholder="{{ trans('labels.name') }}" value="{{ old('name') }}" required autofocus>

                <span class="icon is-small is-left"><i class="fas fa-book"></i></span>
                @if ($errors->has('name'))
                    <span class="icon is-small is-right"><i class="fas fa-exclamation-triangle"></i></span>
                @endif
            </div>

            @if ($errors->has('name'))
                <p class="help is-danger">
                    {{ $errors->first('name') }}
                </p>
            @endif
        </div>


        {{-- Species --}}
        <div class="field">
            <label class="label">@lang('labels.catalog.species')</label>

            @if ($errors->has('hierarchy'))
                <p class="help is-danger">
                    {{ $errors->first('hierarchy') }}
                </p>
            @endif
        </div>
        <div id="hierarchy_selector" data-lang="{{ json_encode($hierarchySelectorLang) }}" data-mode="select"></div>

        {{-- Actions --}}
        <div class="field is-grouped">
            <div class="control">
                <button type="submit" class="button is-primary" name="mode" value="create">
                    <span class="icon"><i class="fas fa-plus"></i></span>
                    <span>@lang('general.create')</span>
                </button>
            </div>
            <div class="control">
                <button type="submit" class="button is-warning" name="mode" value="seal">
                    <span class="icon"><i class="fas fa-stamp"></i></span>
                    <span>@lang('panel.catalogs.seal')</span>
                </button>
            </div>
            <div class="control">
                <a href="{{ route('panel.catalogs.index') }}" class="button is-light">@lang('general.cancel')</a>
            </div>
        </div>
    </form>

@endsection
