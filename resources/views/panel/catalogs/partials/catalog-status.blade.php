@if($catalog->status === \App\Domain\Enums\CatalogStatus::EDITING()->getValue())
    <span class="tag is-light is-warning">{{ trans('labels.catalog.status.' . $catalog->status) }}</span>
@elseif($catalog->status === \App\Domain\Enums\CatalogStatus::SEALED()->getValue())
    <span class="tag is-light is-success">{{ trans('labels.catalog.status.' . $catalog->status) }}</span>
@elseif($catalog->status === \App\Domain\Enums\CatalogStatus::OBSOLETE()->getValue())
    <span class="tag is-light is-danger">{{ trans('labels.catalog.status.' . $catalog->status) }}</span>
@else
    <span class="tag is-light">{{ trans('labels.catalog.status.' . $catalog->status) }}</span>
@endif
