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
            <th class="has-text-right" style="width: 8%">@lang('general.actions')</th>
            </thead>

            <tbody>
            @foreach($catalogs as $catalog)
                <tr>
                    <th>{{ $catalog->id }}</th>
                    <td>{{ $catalog->name }}</td>
                    <td>{{ trans('labels.catalog.status.' . $catalog->status) }}</td>
                    <td>{{ $catalog->created_at->diffForHumans() }}</td>
                    <td class="has-text-right">
                        @if($catalog->isEditable())
                            <div class="level">
                                <div class="level-item" style="margin-right: 5px;">
                                    <a href="{{ route('panel.catalogs.edit', compact('catalog')) }}"
                                       class="button is-light is-small" title="@lang('general.edit')">
                                        <span class="icon"><i class="fas fa-pencil-alt"></i></span>
                                    </a>
                                </div>
                                <div class="level-item">
                                    <form action="{{ route('panel.catalogs.seal', compact('catalog')) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="button is-warning is-small"
                                                title="@lang('panel.catalogs.seal')">
                                            <span class="icon"><i class="fas fa-stamp"></i></span>
                                        </button>
                                    </form>
                                </div>

                            </div>
                        @endif

                        @if($catalog->isSealed())
                            <form action="{{ route('panel.catalogs.mark_as_obsolete', compact('catalog')) }}"
                                  method="POST">
                                @csrf
                                <button type="submit" class="button is-danger is-small"
                                        title="@lang('panel.catalogs.mark_as_obsolete')">
                                    <span class="icon"><i class="fas fa-times"></i></span>
                                </button>
                            </form>
                        @endif
                        @if($catalog->isObsolete())
                            <form action="{{ route('panel.catalogs.restore', compact('catalog')) }}"
                                  method="POST">
                                @csrf
                                <button type="submit" class="button is-light is-small"
                                        title="@lang('panel.catalogs.restore')">
                                    <span class="icon"><i class="fas fa-undo-alt"></i></span>
                                </button>
                            </form>
                        @endif
                    </td>
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
