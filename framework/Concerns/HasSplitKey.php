<?php

namespace PhpMvc\Framework\Concerns;

trait HasSplitKey
{
    public function splitKey($key): string
    {
        $splittedKey = explode('.', $key);
        return end($splittedKey);
    }
}
