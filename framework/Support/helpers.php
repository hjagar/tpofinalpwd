<?php

use PhpMvc\Framework\Configuration\DotEnv;
use PhpMvc\Framework\Core\Application;
use PhpMvc\Framework\View\View;
use PhpMvc\Framework\Http\RedirectResponse;

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
    function app()
    {
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
    function auth()
    {
        $auth = app()->getAuth();
        if (!$auth) {
            throw new \RuntimeException('Authentication service is not available.');
        }
        return $auth;
    }
}

if (!function_exists('role')) {
    function role(string $roles): bool
    {
        $returnValue = true;

        if (is_string($roles)) {
            $roles = explode(',', $roles);
        } elseif (!is_array($roles)) {
            throw new \InvalidArgumentException('Roles must be a string or an array.');
        }

        if (empty($roles)) {
            throw new \InvalidArgumentException('Roles cannot be empty.');
        }

        if (!auth()->check()) {
            $returnValue = false; // User is not authenticated
        }
        
        $roleIntersect = array_intersect(auth()->user()->roles, $roles);

        if (empty($roleIntersect)) {
            $returnValue = false; // No matching roles found
        }

        return $returnValue; // Placeholder for role checking logic
    }
}

if (!function_exists('view_echo')) {
    function view_echo($expr)
    {
        return "<?php echo htmlspecialchars($expr, ENT_QUOTES, 'UTF-8'); ?>";
    }
}

if (!function_exists('route')) {
    function route($routeName, $parameters = [])
    {
        return app()->getRoute($routeName, $parameters);
    }
}

if (!function_exists('sessionStarted')) {
    function sessionStarted(): bool
    {
        return session_status() === PHP_SESSION_ACTIVE;
    }
}

if (!function_exists('generateCsrfToken')) {
    function generateCsrfToken()
    {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
    }
}

if (!function_exists('regenerateCsrfToken')) {
    function regenerateCsrfToken()
    {
        unset($_SESSION['csrf_token']);
        generateCsrfToken();
    }
}

if (!function_exists('csrf')) {
    function csrf()
    {
        $returnValue = "";

        if (sessionStarted()) {
            $csrfToken = $_SESSION['csrf_token'];
            $returnValue = "<input type=\"hidden\" id=\"csrf_token\" name=\"csrf_token\" value=\"{$csrfToken}\" />";
        }

        return $returnValue;
    }
}

if (!function_exists('redirect')) {
    function redirect(string $routeName, $parameters = []): RedirectResponse
    {
        $url = app()->getRoute($routeName, $parameters);
        return new RedirectResponse($url);
    }
}

if (!function_exists('abort')) {
    function abort(int $statusCode, string $message = ''): void
    {
        http_response_code($statusCode);
        echo $message;
        exit;
    }
}
