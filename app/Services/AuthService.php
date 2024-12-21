<?php

namespace App\Services;

use App\Exceptions\ApiRequestException;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class AuthService
{
    public function register(array $data): array
    {
        $user = User::create([
            'name' => trim($data['name']),
            'email' => trim($data['email']),
            'password' => Hash::make($data['password']),
        ]);

        $token = $user->createToken('API_TOKEN')->plainTextToken;

        return [
            'user' => UserResource::make($user),
            'token' => $token,
        ];
    }

    public function login(array $credentials): array
    {
        if (! Auth::attempt($credentials)) {
            throw new ApiRequestException('Invalid credentials', 400);
        }

        $user = Auth::user();
        $token = $user->createToken('API_TOKEN')->plainTextToken;

        return [
            'user' => UserResource::make($user),
            'token' => $token,
        ];
    }

    public function logout(User $user): void
    {
        $user->tokens()->delete();
    }

    public function sendPasswordResetLink(array $data): array
    {
        $status = Password::sendResetLink(
            ['email' => $data['email']]
        );

        return $status === Password::RESET_LINK_SENT
            ? ['success' => true, 'message' => __($status)]
            : ['success' => false, 'errors' => __($status)];
    }

    public function resetPassword(array $data): array
    {
        $status = Password::reset(
            $data,
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        return $status === Password::PASSWORD_RESET
            ? ['success' => true, 'message' => __($status)]
            : ['success' => false, 'errors' => __($status)];
    }
}
