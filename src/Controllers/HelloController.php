<?php

namespace App\Controllers;

class HelloController
{
    public function index()
    {
        return '<h1>Hello World</h1>';
    }

    public function greet($name)
    {
        return "<h1>Hello, $name!</h1>";
    }
}