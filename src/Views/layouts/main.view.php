<html>

@include('partials.head')

<body>
    <header>
        @include('partials.logo')
        @include('partials.header-menu')
    </header>
    @include('partials.main-menu')
    <main>
        @yield('content')
    </main>
    @include('partials.footer')
    @yield('javascript')
</body>

</html>