<?php
namespace App\Helpers;

class HttpCode {
    public const OK = 200;
    public const CREATED = 201;
    public const NO_CONTENT = 204;
    public const BAD_REQUEST = 400;
    public const UN_AUTHORIZED = 401;
    public const FORBIDDEN = 403;
    public const TOO_MANY_REQUEST = 429;
    public const UNPROCESABLE_CONTENT = 422;
    public const INTERNAL_SERVER_ERROR = 500;
}
