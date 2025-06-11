<?php

use PhpMvc\Framework\Configuration\DotEnv;
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
        return DotEnv::getOption($key, $default);
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
    }
}

if (!function_exists('auth')) {
    function auth() {

    }
}

if (!function_exists('role')) {
    function role(string $role) {

    }
}

if (!function_exists('view_echo')) {
    function view_echo($expr) {
        return "<?php echo htmlspecialchars($expr, ENT_QUOTES, 'UTF-8'); ?>";
    }
}