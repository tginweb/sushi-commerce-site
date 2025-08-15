<?php

namespace Main\Graphql\Type\User;

use CFile;
use Main\Entity\User\UserFamily;
use Main\Entity\User\UserGroupModel;
use Main\Graphql\Type\Entity\EntityPropType;
use Main\Graphql\Type\Entity\EntityType;
use Main\Graphql\Type\Image\ImageType;
use Main\Graphql\Types;

class UserType extends EntityType
{
    const NAME = 'User';

    public function getFieldsInfo()
    {
        return parent::getFieldsInfo() + [
                'ID' => Types::int(),
                'SESSION_ID' => Types::string(),
                'GROUPS_INFO' => Types::listOf(Types::get(UserGroupType::class)),
                'ROLES' => Types::JSON(),
                'LOGIN' => Types::string(),
                'LOGIN_FORMAT' => Types::string(),
                'EMAIL' => Types::string(),
                'NAME_FULL' => Types::string(),
                'NAME_TEASER' => Types::string(),
                'NAME' => Types::string(),
                'LAST_NAME' => Types::string(),
                'SECOND_NAME' => Types::string(),
                'PHONE' => Types::string(),
                'PHONE_FORMATTED' => Types::string(),
                'PERSONAL_PHOTO' => Types::get(ImageType::class),
                'PERSONAL_BIRTHDAY' => Types::string(),
                'PERSON_TYPE_ID' => Types::int(),
                'PROPS' => Types::listOf(Types::get(EntityPropType::class)),
                'PROMOCODE' => Types::string(),
                'AVATAR' => Types::get(UserAvatarType::class),

                'GREETING_NAME' => Types::string(),

                'FAMILY' => Types::listOf(Types::get(UserFamilyType::class)),

                'PROFILE_FILLED' => Types::boolean(),
                'PROFILE_GIFT_USED' => Types::boolean()
            ];
    }

    public function resolve_PROFILE_GIFT_USED($element, $args, $context)
    {
        return $element->isProfileGiftUsed();
    }

    public function resolve_PROFILE_FILLED($element, $args, $context)
    {
        return $element->isProfileFilled();
    }

    public function resolve_FAMILY($element, $args, $context)
    {
        return UserFamily::query()->filter(['UF_USER_ID' => $element['ID']])->getList();
    }

    public function resolve_GREETING_NAME($element, $args, $context)
    {
        return $element['NAME'] ?: 'Гость';
    }

    public function resolve_SESSION_ID($element, $args, $context)
    {
        return session_id();
    }

    public function resolve_PROPS($element, $args, $context)
    {
        return [
            ['CODE' => 'ORDER_PROFILE_ID', 'VAL' => $element['UF_ORDER_PROFILE_ID']],
            ['CODE' => 'PROFILE_GIFT_USED', 'VAL' => $element['UF_PROFILE_GIFT_USED']],
        ];
    }

    public function resolve_AVATAR($element, $args, $context)
    {
        return $this->container->getUserAvatarService()->getUserAvatar($element);
    }


    public function resolve_ROLES($element, $args, $context)
    {
        return $element->getRoles();
    }

    public function resolve_GROUPS_INFO($element, $args, $context)
    {
        $userGroupQuery = UserGroupModel::query();

        $userGroups = $userGroupQuery->getList();

        $result = [];

        foreach ($element->getGroups() as $id) {

            //if (in_array($id, [2, 3, 4])) continue;

            $group = $userGroups[$id];
            $result[] = $group;
        }

        return $result;
    }

    public function resolve_LOGIN($element, $args, $context)
    {
        return $element['LOGIN'];
    }

    public function resolve_LOGIN_FORMAT($element, $args, $context)
    {
        return $element->getLoginFormat();
    }

    public function resolve_EMAIL($element, $args, $context)
    {
        return $element->getEmailReal();
    }

    public function resolve_NAME($element, $args, $context)
    {
        return $element->getNameFull();
    }

    public function resolve_NAME_FULL($element, $args, $context)
    {
        return $element->getNameFull();
    }

    public function resolve_NAME_TEASER($element, $args, $context)
    {
        return $element->getNameTeaser();
    }

    public function resolve_PHONE($element, $args, $context)
    {
        return $element->getPhone(true);
    }

    public function resolve_PHONE_FORMATTED($element, $args, $context)
    {
        $phone = $element->getPhone();
        if (preg_match('/^\d(\d{3})(\d{3})(\d{4})$/', $phone, $matches)) {
            return '+7 (' . $matches[1] . ') ' . $matches[2] . '-' . $matches[3];
        }
        return $phone;
    }

    public function resolve_PERSONAL_PHOTO($element, $args, $context)
    {
        return !empty($element['PERSONAL_PHOTO']) ? CFile::GetFileArray($element['PERSONAL_PHOTO']) : null;
    }

    public function resolve_PERSONAL_BIRTHDAY($parent, $args, $context)
    {
        return $parent['PERSONAL_BIRTHDAY'];
    }

    public function resolve_ORDERS_COUNT($parent, $args, $context)
    {
        return $parent->cachedMethod('getOrdersCount');
    }

    public function resolve_ORDERS_ACTIVE_COUNT($parent, $args, $context)
    {
        return $parent->cachedMethod('getOrdersActiveCount');
    }

    public function resolve_PROMOCODE($parent, $args, $context)
    {
        return $parent->getPromocode();
    }
}
