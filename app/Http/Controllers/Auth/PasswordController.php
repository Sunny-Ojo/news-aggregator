<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Services\AuthService;

class PasswordController extends Controller
{
    public function __construct(public readonly AuthService $authService) {}

    public function sendPasswordResetLink(ForgotPasswordRequest $passwordResetRequest)
    {
        try {
            $response = $this->authService->sendPasswordResetLink($passwordResetRequest->validated());
            if ($response['success']) {
                return $this->successResponse(null, __('passwords.sent'));
            }

            return $this->errorResponse($response['errors'], 400);

        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), 400);
        }
    }

    public function resetPassword(ResetPasswordRequest $request)
    {
        try {
            $response = $this->authService->resetPassword($request->validated());

            if ($response['success']) {
                return $this->successResponse(null, __('passwords.reset'));
            }

            return $this->errorResponse($response['errors'], 400);

        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), 400);
        }
    }
}
