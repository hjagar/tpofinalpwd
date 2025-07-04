<?php

namespace PhpMvc\Framework\Http;

use JsonSerializable;

class JsonResult implements JsonSerializable
{
    private array $properties = [];
    public function __construct(private bool $success = true, private string $message = '') {}

    public function __set($name, $value)
    {
        if (property_exists($this, $name)) {
            $this->$name = $value;
        }
        else {
            $this->properties[$name] = $value;
        }
    }

    public function jsonSerialize(): mixed
    {
        return get_object_vars($this);
    }
}
