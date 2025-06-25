<nav>
    <ul>
        @foreach ($headerMenu as $item)
            @if (!$item->has_user && !isset($user))
                @include('partials.menu-item', ['item' => $item])
            @elseif ($item->has_user && isset($user))
                @include('partials.menu-item', ['item' => $item])
            @endif
        @endforeach
    </ul>
</nav>