<?php

namespace PhpMvc\Framework\Http;

use PhpMvc\Framework\Concerns\HasProperty;
use stdClass;

class Request
{
    use HasProperty;
    private readonly object $jsonPayload;

    public function __construct(
        private readonly array $query = [],
        private readonly array $request = [],
        private readonly array $cookies = [],
        private readonly array $files = [],
        private readonly array $server = []
    ) {
        $this->parseJsonBody();

        $this->setPropertyContainers([
            $this,
            $this->query,
            $this->request,
            $this->cookies,
            $this->files,
            $this->server,
            $this->jsonPayload
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
        } elseif (property_exists($this->jsonPayload, $key)) {
            return $this->jsonPayload->$key;
        } else {
            $method = 'get' . ucfirst($key);

            if (method_exists($this, $method)) {
                return $this->$method();
            } else {
                return null; //throw new \InvalidArgumentException("Property '$key' does not exist.");
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
        return array_merge($this->query, $this->request, $this->jsonPayload);
    }

    public function header(string $key): ?string
    {
        $normalized = 'HTTP_' . strtoupper(str_replace('-', '_', $key));
        return $_SERVER[$normalized] ?? null;
    }

    public function isJson(): bool
    {
        $contentType = $_SERVER['CONTENT_TYPE'] ?? $_SERVER['HTTP_CONTENT_TYPE'] ?? '';
        return str_starts_with(strtolower($contentType), 'application/json');
    }

    protected function parseJsonBody(): void
    {
        if ($this->isJson()) {
            $input = file_get_contents('php://input');
            $this->jsonPayload = json_decode($input) ?? new stdClass;
        }
        else {
            $this->jsonPayload = new stdClass;
        }
    }
}
