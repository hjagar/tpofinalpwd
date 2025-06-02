<?php
namespace PhpMvc\Framework\Http;

enum HttpMethods: string
{
    case GET = 'GET';
    case POST = 'POST';
    case PUT = 'PUT';
    case DELETE = 'DELETE';
    case PATCH = 'PATCH';
    case HEAD = 'HEAD';
    case OPTIONS = 'OPTIONS';

    public function isSafe(): bool
    {
        return in_array($this, [self::GET, self::HEAD, self::OPTIONS]);
    }

    public function isIdempotent(): bool
    {
        return in_array($this, [self::GET, self::PUT, self::DELETE, self::OPTIONS]);
    }
}