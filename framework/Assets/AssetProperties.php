<?php

namespace PhpMvc\Framework\Assets;

use stdClass;

class AssetProperties
{
    private array $properties;

    public function __construct(stdClass $properties)
    {
        foreach ($properties as $prop => $val) {
            $this->properties[$prop] = $val;
        }
    }

    public function __toString()
    {
        $properties = array_map(
            fn($key, $value) => "$key=\"$value\"",
            array_keys($this->properties),
            $this->properties
        );

        return implode(' ', $properties);
    }
}
