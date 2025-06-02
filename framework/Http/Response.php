<?php
namespace PhpMvc\Framework\Http;

class Response {
    public function __construct(
        private ?string $content = '',
        private int $status = HttpStatus::OK->value,
        private array $headers = []
    ) {}

    /**
     * Send the response to the client.
     *
     * @return void
     */
    public function send(): void {
        http_response_code($this->status);
        foreach ($this->headers as $name => $value) {
            header("$name: $value");
        }
        echo $this->content;
    }
}