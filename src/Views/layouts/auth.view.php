<html>
@include('partials.head')
<body>
    <header>
        @include('partials.logo')
    </header>
    <main>
        @yield('content')
    </main>
    @include('partials.footer')
    @yield('javascript')
</body>

</html>