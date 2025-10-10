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

if(!function_exists('json')) {
    function json(mixed $data) {
        return [
            'data' => $data
        ];
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

if (!function_exists('csrf_token')) {
    function csrf_token(){
        $returnValue = "";

        if (sessionStarted()) {
            $returnValue = $_SESSION['csrf_token'];
        }

        return $returnValue;
    }
}

if (!function_exists('csrf')) {
    function csrf()
    {
        $returnValue = "";
        $csrfToken = csrf_token();

        if ($csrfToken) {
            $returnValue = "<input type=\"hidden\" id=\"csrf_token\" name=\"csrf_token\" value=\"{$csrfToken}\" />";
        }

        return $returnValue;
    }
}

if (!function_exists('csrf_meta')) {
    function csrf_meta()
    {
        $returnValue = "";
        $csrfToken = csrf_token();

        if ($csrfToken) {
            $returnValue = "<meta name=\"csrf_token\" content=\"{$csrfToken}\" />";
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

if (!function_exists('emailPattern')) {
    function emailPattern() {
        $regex = '/[a-zA-Z0-9!#$%&\'*\/=?^_`\{\|\}~\+\-]([\.]?[a-zA-Z0-9!#$%&\'*\/=?^_`\{\|\}~\+\-])+@[a-zA-Z0-9]([^@&%$\/\(\)=?¿!\.,:;]|\d)+[a-zA-Z0-9][\.][a-zA-Z]{2,4}([\.][a-zA-Z]{2})?/m';
        return "pattern=\"{$regex}\"";
    }
}

if (!function_exists('parseReactive')) {
    /**
     * Envuelve {0} o {algo} en un span reactivo.
     *
     * @param string $name  El texto original.
     * @param string|null $htmlId  El ID para r-text, o null.
     * @return string
     */
    function parseReactive(string $name, ?string $htmlId = null): string
    {
        $pattern = '/\{(.+?)\}/';

        if (preg_match($pattern, $name)) {
            return preg_replace_callback($pattern, function ($m) use ($htmlId) {
                $id = $htmlId ?? '';
                return "<span r-text=\"$id\">{$m[1]}</span>";
            }, $name);
        }

        return $name;
    }
}

if (!function_exists('reactiveRoutes')) {
    /**
     * Obtiene el string que define las rutas reactivas,
     * como una cadena JavaScript que se asigna a window.ROUTES.
     * 
     * @return string
     */
    function reactiveRoutes() {
        return app()->getReactiveRoutes();
    }
}

if (!function_exists('user')) {
    function user() {
        return auth()->user();
    }
}   