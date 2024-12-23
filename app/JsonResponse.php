<?php

namespace App;

use Illuminate\Http\Response;

trait JsonResponse
{
    /**
     * Return a success response.
     *
     * @param  mixed  $data
     */
    public function successResponse($data = [], string $message = 'Operation Successful', int $statusCode = Response::HTTP_OK): \Illuminate\Http\JsonResponse
    {
        $response = [
            'success' => true,
            'message' => $message,
            'data' => $data,
        ];

        if ($data instanceof \Illuminate\Http\Resources\Json\AnonymousResourceCollection) {
            $paginationData = $data->response()->getData(true);
            $response['meta'] = $paginationData['meta'] ?? null;
            $response['links'] = $paginationData['links'] ?? null;
        }

        return response()->json($response, $statusCode);
    }

    /**
     * Return an error response.
     */
    public function errorResponse(string $message, int $code = Response::HTTP_BAD_REQUEST, array $errors = []): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'errors' => $errors,
        ], $code);
    }
}
