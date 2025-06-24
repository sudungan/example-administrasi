<?php

namespace App\Exceptions;

use Exception;
use App\Helpers\HttpCode;

class ForbiddenTransactionException extends Exception
{
    protected $errors;
    protected $httpCode;

    public function __construct(string $message,  $errors = array(), $httpCode = HttpCode::FORBIDDEN)
    {
        $this->message = $message;
        $this->errors = $errors;
        $this->httpCode = $httpCode;
    }

    /**
     * Report the exception.
     *
     * @return bool|null
     */
    public function report()
    {
        return false;
    }

    /**
     * Render the exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function render($request)
    {
        return response()->json(["message" => $this->message, "errors" => $this->errors], $this->httpCode);
    }
}

