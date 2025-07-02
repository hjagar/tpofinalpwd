<li>
    <a href="{{ route($item->route_name) ?: '#NotImplemented#' }}">
        @if($item->html_id !== null)
            {!! parseReactive($item->nombre, $item->html_id) !!}
        @else
            {{ $item->nombre }}
        @endif
    </a>
    @if($item->isPropertySet('children'))
        <ul>
            @foreach($item->children as $child)
                @include('partials.menu-item', ['item' => $child])
            @endforeach
        </ul>
    @endif
</li>