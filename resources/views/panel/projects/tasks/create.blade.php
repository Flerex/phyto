@extends('panel.master')

@section('panel_content')
    <h1 class="title">@lang('panel.projects.tasks.create')</h1>
    <p class="subtitle is-6">{{ trans('panel.projects.samples.feedback', ['project' => $project->name]) }}</p>

    <form action="{{ route('panel.projects.tasks.store', compact('project')) }}" method="POST">

        <div class="box">

            {{-- Sample selector --}}
            <div class="field">
                <label class="label">@choice('panel.projects.samples.label', 1)</label>

                <div class="control">
                    <div id="sample_selector" data-project="{{ $project->getKey() }}"
                         data-old="{{ old('sample') }}"></div>
                </div>

                @if ($errors->has('sample'))
                    <p class="help is-danger">
                        {{ $errors->first('sample') }}
                    </p>
                @endif
            </div>

            {{-- Members --}}
            <div class="field">
                <label class="label">@lang('labels.projects.members')</label>

                <div class="control">
                    <div id="member_selector" data-project="{{ $project->getKey() }}"
                         data-old="{{ json_encode(old('users')) }}"></div>
                </div>

                @if ($errors->has('users'))
                    <p class="help is-danger">
                        {{ $errors->first('users') }}
                    </p>
                @endif
            </div>

            {{-- Tags per user --}}
            <div class="field">
                <label
                    class="label">@lang('panel.projects.tasks.repeat_images') @include('partials.info', ['info' => trans('panel.projects.tasks.repeat_images_explain')])</label>

                <div class="control">
                    <input type="number" class="input" name="repeat_images" value="{{ old('repeat_images') ?? 1 }}" min="1">
                </div>

                @if ($errors->has('repeat_images'))
                    <p class="help is-danger">
                        {{ $errors->first('repeat_images') }}
                    </p>
                @endif
            </div>

            {{-- Process number --}}
            <div class="field">
                <label class="label">@lang('panel.projects.tasks.process_number') @include('partials.info', ['info' => trans('panel.projects.tasks.process_explained')])</label>

                <div class="control">
                    <input type="number" class="input" name="process_number" value="{{ old('process_number') ?? 1 }}" min="1">
                </div>

                @if ($errors->has('process_number'))
                    <p class="help is-danger">
                        {{ $errors->first('process_number') }}
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
