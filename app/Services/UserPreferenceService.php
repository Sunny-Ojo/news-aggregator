<?php

namespace App\Services;

use App\Models\UserPreference;

class UserPreferenceService
{
    /**
     * Get user preferences.
     *
     * @param int $userId
     * @return UserPreference|null
     */
   public function getUserPreferences(int $userId): UserPreference
   {
        return UserPreference::firstOrCreate(
            ['user_id' => $userId],
            ['preferences' => $this->getDefaultPreferences()]
        );
   }

    /**
     * Update or create preferences for a user.
     *
     * @param int $userId
     * @param array $data
     * @return UserPreference
     */
    public function updatePreferences(int $userId, array $data): UserPreference
    {
        return UserPreference::updateOrCreate(
            ['user_id' => $userId],
            $data
        );
    }

     /**
     * return a default preference for a user.
     *
     * @param int $userId
     * @param array $data
     * @return UserPreference
     */
    private function getDefaultPreferences(): array
    {
        return [
            'sources' => [],
            'categories' => [],
            'authors' => '[]',
        ];
    }
}
