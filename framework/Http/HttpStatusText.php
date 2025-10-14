<?php
namespace PhpMvc\Framework\Http;

enum HttpStatusText: string
{
    case OK = 'Ok';
    case CREATED = 'Creado';
    case ACCEPTED = 'Aceptado';
    case NO_CONTENT = 'Sin contenido';

    case MOVED_PERMANENTLY = 'Movido permanentemente';
    case FOUND = 'Encontrado';
    case NOT_MODIFIED = 'No modificado';

    case BAD_REQUEST = 'Solicitud incorrecta';
    case UNAUTHORIZED = 'No autorizado';
    case FORBIDDEN = 'Prohibido';
    case NOT_FOUND = 'No encontrado';
    case METHOD_NOT_ALLOWED = 'Método no permitido';
    case NOT_ACCEPTABLE = 'No aceptable';
    case PROXY_AUTHENTICATION_REQUIRED = 'Autenticación de proxy requerida';
    case REQUEST_TIMEOUT = 'Tiempo de espera agotado';
    case CONFLICT = 'Conflicto';
    case GONE = 'Eliminado';
    case LENGTH_REQUIRED = 'Longitud requerida';
    case PRECONDITION_FAILED = 'Precondición fallida';
    case PAYLOAD_TOO_LARGE = 'Payload demasiado grande';
    case URI_TOO_LONG = 'URI demasiado larga';
    case UNSUPPORTED_MEDIA_TYPE = 'Tipo de medio no soportado';
    case RANGE_NOT_SATISFIABLE = 'Rango no satisfacible';
    case EXPECTATION_FAILED = 'Expectativa fallida';
    case IM_A_TEAPOT = 'Soy una tetera';
    case MISDIRECTED_REQUEST = 'Solicitud mal dirigida';
    case UNPROCESSABLE_ENTITY = 'Entidad no procesable';
    case LOCKED = 'Bloqueado';
    case FAILED_DEPENDENCY = 'Dependencia fallida';
    case TOO_EARLY = 'Demasiado pronto';
    case UPGRADE_REQUIRED = 'Actualización requerida';
    case PRECONDITION_REQUIRED = 'Precondición requerida';
    case TOO_MANY_REQUESTS = 'Demasiadas solicitudes';
    case REQUEST_HEADER_FIELDS_TOO_LARGE = 'Campos de encabezado de solicitud demasiado grandes';
    case UNAVAILABLE_FOR_LEGAL_REASONS = 'No disponible por razones legales';

    case INTERNAL_SERVER_ERROR = 'Error interno del servidor';
    case NOT_IMPLEMENTED = 'No implementado';
    case BAD_GATEWAY = 'Puerta de enlace incorrecta';
    case SERVICE_UNAVAILABLE = 'Servicio no disponible';
    case GATEWAY_TIMEOUT = 'Tiempo de espera de puerta de enlace';
    case HTTP_VERSION_NOT_SUPPORTED = 'Versión HTTP no soportada';
    case VARIANT_ALSO_NEGOTIATES = 'Variante también negocia';
    case INSUFFICIENT_STORAGE = 'Almacenamiento insuficiente';
    case LOOP_DETECTED = 'Bucle detectado';
    case NOT_EXTENDED = 'No extendido';
    case NETWORK_AUTHENTICATION_REQUIRED = 'Autenticación de red requerida';

    public static function getStatusText(HttpStatus $status): string
    {
        foreach (self::cases() as $case) {
            if ($case->name === $status->name) {
                return $case->value;
            }
        }

        throw new \ValueError("Invalid HttpStatus: {$status->name}");
    }
}