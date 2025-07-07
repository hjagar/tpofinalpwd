<?php

namespace PhpMvc\Framework\Http\Middleware;

use Closure;
use PhpMvc\Framework\Http\Constants\HeaderType;
use PhpMvc\Framework\Http\HttpMethods;
use PhpMvc\Framework\Http\Request;

class CsrfMiddleware implements MiddlewareContract
{
    public function handle(Request $request, Closure $next)
    {
        if ($this->isCsrfVeriableMethod($request->method)) {
            if (!$this->hasRequestCsrfToken($request) || !$this->verifyCsfrTokenEquals($request)) {
                http_response_code(419);
                exit('CSRF token mismatch');
            }

            regenerateCsrfToken();
        }
        return $next($request);
    }

    private function isCsrfVeriableMethod(string $method): bool
    {
        return in_array($method, [HttpMethods::POST->value, HttpMethods::PUT->value, HttpMethods::DELETE->value]);
    }

    private function hasRequestCsrfToken(Request $request): bool
    {
        return $request->isPropertySet(HeaderType::CSRF_TOKEN) || $request->header(HeaderType::X_CSRF_TOKEN);
    }

    private function getRequestCsrfToken(Request $request): string
    {
        return $request->csrf_token ?? $request->header(HeaderType::X_CSRF_TOKEN) ?? '';
    }

    private function getSessionCsrfToken(): string
    {
        return $_SESSION[HeaderType::CSRF_TOKEN] ?? '';
    }

    private function verifyCsfrTokenEquals(Request $request): bool
    {
        $requestCsfrToken = $this->getRequestCsrfToken($request);
        $sessionCsftToken = $this->getSessionCsrfToken();

        return $requestCsfrToken === $sessionCsftToken;
    }
}
