<?php

namespace PhpMvc\Framework\Configuration;

class DotEnv
{
    public static function load(string $path): void
    {
        if (!file_exists($path)) {
            throw new \InvalidArgumentException("El archivo .env no existe en $path");
        }

        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        foreach ($lines as $line) {
            if (!str_starts_with(trim($line), '#')) {
                [$name, $value] = array_map('trim', explode('=', $line, 2));

                if (!array_key_exists($name, $_ENV)) {
                    putenv("$name=$value");
                    $_ENV[$name] = $value;
                    $_SERVER[$name] = $value;
                }
            }
        }
    }

    public static function getOption($key, $default)
    {
        $value = $_ENV[$key] ?? $_SERVER[$key] ?? $default;
        $returnValue = $value;

        switch ($value) {
            case 'true':
            case 'yes':
                $returnValue = true;
                break;
            case 'false':
            case 'no':
                $returnValue = false;
                break;
        }

        return $returnValue;
    }
}
