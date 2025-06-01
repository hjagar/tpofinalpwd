<?php

namespace PhpMvc\Framework\Core;

use PhpMvc\Framework\Http\Kernel;
use PhpMvc\Framework\Http\Router;


class ApplicationConfigure
{
    public static function configureApplication(Router $router): Application
    {
        $kernel = new Kernel($router);
        return new Application($kernel);
    }
}
