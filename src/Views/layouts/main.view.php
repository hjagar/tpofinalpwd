<html>

<head>
    <title>@yield('title')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
    <link rel="stylesheet" href="/css/styles.css" />
</head>

<body>
    <header>
        @include('partials.logo')
        <div class="busqueda">
            <input type="text" placeholder="Buscar productos..." />
            <button>Buscar</button>
        </div>
        @include('partials.header-menu')
    </header>
    @include('partials.main-menu-static')
    <main>
        @yield('content')
    </main>
    @include('partials.footer')
</body>

</html>