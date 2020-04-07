@if($catalog->status === \App\Enums\CatalogStatus::EDITING)
    <span class="tag is-light is-warning">{{ trans('labels.catalog.status.' . $catalog->status) }}</span>
@elseif($catalog->status === \App\Enums\CatalogStatus::SEALED)
    <span class="tag is-light is-success">{{ trans('labels.catalog.status.' . $catalog->status) }}</span>
@elseif($catalog->status === \App\Enums\CatalogStatus::OBSOLETE)
    <span class="tag is-light is-danger">{{ trans('labels.catalog.status.' . $catalog->status) }}</span>
@else
    <span class="tag is-light">{{ trans('labels.catalog.status.' . $catalog->status) }}</span>
@endif
