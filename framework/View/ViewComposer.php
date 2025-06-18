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
        return self::$globals;
    }
}
