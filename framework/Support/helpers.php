<?php
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