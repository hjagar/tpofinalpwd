<li>
    <a href="{{ route($item->route_name) ?: '#NotImplemented#' }}">{{ $item->nombre }}</a>

    @if($item->isPropertySet('children'))
        <ul>
            @foreach($item->children as $child)
                @include('partials.menu-item', ['item' => $child])
            @endforeach
        </ul>
    @endif
</li>