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
            <th style="width: 8%">
                <a href="{{ route('panel.catalogs.index') }}?sortBy=id&order={{ $order !== 'asc' ? 'asc' : 'desc' }}">
                    @lang('labels.id')
                    @if($sortBy == 'id' && $order !== '')
                        <span class="icon"><i class="fas fa-chevron-{{ $order !== 'asc' ? 'down' : 'up' }}"></i></span>
                    @endif
                </a>
            </th>
            <th>
                <a href="{{ route('panel.catalogs.index') }}?sortBy=name&order={{ $order !== 'asc' ? 'asc' : 'desc' }}">
                    @lang('labels.name')
                    @if($sortBy == 'name' && $order !== '')
                        <span class="icon"><i class="fas fa-chevron-{{ $order !== 'asc' ? 'down' : 'up' }}"></i></span>
                    @endif
                </a>
            </th>
            <th>
                <a href="{{ route('panel.catalogs.index') }}?sortBy=status&order={{ $order !== 'asc' ? 'asc' : 'desc' }}">
                    @lang('labels.catalog.status_label')
                    @if($sortBy == 'status' && $order !== '')
                        <span class="icon"><i class="fas fa-chevron-{{ $order !== 'asc' ? 'down' : 'up' }}"></i></span>
                    @endif
                </a></th>
            <th>
                <a href="{{ route('panel.catalogs.index') }}?sortBy=created_at&order={{ $order !== 'asc' ? 'asc' : 'desc' }}">
                    @lang('labels.created_at')
                    @if($sortBy == 'created_at' && $order !== '')
                        <span class="icon"><i class="fas fa-chevron-{{ $order !== 'asc' ? 'down' : 'up' }}"></i></span>
                    @endif
                </a>
            </th>
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
                        <div class="catalog-options">
                            <div class="catalog-options__option">
                                <a href="{{ route('panel.catalogs.create_from', compact('catalog')) }}"
                                   class="button is-light is-small"
                                   title="@lang('panel.catalogs.create_from', ['catalog' => $catalog->name])">
                                    <span class="icon"><i class="fas fa-clone"></i></span>
                                </a>
                            </div>
                            @if($catalog->isEditable())
                                <div class="catalog-options__option">
                                    <a href="{{ route('panel.catalogs.edit', compact('catalog')) }}"
                                       class="button is-light is-small" title="@lang('general.edit')">
                                        <span class="icon"><i class="fas fa-pencil-alt"></i></span>
                                    </a>
                                </div>
                                <div class="catalog-options__option">
                                    <form action="{{ route('panel.catalogs.seal', compact('catalog')) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="button is-warning is-small"
                                                title="@lang('panel.catalogs.seal')">
                                            <span class="icon"><i class="fas fa-stamp"></i></span>
                                        </button>
                                    </form>
                                </div>

                                <div class="catalog-options__option">
                                    <form action="{{ route('panel.catalogs.destroy', compact('catalog')) }}"
                                          method="POST">
                                        @method('DELETE')
                                        @csrf
                                        <button type="submit" class="button is-danger is-small"
                                                title="@lang('panel.catalogs.destroy')">
                                            <span class="icon"><i class="fas fa-trash-alt"></i></span>
                                        </button>
                                    </form>
                                </div>


                            @endif

                            @if($catalog->isSealed())
                                <div class="catalog-options__option">
                                    <form action="{{ route('panel.catalogs.mark_as_obsolete', compact('catalog')) }}"
                                          method="POST">
                                        @csrf
                                        <button type="submit" class="button is-danger is-small"
                                                title="@lang('panel.catalogs.mark_as_obsolete')">
                                            <span class="icon"><i class="fas fa-times"></i></span>
                                        </button>
                                    </form>
                                </div>
                            @endif
                            @if($catalog->isObsolete())
                                <div class="catalog-options__option">
                                    <form action="{{ route('panel.catalogs.restore', compact('catalog')) }}"
                                          method="POST">
                                        @csrf
                                        <button type="submit" class="button is-light is-small"
                                                title="@lang('panel.catalogs.restore')">
                                            <span class="icon"><i class="fas fa-undo-alt"></i></span>
                                        </button>
                                    </form>
                                </div>
                            @endif
                        </div>

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
