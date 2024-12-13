<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Services\AuthService;
use Illuminate\Http\Response;

class RegisterController extends Controller
{
    public function __construct(public readonly AuthService $authService) {}

    public function register(RegisterRequest $registerRequest)
    {
        try {
            $data = $this->authService->register($registerRequest->validated());
            return $this->successResponse($data, __('auth.registration_success'), Response::HTTP_CREATED);
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }
}
