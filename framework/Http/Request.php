<?php

namespace PhpMvc\Framework\Http;

class Request
{
    public function __construct(
        private readonly array $query = [],
        private readonly array $request = [],
        private readonly array $cookies = [],
        private readonly array $files = [],
        private readonly array $server = []
    ) {}

    /**
     * Create a Request instance from global variables.
     *
     * @return Request
     */
    public static function createFromGlobals(): static
    {
        return new static($_GET, $_POST, $_COOKIE, $_FILES, $_SERVER);
    }

    public function __get(string $key)
    {
        $attributes = ['query', 'request', 'cookies', 'files', 'server'];

        if (in_array($key, $attributes)) {
            return $this->$key;
        } else {
            $method = 'get' . ucfirst($key);
            if (method_exists($this, $method)) {
                return $this->$method();
            }
            else {
                throw new \InvalidArgumentException("Property '$key' does not exist.");
            }            
        }
    }

    public function getUri(): string
    {
        return strtok($this->server['REQUEST_URI'], '?') ?? '/';
    }

    public function getMethod(): string
    {
        return strtoupper($this->server['REQUEST_METHOD'] ?? 'GET');
    }
}
