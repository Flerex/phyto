@extends('panel.master')

@section('panel_content')
    <div class="level">
        <div class="level-left">
            <div class="level-item">
                <h1 class="title">@lang('panel.label.catalogs')</h1>
            </div>
        </div>

        <div class="level-right">
            <div class="level-item">
                <a href="{{ route('panel.catalogs.create') }}" class="button is-primary is-pulled-right">
                    <span class="icon is-left"><i class="fas fa-user-plus"></i></span>
                    <span>@lang('panel.catalogs.create')</span>
                </a>
            </div>
        </div>
    </div>

    @if(count($catalogs))
        <table class="table is-fullwidth">
            <thead>
            <th style="width: 5%">@lang('labels.id')</th>
            <th>@lang('labels.name')</th>
            <th>@lang('labels.catalog.status_label')</th>
            <th>@lang('labels.created_at')</th>
            </thead>

            <tbody>
            @foreach($catalogs as $catalog)
                <tr>
                    <th>{{ $catalog->id }}</th>
                    <td><a href="{{ route('panel.catalogs.edit', compact('catalog')) }}">{{ $catalog->name }}</a></td>
                    <td>{{ trans('labels.catalog.status.' . $catalog->status) }}</td>
                    <td>{{ $catalog->created_at->diffForHumans() }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
        {{ $catalogs->links() }}
    @else
        <div class="message is-info">
            <div class="message-body">@lang('panel.catalogs.no_catalogs')</div>
        </div>
    @endif

@endsection
