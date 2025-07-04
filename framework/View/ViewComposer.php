<?php

namespace PhpMvc\Framework\View;

class ViewComposer
{
    protected static array $globals = [];

    public static function share(string $key, mixed $value): void
    {
        self::$globals[$key] = $value;
    }

    public static function getShared(): array
    {
        if (auth()->check()) {
            self::$globals['user'] = auth()->user();
        }

        if (isset($_SESSION['flash'])) {
            self::$globals['flash'] = $_SESSION['flash'];
            unset($_SESSION['flash']);
        }

        return self::$globals;
    }
}
