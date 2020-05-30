@extends('panel.projects.partials.layout')

@section('project_content')
    <div class="button-strip">
        <a href="{{ route('panel.projects.samples.create', compact('project')) }}"
           class="button is-rounded is-link">
            <span class="icon is-left"><i class="fas fa-microscope"></i></span>
            <span>@lang('panel.projects.samples.create')</span>
        </a>
    </div>
    @if(count($samples))
        <table class="table is-fullwidth is-boxed">
            <thead>
            <th>@lang('labels.name')</th>
            <th>@lang('labels.description')</th>
            <th>@lang('labels.samples.taken_on')</th>
            <th>{{ trans_choice('panel.projects.images.label', 0) }}</th>
            <th class="has-text-right">@lang('general.actions')</th>
            </thead>

            <tbody>
            @foreach($samples as $sample)
                <tr>
                    <td>{{ $sample->name }}</td>
                    <td>{{ $sample->description }}</td>
                    <td>{{ $sample->taken_on->format(trans('general.date_format')) }}</td>
                    <td>{{ $sample->images_count }}</td>
                    <td class="has-text-right">
                        <a class="button is-rounded is-light is-link is-small has-text-weight-bold"
                           href="{{ route('panel.projects.images.index', compact('project', 'sample')) }}">
                            @lang('general.view')
                        </a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        {{ $samples->links() }}
    @else
        <div class="message is-info">
            <div class="message-body">@lang('panel.projects.samples.no_samples')</div>
        </div>
    @endif
@endsection
