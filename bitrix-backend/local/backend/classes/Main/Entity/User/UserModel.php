<?php

namespace Main\Entity\User;

use Bitrix\Main\Type\DateTime;
use Bitrix\Sale\Order;
use CUserFieldEnum;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Main\DI\Containerable;
use Main\Entity\Model\BitrixModel;
use TG\Main\Helper;

/**
 * UserQuery methods
 * @method static static getByLogin(string $login)
 * @method static static getByEmail(string $email)
 *
 * Base Query methods
 * @method static UserCollection|static[] getList()
 * @method static static first()
 * @method static static getById(int $id)
 * @method static UserQuery sort(string|array $by, string $order = 'ASC')
 * @method static UserQuery order(string|array $by, string $order = 'ASC') // same as sort()
 * @method static UserQuery filter(array $filter)
 * @method static UserQuery addFilter(array $filters)
 * @method static UserQuery resetFilter()
 * @method static UserQuery navigation(array $filter)
 * @method static UserQuery select($value)
 * @method static UserQuery keyBy(string $value)
 * @method static UserQuery limit(int $value)
 * @method static UserQuery offset(int $value)
 * @method static UserQuery page(int $num)
 * @method static UserQuery take(int $value) // same as limit()
 * @method static UserQuery forPage(int $page, int $perPage = 15)
 * @method static LengthAwarePaginator paginate(int $perPage = 15, string $pageName = 'page')
 * @method static Paginator simplePaginate(int $perPage = 15, string $pageName = 'page')
 * @method static UserQuery stopQuery()
 * @method static UserQuery cache(float|int $minutes)
 *
 * Scopes
 * @method static UserQuery active()
 * @method UserQuery fromGroup(int $groupId)
 */
class UserModel extends BitrixModel
{
    use Containerable;

    /**
     * Bitrix entity object.
     *
     * @var object
     */
    public static $bxObject;
    static $users = [];
    /**
     * Corresponding object class name.
     *
     * @var string
     */
    protected static $objectClass = 'CUser';
    /**
     * Current user cache.
     *
     * @var static
     */
    protected static $currentUser = null;
    public $cachedMethods = [];
    /**
     * Have groups been already fetched from DB?
     *
     * @var bool
     */
    protected $groupsAreFetched = false;

    /**
     * Get a new instance for the current user
     *
     * @return static
     */
    public static function current()
    {
        return is_null(static::$currentUser)
            ? static::freshCurrent()
            : static::$currentUser;
    }

    /**
     * Get a fresh instance for the current user and save it to local cache.
     *
     * @return static
     */
    public static function freshCurrent()
    {
        global $USER;

        return static::$currentUser = (new static($USER->getId()))->load();
    }

    /**
     * Load model fields from database if they are not loaded yet.
     *
     * @return $this
     */
    public function load()
    {
        $this->getFields();
        $this->getGroups();

        return $this;
    }

    /**
     * Get user groups from cache or database.
     *
     * @return array
     */
    public function getGroups()
    {
        if ($this->groupsAreFetched) {
            return $this->fields['GROUP_ID'];
        }

        return $this->refreshGroups();
    }

    /**
     * Refresh user groups and save them to a class field.
     *
     * @return array
     */
    public function refreshGroups()
    {

        if ($this->id === null) {
            return [];
        }

        global $USER;

        $this->fields['GROUP_ID'] = $this->isCurrent()
            ? $USER->getUserGroupArray()
            : static::$bxObject->getUserGroup($this->id);

        $this->fields['GROUP_ID'] = array_map('intval', $this->fields['GROUP_ID']);

        $this->groupsAreFetched = true;

        return $this->fields['GROUP_ID'];
    }

    /**
     * Check if this user is the operating user.
     */
    public function isCurrent()
    {
        global $USER;

        return $USER->getId() && $this->id == $USER->getId();
    }

    /* @return UserModel */
    static function instance($userId = null)
    {
        global $USER;

        if ($userId || $USER->IsAuthorized()) {
            $userId = $userId ?: $USER->GetID();

            if (!isset(static::$users[$userId])) {
                static::$users[$userId] = static::query()->select(['PROPS'])->getById($userId);
            }

            return static::$users[$userId];
        }
    }

    /**
     * Fill model groups if they are already known.
     * Saves DB queries.
     *
     * @param array $groups
     *
     * @return null
     */
    public function fillGroups($groups)
    {
        $this->fields['GROUP_ID'] = $groups;

        $this->groupsAreFetched = true;
    }

    /**
     * Refresh model from database and place data to $this->fields.
     *
     * @return array
     */
    public function refresh()
    {
        $this->refreshFields();

        $this->refreshGroups();

        return $this->fields;
    }

    /**
     * Refresh user fields and save them to a class field.
     *
     * @return array
     */
    public function refreshFields()
    {
        if ($this->id === null) {
            $this->original = [];
            return $this->fields = [];
        }

        $groupBackup = isset($this->fields['GROUP_ID']) ? $this->fields['GROUP_ID'] : null;

        $this->fields = static::query()->getById($this->id)->fields;

        if ($groupBackup) {
            $this->fields['GROUP_ID'] = $groupBackup;
        }

        $this->fieldsAreFetched = true;

        $this->original = $this->fields;

        return $this->fields;
    }

    /**
     * Instantiate a query object for the model.
     *
     * @return UserQuery
     */
    public static function query()
    {
        return new UserQuery(static::instantiateObject(), get_called_class());
    }

    /**
     * Check if user has role with a given ID.
     *
     * @param array $role_id
     *
     * @return bool
     */
    public function inRoles($roles = [])
    {
        return count(array_intersect($roles, $this->getRoles())) > 0;
    }

    function getRoles()
    {
        return array_merge($this->getGroups(), $this->getGroupsCode());
    }

    function getGroupsCode()
    {
        $result = [];

        $groupIds = $this->getGroups();

        $codesById = UserGroupModel::getListAllCodesById();

        foreach ($groupIds as $groupId) {
            if (!empty($codesById[$groupId])) {
                $result = array_merge($result, $codesById[$groupId]);
            }
        }

        return array_unique($result);
    }

    /**
     * Check if user is guest.
     *
     * @return bool
     */
    public function isGuest()
    {
        return !$this->isAuthorized();
    }

    /**
     * Check if user is authorized.
     *
     * @return bool
     */
    public function isAuthorized()
    {
        global $USER;

        return ($USER->getId() == $this->id) && $USER->isAuthorized();
    }

    /**
     * Logout user.
     *
     * @return void
     */
    public function logout()
    {
        global $USER;

        $USER->logout();
    }

    /**
     * Scope to get only users from a given group / groups.
     *
     * @param UserQuery $query
     * @param int|array $id
     *
     * @return UserQuery
     */
    public function scopeFromGroup($query, $id)
    {
        $query->filter['GROUPS_ID'] = $id;

        return $query;
    }

    /**
     * Substitute old group with the new one.
     *
     * @param int $old
     * @param int $new
     *
     * @return void
     */
    public function substituteGroup($old, $new)
    {
        $groups = $this->getGroups();

        if (($key = array_search($old, $groups)) !== false) {
            unset($groups[$key]);
        }

        if (!in_array($new, $groups)) {
            $groups[] = $new;
        }

        $this->fields['GROUP_ID'] = $groups;
    }

    function getNameDefault()
    {
        return 'Клиент';
    }

    function getNameFull()
    {
        return $this['NAME'];
    }

    function getNameTeaser()
    {
        $parts = [];

        if ($this->getNameFirst()) {
            $parts[] = $this->getNameFirst();
        }

        if ($this['LAST_NAME']) {
            $parts[] = mb_substr($this['LAST_NAME'], 0, 1) . '.';
        }

        return join(' ', array_filter($parts));
    }

    function getNameFirst()
    {
        return $this['NAME'];
    }

    function getEmail()
    {
        return $this['EMAIL'];
    }

    function getNameFirstLast()
    {
        return join(' ', array_filter([$this->getNameFirst(), $this['LAST_NAME']]));
    }

    function getProp($name, $field = 'VALUE', $multiple = false)
    {
        $result = null;

        $prop = 'UF_' . $name;

        switch ($field) {
            case 'VALUE':
                $result = $this->fields[$prop];
                break;
            case 'XML_ID':
                if ($enumId = $this->fields[$prop]) {
                    $enumId = (array)$enumId;

                    $result = [];
                    foreach ($enumId as $id) {
                        if ($enum = $this->getPropEnum($prop, $id))
                            $result[] = $enum['XML_ID'];
                    }
                }
                break;
        }

        if (!$multiple && is_array($result)) {
            $result = $result[0];
        }

        return $result;
    }

    function getPropEnum($name, $enumId)
    {
        static $cache = [];

        $iblockId = 'user';

        if (!isset($cache[$iblockId][$name])) {
            $cache[$iblockId][$name] = [];
            $property_enums = CUserFieldEnum::GetList(array(), array(
                "USER_FIELD_NAME" => $name,
            ));

            while ($prop = $property_enums->GetNext()) {
                $cache[$iblockId][$name][$prop['ID']] = $prop;
            }
        }

        return $cache[$iblockId][$name][$enumId];
    }

    function cachedMethod($name)
    {
        if ($result = $this->getMethodCache($name)) {
            return $result;
        }
        return $this->{$name}();
    }

    function getMethodCache($name)
    {
        return null;
    }

    function resetMethodCache($name)
    {
        return null;
    }

    function getOrdersCount()
    {
        $filter = ['USER_ID' => $this['ID']];
        $result = Order::getList(['filter' => $filter])->getSelectedRowsCount();
        return $this->setMethodCache(__METHOD__, $result, 120);
    }

    function setMethodCache($name, $result, $ttl)
    {
        return $result;
    }

    function getOrdersActiveCount()
    {
        $filter = [
            'USER_ID' => $this['ID'],
            '!CANCELED' => 'Y',
            '!STATUS_ID' => 'F',
            '>=DATE_UPDATE' => DateTime::createFromTimestamp(time() - 3600 * 24 * 10),
        ];
        $result = Order::getList(['filter' => $filter])->getSelectedRowsCount();
        return $this->setMethodCache(__METHOD__, $result, 120);
    }

    function getPromocode()
    {
        return strtoupper(substr(md5($this['ID']), 0, 6));
    }

    function getRolesByValue()
    {
        $result = [];
        foreach ($this->getRoles() as $role) {
            $result[$role] = $role;
        }
        return $result;
    }

    function isShopAdmin()
    {
        return $this->isAdmin() || in_array('SHOP_ADMIN', $this->getRoles());
    }

    /**
     * Check if user is an admin.
     */
    public function isAdmin()
    {
        return $this->hasGroupWithId(1);
    }

    /**
     * Check if user has role with a given ID.
     *
     * @param $role_id
     *
     * @return bool
     */
    public function hasGroupWithId($role_id)
    {
        return in_array($role_id, $this->getGroups());
    }

    function getAdminActions($viewmode = null, $user = null)
    {
        $result[] = [
            'icon' => 'visibility',
            'key' => 'view',
            'label' => 'Просмотр',
            'listEvent' => 'open',
            'rowRoot' => true,
            'command' => [
                'type' => 'router',
                'path' => '/admin/user/' . $this['ID']
            ],
        ];

        return $result;
    }

    function canChangeEmail($email, $canChangeWithoutConfirm = false)
    {

        if ($this->getLoginFormat() === 'email' || $this['UF_REG_BY'] === 'email') {
            return false;
        }

        $foundUser = UserModel::query()->filter([
            'LOGIC' => 'OR',
            'LOGIN' => $email,
            'EMAIL' => $email,
        ])->first();

        if ($foundUser) {
            if ($foundUser['ID'] === $this['ID']) {
                return 'used-current';
            } else {
                return 'used-another';
            }
        }

        if ($canChangeWithoutConfirm && $this->container->getUserService()->confirmEmailNeed())
            return false;

        return true;
    }

    function getLoginFormat()
    {
        if (\Main\Helper\Format::validateMobile($this['LOGIN'])) {
            return 'phone';
        } else if (\Main\Helper\Format::validateEmail($this['LOGIN'])) {
            return 'email';
        } else {
            return 'login';
        }
    }

    function canChangePhone($phone, $canChangeWithoutConfirm = false)
    {
        if ($this->getLoginFormat() === 'phone' || $this['UF_REG_BY'] === 'phone') {
            return false;
        }

        $foundUser = UserModel::query()->filter([
            'LOGIC' => 'OR',
            'LOGIN' => $phone,
            'PERSONAL_PHONE' => $phone,
        ])->first();

        if ($foundUser) {
            if ($foundUser['ID'] === $this['ID']) {
                return 'used-current';
            } else {
                return 'used-another';
            }
        }

        if ($canChangeWithoutConfirm && $this->container->getUserService()->confirmPhoneNeed())
            return false;

        return true;
    }

    function isProfileGiftUsed()
    {
        return !!$this['UF_PROFILE_GIFT_USED'];
    }

    function isProfileFilled()
    {

        if (!$this['NAME'] || preg_match('/клиент|гость/', $this['NAME']))
            return false;
        if (!$this->getEmailReal())
            return false;
        if (!$this->getPhone())
            return false;
        if (!$this['PERSONAL_BIRTHDAY'])
            return false;

        return true;

    }

    function getEmailReal()
    {
        $autoCreateDomain = $this->container->getUserService()->getAutoCreateEmailDomain();
        if ($autoCreateDomain && strpos($this['EMAIL'], $autoCreateDomain) !== false) {
            return null;
        }
        return $this['EMAIL'];
    }

    function getPhone($trimCountryCode = false)
    {
        $phone = null;

        if ($this['PERSONAL_PHONE']) {
            $phone = \Main\Helper\Format::validateMobile($this['PERSONAL_PHONE']);
        }

        if (!$phone) {
            $phone = \Main\Helper\Format::validateMobile($this['LOGIN']);
        }

        if ($phone) {
            return $trimCountryCode ? substr($phone, 1) : $phone;
        }

        return null;
    }

    function getBirthday()
    {
        return $this['PERSONAL_BIRTHDAY'] ? $this['PERSONAL_BIRTHDAY']->toString() : null;
    }

    function isBirthday()
    {
        $data = $this['PERSONAL_BIRTHDAY'];
        return $data ? $data->format('d.m.Y') === date('d.m.Y') : false;
    }

    function isBirthdayNear()
    {
        $timestamp = 0;
        $data = $this['PERSONAL_BIRTHDAY'];
        if ($data) {
            $timestamp = $data->getTimestamp();
        }
        return abs(time() - $timestamp) < 3600 * 24 * 3;
    }

    /**
     * Fill extra fields when $this->field is called.
     *
     * @return null
     */
    protected function afterFill()
    {
        if (isset($this->fields['GROUP_ID']) && is_array(['GROUP_ID'])) {
            $this->groupsAreFetched = true;
        }
    }
}
