<?php

namespace PhpMvc\Framework\Http;

use Exception;

class Route
{
    private ?string $routeName = null;

    public function __construct(private string $regex, private array $paramNames, private $action, private string $uri) {}

    public function __get($name) {
        if(property_exists($this, $name)) {
            return $this->$name;
        }
        else {
            throw new Exception("Propiedad inexistente");
        }
    }

    public function name($routeName) {
        $this->routeName = $routeName;
    }
}
