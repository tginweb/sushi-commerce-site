<?php

namespace Shop\Core\Entity\OrderProperty;

use Shop\Core\Entity\OrderProperty;
use Shop\Core\Entity\VorderCurrent;

class ProfileId extends OrderProperty
{
    public function vorderUpdateFromClient(VorderCurrent $vorder, $value, $valueChanged = null, &$inputAttrsByType = [], $op = null)
    {
        if ($op === 'profile-reload') {
            $vorder->checkUserProfile($value, true);
        } else if ($value != $vorder->getProfileId()) {
            if ($value !== 'custom') {
                $vorder->checkUserProfile($value);
            } else {
                $vorder->getOrder()->resetProfileProps();
                $vorder->setProfileId($value);
            }
        }
    }
}


