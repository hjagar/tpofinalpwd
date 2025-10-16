<?php

namespace PhpMvc\Framework\Http;

use PhpMvc\Framework\Http\HttpStatus;

class ResponseError extends Response
{
    public function __construct(int $statusCode, ?string $message = null)
    {
        $content = $this->getErrorContent($statusCode, $message);
        parent::__construct($content, $statusCode);
    }

    private function getErrorContent(int $statusCode, ?string $message = null): ?string
    {
        // Construye la ruta al archivo de error (ej: 404.html, 500.html)
        $errorFile = __DIR__ . '/Middleware/Errors/' . $statusCode . '.html';

        if (file_exists($errorFile)) {
            $errorContent = file_get_contents($errorFile);

            if($message === null) {
                $message = HttpStatusText::getStatusText(HttpStatus::from($statusCode));
            }

            if($message !== null) {
                $errorContent = str_replace('{{ message }}', $message, $errorContent);
            }

            return $errorContent;
        }

        // Contenido de respaldo si no se encuentra un archivo específico
        return "<h1>Error {$statusCode}</h1>";
    }

    public static function notFound(): ResponseError
    {
        return new ResponseError(HttpStatus::NOT_FOUND->value);
    }

    public static function internalServerError(): ResponseError
    {
        return new ResponseError(HttpStatus::INTERNAL_SERVER_ERROR->value);
    }

    public static function forbidden(): ResponseError
    {
        return new ResponseError(HttpStatus::FORBIDDEN->value);
    }
    
    // Additional static methods for other HTTP error status codes
    public static function badRequest(): ResponseError
    {
        return new ResponseError(HttpStatus::BAD_REQUEST->value);
    }

    public static function unauthorized(): ResponseError
    {
        return new ResponseError(HttpStatus::UNAUTHORIZED->value);
    }

    public static function methodNotAllowed(): ResponseError
    {
        return new ResponseError(HttpStatus::METHOD_NOT_ALLOWED->value);
    }

    public static function notAcceptable(): ResponseError
    {
        return new ResponseError(HttpStatus::NOT_ACCEPTABLE->value);
    }

    public static function conflict(): ResponseError
    {
        return new ResponseError(HttpStatus::CONFLICT->value);
    }

    public static function gone(): ResponseError
    {
        return new ResponseError(HttpStatus::GONE->value);
    }

    public static function lengthRequired(): ResponseError
    {
        return new ResponseError(HttpStatus::LENGTH_REQUIRED->value);
    }

    public static function preconditionFailed(): ResponseError
    {
        return new ResponseError(HttpStatus::PRECONDITION_FAILED->value);
    }

    public static function payloadTooLarge(): ResponseError
    {
        return new ResponseError(HttpStatus::PAYLOAD_TOO_LARGE->value);
    }

    public static function uriTooLong(): ResponseError
    {
        return new ResponseError(HttpStatus::URI_TOO_LONG->value);
    }

    public static function unsupportedMediaType(): ResponseError
    {
        return new ResponseError(HttpStatus::UNSUPPORTED_MEDIA_TYPE->value);
    }

    public static function rangeNotSatisfiable(): ResponseError
    {
        return new ResponseError(HttpStatus::RANGE_NOT_SATISFIABLE->value);
    }

    public static function expectationFailed(): ResponseError
    {
        return new ResponseError(HttpStatus::EXPECTATION_FAILED->value);
    }

    public static function imATeapot(): ResponseError
    {
        return new ResponseError(HttpStatus::IM_A_TEAPOT->value);
    }

    public static function misdirectedRequest(): ResponseError
    {
        return new ResponseError(HttpStatus::MISDIRECTED_REQUEST->value);
    }

    public static function unprocessableEntity(): ResponseError
    {
        return new ResponseError(HttpStatus::UNPROCESSABLE_ENTITY->value);
    }

    public static function locked(): ResponseError
    {
        return new ResponseError(HttpStatus::LOCKED->value);
    }

    public static function failedDependency(): ResponseError
    {
        return new ResponseError(HttpStatus::FAILED_DEPENDENCY->value);
    }

    public static function tooEarly(): ResponseError
    {
        return new ResponseError(HttpStatus::TOO_EARLY->value);
    }

    public static function upgradeRequired(): ResponseError
    {
        return new ResponseError(HttpStatus::UPGRADE_REQUIRED->value);
    }

    public static function preconditionRequired(): ResponseError
    {
        return new ResponseError(HttpStatus::PRECONDITION_REQUIRED->value);
    }

    public static function tooManyRequests(): ResponseError
    {
        return new ResponseError(HttpStatus::TOO_MANY_REQUESTS->value);
    }

    public static function requestHeaderFieldsTooLarge(): ResponseError
    {
        return new ResponseError(HttpStatus::REQUEST_HEADER_FIELDS_TOO_LARGE->value);
    }

    public static function unavailableForLegalReasons(): ResponseError
    {
        return new ResponseError(HttpStatus::UNAVAILABLE_FOR_LEGAL_REASONS->value);
    }

    public static function serviceUnavailable(): ResponseError
    {
        return new ResponseError(HttpStatus::SERVICE_UNAVAILABLE->value);
    }

    public static function gatewayTimeout(): ResponseError
    {
        return new ResponseError(HttpStatus::GATEWAY_TIMEOUT->value);
    }

    public static function httpVersionNotSupported(): ResponseError
    {
        return new ResponseError(HttpStatus::HTTP_VERSION_NOT_SUPPORTED->value);
    }

    public static function variantAlsoNegotiates(): ResponseError
    {
        return new ResponseError(HttpStatus::VARIANT_ALSO_NEGOTIATES->value);
    }

    public static function insufficientStorage(): ResponseError
    {
        return new ResponseError(HttpStatus::INSUFFICIENT_STORAGE->value);
    }

    public static function loopDetected(): ResponseError
    {
        return new ResponseError(HttpStatus::LOOP_DETECTED->value);
    }

    public static function notExtended(): ResponseError
    {
        return new ResponseError(HttpStatus::NOT_EXTENDED->value);
    }

    public static function networkAuthenticationRequired(): ResponseError
    {
        return new ResponseError(HttpStatus::NETWORK_AUTHENTICATION_REQUIRED->value);
    }
}