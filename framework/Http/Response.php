<?php

namespace PhpMvc\Framework\Http;

use Exception;
use PhpMvc\Framework\Core\Application;
use PhpMvc\Framework\Http\Constants\ContentType;
use PhpMvc\Framework\Http\Constants\HeaderType;

class Response
{
    private array $defaultHeaders = [
        HeaderType::CONTENT_TYPE => ContentType::TEXT_HTML,
        HeaderType::POWERED_BY => Application::NAME,
        HeaderType::VERSION => Application::VERSION,
        HeaderType::AUTHOR => Application::AUTHOR,
    ];

    private array $headers;

    public function __construct(
        private ?string $content = '',
        private int $status = HttpStatus::OK->value,
        array $headers = []
    ) {
        $this->headers = array_unique(array_merge($this->defaultHeaders, $headers));
    }

    public function __get($name)
    {
        if (property_exists($this, $name)) {
            return $this->$name;
        } else {
            throw new Exception(__CLASS__ . ".{$name} no existe");
        }
    }

    protected function sendCodeAndHeaders() {
        http_response_code($this->status);

        foreach ($this->headers as $name => $value) {
            header("$name: $value");
        }
    }

    /**
     * Send the response to the client.
     *
     * @return void
     */
    public function send(): void
    {
        $this->sendCodeAndHeaders();
        echo $this->content;
    }
}
