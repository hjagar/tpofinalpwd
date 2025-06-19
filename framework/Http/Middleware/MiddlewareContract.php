<?php

namespace PhpMvc\Framework\Http\Middleware;

use Closure;
use PhpMvc\Framework\Http\Request;

interface MiddlewareContract
{
    public function handle(Request $request, Closure $next);
}
