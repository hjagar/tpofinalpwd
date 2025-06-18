<li>
    <a href="{{ $item->url }}">{{ $item->menombre }}</a>

    @if(!empty($item->children))
        <ul>
            @foreach($item->children as $child)
                @include('partials.menu-item', ['item' => $child])
            @endforeach
        </ul>
    @endif
</li>