@extends('panel.master')

@section('panel_content')
    <h1 class="title">@lang('panel.projects.samples.create')</h1>
    <p class="subtitle is-6">{{ trans('panel.projects.samples.feedback', ['project' => $project->name]) }}</p>

    <form action="{{ route('panel.projects.samples.store', compact('project')) }}" method="POST">

        <div class="box">
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

            {{-- Taken on --}}
            <div class="field">
                <label for="description" class="label">@lang('labels.samples.taken_on')</label>

                <div class="control">
                    <input class="input" type="date" name="taken_on"
                           value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}">
                </div>

                @if ($errors->has('taken_on'))
                    <p class="help is-danger">
                        {{ $errors->first('taken_on') }}
                    </p>
                @endif
            </div>


            {{-- Batch upload field --}}
            <div class="field">
                <label for="file" class="label">@lang('labels.files')</label>
                <div class="control">
                    <div class="upload-dropzone empty" id="upload-dropzone"
                         data-url="{{ route('panel.projects.samples.upload', compact('project')) }}"
                         data-help="@lang('uploads.help')"></div>
                    <div class="notification is-danger upload-dropzone__error">
                        <button class="delete" type="button"
                                onclick="document.querySelector('.upload-dropzone__error').classList.remove('active')"></button>
                        Format not supported.
                    </div>
                </div>
                @if ($errors->has('files'))
                    <p class="help is-danger">
                        {{ $errors->first('files') }}
                    </p>
                @endif
            </div>
        </div>

        {{-- Actions --}}
        <div class="has-text-centered">
            <button type="submit" class="button is-rounded is-primary" disabled>
                <span class="icon"><i class="fas fa-plus"></i></span>
                <span>@lang('general.create')</span>
            </button>
            <a href="{{ route('panel.projects.show', compact('project')) }}"
               class="button is-rounded is-light">@lang('general.cancel')</a>
        </div>

        @csrf
    </form>
@endsection

@push('scripts')
    <script src="{{ asset('js/sample-upload.js') }}"></script>
@endpush
