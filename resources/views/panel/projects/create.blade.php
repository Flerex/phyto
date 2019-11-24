@extends('panel.master')

@section('panel_content')
    <h1 class="title">@lang('panel.projects.create')</h1>
    <form method="POST" action="{{ route('panel.projects.store') }}">
        @csrf

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

        {{-- Description --}}
        <div class="field">
            <label for="description" class="label">@lang('labels.description')</label>

            <div class="control">
                <textarea id="description" class="textarea" name="description"
                          placeholder="{{ trans('labels.description') }}" required>{{ old('description') }}</textarea>
            </div>

            @if ($errors->has('description'))
                <p class="help is-danger">
                    {{ $errors->first('description') }}
                </p>
            @endif
        </div>

        {{-- Catalogs --}}
        <div class="field">
            <label for="catalogs" class="label">@lang('labels.projects.catalogs')</label>

            @if(count($catalogs))
                <div class="control">
                    <div class="select is-multiple is-fullwidth">
                        <select id="catalogs" name="catalogs[]" multiple size="{{ count($catalogs) }}">
                            @foreach($catalogs as $catalog)
                                <option value="{{ $catalog->getKey() }}">{{ $catalog->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <p class="help">@lang('general.multiple_select_help')</p>
            @else
                <article class="message is-danger">
                    <div class="message-body">@lang('panel.projects.no_catalogs')</div>
                </article>
            @endif

            @if ($errors->has('catalogs'))
                <p class="help is-danger">
                    {{ $errors->first('catalogs') }}
                </p>
            @endif
        </div>


        {{-- Users --}}
        <div class="field">
            <label class="label">@lang('labels.projects.members')</label>

            <div class="control">
                <div id="user_selector"></div>
            </div>

            @if ($errors->has('users'))
                <p class="help is-danger">
                    {{ $errors->first('users') }}
                </p>
            @endif
        </div>


        {{-- Actions --}}
        <div class="field is-grouped">
            <div class="control">
                <button type="submit" class="button is-primary">
                    <span class="icon"><i class="fas fa-plus"></i></span>
                    <span>@lang('general.create')</span>
                </button>
            </div>
            <div class="control">
                <a href="{{ route('panel.projects.index') }}" class="button is-light">@lang('general.cancel')</a>
            </div>
        </div>
    </form>

@endsection
