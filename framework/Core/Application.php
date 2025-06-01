<?php

namespace PhpMvc\Framework\Core;

use PhpMvc\Framework\Http\Kernel;
use PhpMvc\Framework\Http\Request;

class Application
{
    public const VERSION = '1.0.0';
    public const AUTHOR = 'Gonzalo Molina';
    public const NAME = 'PHP MVC Framework';

    public function __construct(private readonly Kernel $kernel) {}

    public function run()
    {
        $response = $this->kernel->handle(Request::createFromGlobals());
        $response->send();
    }
}
