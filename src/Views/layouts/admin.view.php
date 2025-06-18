<html>

<head>
    <title>@yield('title')</title>
    <link rel="stylesheet" href="/css/styles.css" />
</head>

<body>
    <header>
        <div class="logo">Tienda Tuya</div>
    </header>
    <nav>
        <ul><!-- hacer menu dinamico y/o componente con @include -->
            <li><a href="#">Inicio</a></li>
            <li><a href="#">Contacto</a></li>
            <li>
                <a href="#" style="color:red;">Administración</a>
                <ul>
                    <li><a href="#">Submenu</a></li>
                </ul>
            </li>
        </ul>
    </nav>
    <main>
        @yield('content')
    </main>
    <footer>
        <p>&copy; 2025 Tienda Tuya</p>
    </footer>
</body>

</html>