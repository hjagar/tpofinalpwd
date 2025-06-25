<nav>
    <ul>
        @foreach ($mainMenu as $item)
            @if (!$item->has_user)
                @include('partials.menu-item', ['item' => $item])
            @elseif ($item->has_user && isset($user))
                @role($item->role_names)
                    @include('partials.menu-item', ['item' => $item])
                @endrole
            @endif
        @endforeach
    </ul>
</nav>