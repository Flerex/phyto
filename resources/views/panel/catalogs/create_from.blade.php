@extends('panel.master')

@section('panel_content')
    <h1 class="title">@lang('panel.catalogs.create_from', ['catalog' => $catalog->name])</h1>
    <form method="POST" action="{{ route('panel.catalogs.store') }}">
        @csrf

        <div class="box">

            {{-- Name --}}
            <div class="field">
                <label for="name" class="label">@lang('labels.name')</label>

                <div class="control has-icons-left{{ $errors->has('name') ? ' has-icons-right' : '' }}">
                    <input id="name" type="text" class="input{{ $errors->has('name') ? ' is-danger' : '' }}"
                           name="name" placeholder="{{ trans('labels.name') }}" value="{{ old('name') }}" required
                           autofocus>

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
        </div>


        {{-- Species field --}}

        <div class="field">
            <label class="label">@lang('labels.catalog.species')</label>

            @if ($errors->has('hierarchy'))
                <p class="help is-danger">
                    {{ $errors->first('hierarchy') }}
                </p>
            @endif
        </div>
        <div id="hierarchy_selector" data-mode="select" data-catalog="{{ $catalog->getKey() }}"></div>

        {{-- Actions --}}
        <div class="has-text-centered">
            <button type="submit" class="button is-primary is-rounded" name="mode" value="create">
                <span class="icon"><i class="fas fa-plus"></i></span>
                <span>@lang('general.create')</span>
            </button>
            <button type="submit" class="button is-warning is-rounded" name="mode" value="seal">
                <span class="icon"><i class="fas fa-stamp"></i></span>
                <span>@lang('panel.catalogs.create_seal_label')</span>
            </button>
        </div>
    </form>

@endsection
