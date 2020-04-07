<a href="{{ $route }}">
    {{ $content }}

    @if($sortBy === $attr && $order !== '')
        <span class="icon"><i class="fas fa-chevron-{{ $order !== 'asc' ? 'up' : 'down' }}"></i></span>
    @endif
</a>
