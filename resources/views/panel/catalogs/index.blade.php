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
                <a href="{{ route('panel.catalogs.create') }}" class="button is-gray is-rounded is-pulled-right">
                    <span class="icon is-left"><i class="fas fa-book"></i></span>
                    <span>@lang('panel.catalogs.create')</span>
                </a>
            </div>
        </div>
    </div>



    @if(count($catalogs))
        <div id="tables">
            <div class="box">
                <table class="table is-fullwidth">
                    <thead>
                    <th class="has-text-right" style="width: 8%">@include('partials.sortable-link', ['attr' => 'id', 'content' => trans('labels.id') ])</th>
                    <th>@include('partials.sortable-link', ['attr' => 'name', 'content' => trans('labels.name') ])</th>
                    <th>@include('partials.sortable-link', ['attr' => 'status', 'content' => trans('labels.catalog.status_label') ])</th>
                    <th>@include('partials.sortable-link', ['attr' => 'created_at', 'content' => trans('labels.created_at') ])</th>
                    <th class="has-text-right" style="width: 20%">@lang('general.actions')</th>
                    </thead>

                    <tbody>
                    @foreach($catalogs as $catalog)
                        <tr>
                            <th class="has-text-right">{{ $catalog->id }}</th>
                            <td>{{ $catalog->name }}</td>
                            <td>@include('panel.catalogs.partials.catalog-status', compact('catalog'))</td>
                            <td>{{ $catalog->created_at->diffForHumans() }}</td>
                            <td class="has-text-right">
                                @include('panel.catalogs.partials.actions', compact('catalog'))
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            {{ $catalogs->links() }}
        </div>
    @else
        <div class="message is-info">
            <div class="message-body">@lang('panel.catalogs.no_catalogs')</div>
        </div>
    @endif

@endsection
