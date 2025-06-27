<?php

namespace PhpMvc\Framework\Security;

class SessionUser
{
    private array $attributes = [];

    public function __construct($user, $fields = [])
    {
        foreach ($fields as $field) {            
            $this->attributes[$field] = $user->$field;
        }
    }

    public function __get($name)
    {
        $returnValue = null;

        if (array_key_exists($name, $this->attributes)) {
            $returnValue = $this->attributes[$name];
        }

        return $returnValue;
    }

    public function __set($name, $value)
    {
        $this->attributes[$name] = $value;
    }
}