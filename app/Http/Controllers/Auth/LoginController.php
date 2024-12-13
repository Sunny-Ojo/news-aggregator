<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Services\AuthService;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function __construct(public readonly AuthService $authService) {}

    public function login(LoginRequest $loginRequest)
    {
        try {
            $data = $this->authService->login($loginRequest->validated());
            return $this->successResponse($data, __('auth.login_success'), Response::HTTP_OK);
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }

    public function logout()
    {
     try {
        $this->authService->logout(Auth::user());
        return $this->successResponse(__('auth.logout_success'));
     } catch (\Throwable $th) {
      return $this->errorResponse($th->getMessage(), Response::HTTP_BAD_REQUEST);
     }
    }
}
