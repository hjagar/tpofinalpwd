<?php

namespace PhpMvc\Framework\Concerns;

trait SplitKey
{
    public function splitKey($key): string
    {
        $splittedKey = explode('.', $key);
        return end($splittedKey);
    }
}
