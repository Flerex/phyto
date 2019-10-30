@extends('panel.master')

@section('panel_content')
    <h1 class="title">@lang('panel.projects.samples.create')</h1>
    <p class="subtitle is-6">{{ trans('panel.projects.samples.feedback', ['project' => $project->name]) }}</p>

    <form action="{{ route('panel.projects.samples.store', compact('project')) }}" method="POST"> {{-- FIXME: Multipart --}}

        {{-- Name field --}}
        <div class="field">
            <label for="name" class="label">@lang('labels.name')</label>

            <div class="control has-icons-left{{ $errors->has('name') ? ' has-icons-right' : '' }}">
                <input id="name" type="text" class="input{{ $errors->has('name') ? ' is-danger' : '' }}"
                       name="name" placeholder="{{ trans('labels.name') }}" value="{{ old('name') }}" required
                       autofocus>

                <span class="icon is-small is-left"><i class="fas fa-flask"></i></span>
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


        {{-- Batch upload field --}}
        <div class="field">
            <label for="file" class="label">@lang('labels.files')</label>
            <div class="control">upload here</div>
        </div>

        @csrf
    </form>
@endsection