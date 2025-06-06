<?php

// Obtener puerto desde argumentos del comando
$argv = $_SERVER['argv'];
$port = $argv[2] ?? 8000;

$host = 'localhost';
$docroot = 'src/public';
$url = "http://{$host}:{$port}";

// Verificar si el puerto ya está en uso
$connection = @fsockopen($host, $port);
if (is_resource($connection)) {
    fclose($connection);
    echo "⚠️  El puerto {$port} ya está en uso. No se pudo iniciar el servidor en {$url}\n";
    exit(1);
}

// Mostrar mensajes
echo "✅ Servidor iniciado en {$url}\n";
echo "ℹ️  Pulse Ctrl+C para finalizar el servidor\n";

// Abrir navegador según sistema operativo
switch (PHP_OS_FAMILY) {
    case 'Windows':
        exec("start {$url}");
        break;
    case 'Darwin':
        exec("open {$url}");
        break;
    case 'Linux':
        exec("xdg-open {$url}");
        break;
    default:
        echo "❗ No se pudo abrir el navegador automáticamente.\n";
        break;
}

// Ejecutar servidor integrado de PHP
passthru("php -S {$host}:{$port} -t {$docroot}");
