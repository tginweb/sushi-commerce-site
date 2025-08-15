<?php

namespace Main\Service;

use CFile;
use Main\Entity\User\UserAvatarElementModel;

class AvatarService extends BaseService
{
    function getCurrentUserAvatar()
    {
        $user = $this->container->getUser();

        return $user ? $this->getUserAvatar($user) : null;
    }

    function getUserAvatar($user)
    {
        return [
            'ELEMENT_ID' => $user['UF_AVATAR_ID'],
            'IMAGE' => $this->getUserAvatarImage($user)
        ];
    }

    function getUserAvatarImage($user)
    {

        $pictureId = null;
        $pictureModel = null;

        if ($user['PERSONAL_PHOTO']) {

            $pictureId = $user['PERSONAL_PHOTO'];

        } else if ($user['UF_AVATAR_ID']) {

            if ($avatarModel = UserAvatarElementModel::getById($user['UF_AVATAR_ID'])) {
                $pictureId = $avatarModel['DETAIL_PICTURE'];
            }
        }

        if ($pictureId) {
            $pictureModel = CFile::GetFileArray($pictureId);
        }

        if (!$pictureModel) {
            $avatarModel = UserAvatarElementModel::query()->first();
            $pictureId = $avatarModel['DETAIL_PICTURE'];
            $pictureModel = CFile::GetFileArray($pictureId);
        }

        return $pictureModel;
    }
}



