<?php

namespace Shop\Core\Service;

use Bitrix;
use Main\Service\BaseService;
use Shop\Core\Entity\OrderProfile;

class OrderProfileService extends BaseService
{
    public $profilesCached = [];
    public $profileCached = [];

    function getProfilesByUser($userId)
    {
        if (!$userId)
            $userId = $this->container->getUserGustableIdOrThrow();
        $query = OrderProfile::query();
        $query->filter([
            'USER_ID' => $userId,
        ]);
        return $query->getList()->withValues()->pluck(null, 'ID');
    }

    function getProfilesByUserCached($userId = null, $refetch = false)
    {
        if (!$userId)
            $userId = $this->container->getUserGustableIdOrThrow();
        if (!isset($this->profilesCached[$userId]) || $refetch) {
            $profiles = $this->getProfilesByUser($userId);
            foreach ($profiles as $profile) {
                $this->profileCached[$profile['ID']] = $profile;
            }
            $this->profilesCached[$userId] = $profiles;
        }
        return $this->profilesCached[$userId];
    }

    /**
     * @return OrderProfile
     */
    function getProfileByUser($userId, $profileId)
    {
        if (!$profileId)
            return false;
        if (!$userId)
            $userId = $this->container->getUserGustableIdOrThrow();
        $query = OrderProfile::query();
        $query->filter([
            'USER_ID' => $userId,
            'ID' => $profileId,
        ]);
        return $query->getList()->withValues()->first();
    }

    /**
     * @return OrderProfile
     */
    function getProfileByUserCached($userId, $profileId, $refetch = false)
    {
        if (!$userId)
            $userId = $this->container->getUserGustableIdOrThrow();
        if (!isset($this->profileCached[$profileId]) || $refetch) {
            $this->profileCached[$profileId] = $this->getProfileByUser($userId, $profileId);
        }
        return $this->profileCached[$profileId];
    }

    function unsetUserProfilesDefault($userId = null)
    {
        $profiles = $this->getProfilesByUserCached();
        foreach ($profiles as $profile) {
            $profile->updateValues([
                'PROFILE_DEFAULT' => 'N'
            ]);
        }
    }
}



