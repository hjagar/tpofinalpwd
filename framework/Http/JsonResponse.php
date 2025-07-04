<?php

namespace PhpMvc\Framework\Http;

use JsonSerializable;
use PhpMvc\Framework\Http\Constants\ContentType;
use PhpMvc\Framework\Http\Constants\HeaderType;

class JsonResponse extends Response implements JsonSerializable
{
    private array $defaultHeaders = [
        HeaderType::CONTENT_TYPE => ContentType::APP_JSON,
    ];

    public function __construct(mixed $data, int $status = HttpStatus::OK->value, array $headers = [])
    {
        $content = json_encode($data, JSON_UNESCAPED_UNICODE);
        $headers = array_merge($this->defaultHeaders, $headers);
        parent::__construct($content, $status, $headers);
    }

    public function jsonSerialize(): mixed
    {
        return [
            'content' => $this->content,
            'status' => $this->status,
            'headers' => $this->headers
        ];
    }

    /**
     * Send the response to the client.
     *
     * @return void
     */
    public function send(): void
    {
        $this->sendCodeAndHeaders();
        echo json_encode($this);
    }
}
