<nav>
    <ul>
        @foreach($menus as @item)
            @include('partials.menu-item', ['item' => $item])
        @endforeach
    </ul>
</nav>