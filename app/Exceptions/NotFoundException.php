<?php

namespace App\Exceptions;

use App\Helpers\HttpCode;
use Exception;

class NotFoundException extends Exception
{
    public $errors;
    public $httpCode;

    public function __construct(string $message, $errors = [], $httpCode = HttpCode::NOT_FOUND)
    {
        $this->message = $message;
        $this->errors = $errors;
        $this->httpCode = $httpCode;
    }

    public function report()
    {
        return false;
    }

     public function render($request)
    {
        return response()->json([
            "message" => $this->message,
             "errors" => $this->errors
            ],
            $this->httpCode
        );
    }
}
