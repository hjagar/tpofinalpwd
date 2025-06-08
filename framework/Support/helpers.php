<?php

use PhpMvc\Framework\Core\Application;
use PhpMvc\Framework\View\View;

if (!function_exists('env')) {
    /**
     * Get the value of an environment variable.
     *
     * @param string $key The name of the environment variable.
     * @param mixed $default The default value if the variable is not set.
     * @return mixed The value of the environment variable or the default value.
     */
    function env(string $key, mixed $default = null): mixed
    {
        return $_ENV[$key] ?? $_SERVER[$key] ?? $default;
    }
}

if (!function_exists('app')) {
    function app() {
        return Application::getInstance();
    }
}

if (!function_exists('view')) {
    function view(string $path, array $data = []): string
    {
        $viewsPath = app()->getViewPath();
        $view = new View($viewsPath);

        return $view->render($path, $data);
        // extract($data);
        // ob_start();
        // require_once "{$viewsPath}/{$path}.view.php";
        // return ob_get_clean();
    }
}