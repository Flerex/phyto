@extends('panel.projects.partials.layout')

@section('project_content')
    <h2 class="project-subtitle">@lang('panel.projects.tasks.create')</h2>

    <form action="{{ route('panel.projects.tasks.store', compact('project')) }}" method="POST">

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

            @if ($errors->has('compatibility'))
                <div class="field">
                    <p class="help is-danger">
                        {{ $errors->first('compatibility') }}
                    </p>
                </div>
            @endif


            {{-- Members --}}
            <div class="field">
                <label class="label">@choice('labels.projects.members', 0)</label>

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

            {{-- Process number --}}
            <div class="field">
                <label
                    class="label">@lang('panel.projects.tasks.process_number') @include('partials.info', ['info' => trans('panel.projects.tasks.process_explained')])</label>

                <div class="control">
                    <input type="number" class="input" name="process_number" value="{{ old('process_number') ?? 1 }}"
                           min="1">
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
