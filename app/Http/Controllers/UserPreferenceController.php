<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserPreferenceRequest;
use App\Http\Resources\UserPreferenceResource;
use App\Services\UserPreferenceService;
use Illuminate\Support\Facades\Auth;

class UserPreferenceController extends Controller
{
    public function __construct(private readonly UserPreferenceService $userPreferenceService) {}

    public function show()
    {
       try {
        $preferences = $this->userPreferenceService->getUserPreferences(Auth::id());
        return $this->successResponse(UserPreferenceResource::make($preferences));

       } catch (\Throwable $th) {
        return $this->errorResponse($th->getMessage());
       }
    }

    public function update(UserPreferenceRequest $request)
    {
       try {
        $preferences = $this->userPreferenceService->updatePreferences(Auth::id(), $request->validated());
        return $this->successResponse(UserPreferenceResource::make($preferences));
       } catch (\Throwable $th) {
        return $this->errorResponse($th->getMessage());
       }
    }

}
