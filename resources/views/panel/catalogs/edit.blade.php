@extends('panel.master')

@section('panel_content')
    <h1 class="title">@lang('panel.catalogs.edit_label')</h1>
    <form method="POST" action="{{ route('panel.catalogs.update', compact('catalog')) }}">
        @method('PUT')
        @csrf


        <div class="box">
            {{-- Name --}}
            <div class="field">
                <label for="name" class="label">@lang('labels.name')</label>

                <div class="control has-icons-left{{ $errors->has('name') ? ' has-icons-right' : '' }}">
                    <input id="name" type="text" class="input{{ $errors->has('name') ? ' is-danger' : '' }}"
                           name="name" placeholder="{{ trans('labels.name') }}"
                           value="{{ old('name') ?? $catalog->name }}" required
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
        <div id="taxonomy-selector" data-tree="{{ $tree->toJson() }}" data-nodes="{{ json_encode($nodes) }}"></div>

        {{-- Actions --}}
        <div class="detatched-control-strip has-text-centered">
            <button type="submit" class="button is-primary is-rounded">@lang('general.update')</button>
        </div>
    </form>

@endsection
