{{-- Create from another catalog --}}
<a href="{{ route('panel.catalogs.create_from', compact('catalog')) }}"
   class="button is-rounded is-light is-small"
   data-tippy-content="@lang('panel.catalogs.create_from', ['catalog' => $catalog->name])">
    <span class="icon"><i class="fas fa-clone"></i></span>
</a>

@if($catalog->isEditable())

    {{-- Edit --}}
    <a href="{{ route('panel.catalogs.edit', compact('catalog')) }}"
       class="button is-rounded is-light is-small" data-tippy-content="@lang('general.edit')">
        <span class="icon"><i class="fas fa-pencil-alt"></i></span>
    </a>

    {{-- Seal --}}
    <div class="button-confirmation" data-type="warning"
         data-route="{{ route('panel.catalogs.seal', compact('catalog')) }}"
         data-icon="fas fa-stamp" data-action="@lang('panel.catalogs.seal')"></div>

    {{-- Destroy --}}
    <div class="button-confirmation" data-type="danger"
         data-route="{{ route('panel.catalogs.destroy', compact('catalog')) }}" data-method="DELETE"
         data-icon="fas fa-trash-alt" data-action="@lang('panel.catalogs.destroy')"></div>
@endif

@if($catalog->isSealed())
    <div class="button-confirmation" data-type="danger"
         data-route="{{ route('panel.catalogs.mark_as_obsolete', compact('catalog')) }}"
         data-icon="fas fa-times" data-action="@lang('panel.catalogs.mark_as_obsolete')"></div>
@endif
@if($catalog->isObsolete())
    <button type="submit" class="button is-light is-rounded is-small" data-tippy-content="@lang('panel.catalogs.restore')"
            onclick="document.getElementById('restore-catalog-form').submit(); return false">
        <span class="icon"><i class="fas fa-undo-alt"></i></span>
    </button>
    <form id="restore-catalog-form" action="{{ route('panel.catalogs.restore', compact('catalog')) }}"
          method="POST">
        @csrf
    </form>
@endif
