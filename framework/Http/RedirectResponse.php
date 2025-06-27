<?php

namespace PhpMvc\Framework\Http;

class RedirectResponse
{
    protected string $url;

    public function __construct(string $url)
    {
        $this->url = $url;
        header("Location: " . $url);
        exit;
    }

    public function with(string $key, mixed $value): self
    {
        $_SESSION[$key] = $value;
        return $this;
    }
}