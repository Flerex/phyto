@extends('panel.projects.partials.layout')

@section('project_content')
    <h2 class="project-subtitle">@lang('panel.projects.tasks.automated_create')</h2>

    <form action="{{ route('panel.projects.automated_tasks.store', compact('project')) }}" method="POST">

        <div class="box">

            {{-- Sample selector --}}
            <div class="field">
                <div id="sample_selector" data-project="{{ $project->getKey() }}"
                     data-old="{{ old('sample') }}"></div>
            </div>

            @if ($errors->has('sample'))
                <div class="field">
                    <p class="help is-danger">
                        {{ $errors->first('sample') }}
                    </p>
                </div>
            @endif

            {{-- Services --}}
            <div class="field">
                <label class="label">@choice('labels.task.services', 0)</label>

                <div class="control">

                    <div class="vitamined-selector" data-options="{{ $options->toJson() }}" data-name="services[]"
                         data-old="{{ old('services') ? $options->filter(fn($el) => in_array($el['value'], old('services')) ) : ''}}" data-is-multi="{{ true }}"></div>
                </div>

                @if ($errors->has('users'))
                    <p class="help is-danger">
                        {{ $errors->first('users') }}
                    </p>
                @endif
            </div>

        </div>

        {{-- Actions --}}
        <div class="has-text-centered">
            <button type="submit" class="button is-rounded is-primary">
                <span class="icon"><i class="fas fa-plus"></i></span>
                <span>@lang('general.create')</span>
            </button>
            <a href="{{ route('panel.projects.show', compact('project')) }}"
               class="button is-rounded is-light">@lang('general.cancel')</a>
        </div>

        @csrf
    </form>
@endsection
