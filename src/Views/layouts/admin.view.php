<html>
@include('partials.head')
<body>
    <header>
        @include('partials.logo')
    </header>
    @include('partials.main-menu')
    <main>
        @yield('content')
    </main>
    @include('partials.footer')
</body>

</html>