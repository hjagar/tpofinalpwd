<?php

namespace PhpMvc\Framework\Http;

use PhpMvc\Framework\Concerns\HasProperty;

class Request
{
    use HasProperty;

    public function __construct(
        private readonly array $query = [],
        private readonly array $request = [],
        private readonly array $cookies = [],
        private readonly array $files = [],
        private readonly array $server = []
    ) {
        $this->setPropertyContainers([
            $this,
            $this->query,
            $this->request,
            $this->cookies,
            $this->files,
            $this->server
        ]);
    }

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
        if (property_exists($this, $key)) {
            return $this->$key;
        } elseif (array_key_exists($key, $this->server)) {
            return $this->server[$key];
        } elseif (array_key_exists($key, $this->query)) {
            return $this->query[$key];
        } elseif (array_key_exists($key, $this->request)) {
            return $this->request[$key];
        } elseif (array_key_exists($key, $this->cookies)) {
            return $this->cookies[$key];
        } elseif (array_key_exists($key, $this->files)) {
            return $this->files[$key];
        } else {
            $method = 'get' . ucfirst($key);

            if (method_exists($this, $method)) {
                return $this->$method();
            } else {
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
        return strtoupper($this->server['REQUEST_METHOD'] ?? HttpMethods::GET->value);
    }

    public function all(): array
    {
        return array_merge($this->query, $this->request);
    }
}
