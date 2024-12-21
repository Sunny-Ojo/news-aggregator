<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Response;

class ApiRequestException extends Exception
{
    protected int $statusCode;

    /**
     * ApiRequestException Constructor
     */
    public function __construct(string $message = 'Something went wrong', int $statusCode = Response::HTTP_BAD_REQUEST)
    {
        parent::__construct($message);
        $this->statusCode = $statusCode;
    }

    public function render($request)
    {
        return response()->json([
            'status' => false,
            'message' => $this->getMessage(),
            'data' => null,
        ], $this->statusCode);
    }
}
