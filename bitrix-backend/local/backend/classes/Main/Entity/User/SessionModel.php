<?php

namespace Main\Entity\User;

use Bitrix\Sale;
use Bitrix\Sale\Fuser;
use CSaleUser;
use Main\Error\AccessError;

class SessionModel
{
    static $instanceCurrent = null;
    static $instances = [];
    var $by;
    var $byValue;
    var $fuserId;
    var $userId;
    var $user;
    var $sessionId;
    var $sessionIdOld;

    function __construct($by, $byValue = null)
    {

        $this->by = $by;
        $this->byValue = $byValue;

        switch ($by) {
            case 'user':
                $this->userId = $byValue;
                $this->fuserId = Sale\Fuser::getIdByUserId($this->userId);
                break;
            case 'fuser':
                $this->fuserId = $byValue;
                break;
            case 'session':
                $this->sessionId = $byValue;
                break;
        }

        if (!$this->fuserId) {
            $this->fuserId = CSaleUser::getID(false);
        }
    }

    /**
     * @return static
     */
    static function getCurrent()
    {
        global $USER;

        if (!isset(static::$instanceCurrent)) {

            if ($userId = $USER->GetID()) {
                $session = static::getBy('user', $userId);
            } else if ($fuserId = Fuser::getId()) {
                $session = static::getBy('fuser', $fuserId);
            } else if ($sessionId = session_id()) {
                $session = static::getBy('session', $sessionId);
            }

            $session->setSessionId(session_id());

            static::updateSession($session->fuserId);

            static::$instanceCurrent = $session;
        }

        return static::$instanceCurrent;
    }

    /**
     * @return static
     */
    static function getBy($by, $byValue = null)
    {
        if (!isset(static::$instances[$by . $byValue])) {
            static::$instances[$by . $byValue] = new static($by, $byValue);
        }
        return static::$instances[$by . $byValue];
    }

    static function updateSession($fuserId)
    {
        /*
        \CSaleUser::updateSessionSaleUserID();
        if ((string)Main\Config\Option::get('sale', 'encode_fuser_id') != 'Y' && isset($_SESSION['SALE_USER_ID']))
            $_SESSION['SALE_USER_ID'] = (int)$_SESSION['SALE_USER_ID'];

        if (!isset($_SESSION['SALE_USER_ID']) || (string)$_SESSION['SALE_USER_ID'] == '' || $_SESSION['SALE_USER_ID'] === 0)
            $_SESSION['SALE_USER_ID'] = $fuserId;
        */
    }

    function authorize($id)
    {
        global $USER;

        $res = $USER->Authorize($id, true, true);

        if ($res == true) {
            return $this->enter($id);
        }
    }

    function enter($id)
    {

        $this->userId = $id;

        $this->getUser(true);

        return $this->getUser();
    }

    function getUser($reload = false)
    {
        if (!isset($this->user) || $reload) {
            if ($this->userId) {
                $this->user = UserModel::instance($this->userId);
            } else {
                $this->user = false;
            }
        }
        return $this->user;
    }

    function login($login, $pass)
    {

        global $USER;

        $res = $USER->Login($login, $pass);

        if ($res === true) {

            return $this->enter($USER->GetID());
        }

    }

    function logout()
    {

        global $USER;

        $USER->Logout();

        $this->setUserId(0);
        $this->changeSessionId();
    }

    function changeSessionId($sessionId = null)
    {
        $this->sessionIdOld = $this->getSessionId();
        $this->setSessionId($sessionId);
    }

    function getSessionId()
    {
        return $this->sessionId;
    }

    function setSessionId($sessionId = null)
    {
        $this->sessionId = $sessionId ?: session_id();
        return $this;
    }

    function getUserIdOrThrow($errorClass = AccessError::class)
    {
        $userId = $this->userId;

        if (!$userId)
            throw new $errorClass(null, 'NOT_AUTHORIZED');

        return $userId;
    }

    function getUserOrThrow($errorClass = AccessError::class)
    {
        $user = $this->getUser();

        if (!$user)
            throw new $errorClass(null, 'NOT_AUTHORIZED');

        return $user;
    }

    function getGuestCode()
    {
        return $this->getFuserId();
    }

    function getFuserId()
    {
        return $this->fuserId;
    }

    function getUserGustableIdOrThrow($errorClass = AccessError::class)
    {
        $id = $this->getUserGustableId();
        if (!$id)
            throw new $errorClass(null, 'NOT_AUTHORIZED');
        return $id;
    }

    function getUserGustableId()
    {
        return $this->getUserId() ?: -$this->getGuestId();
    }

    function getUserId()
    {
        return $this->userId;
    }

    function setUserId($userId)
    {
        $this->userId = $userId;
        $this->user = null;
        return $this;
    }

    function getGuestId()
    {
        return $this->getFuserId();
    }

    function getClientState()
    {
        return [
            'logged' => $this->isLoggedIn(),
            'sessionId' => $this->getSessionId(),
            'sessionIdOld' => $this->getSessionIdOld(),
            'userId' => $this->getUserId(),
        ];
    }

    function isLoggedIn()
    {
        global $USER;
        return $this->userId > 0;
    }

    function getSessionIdOld()
    {
        return $this->sessionIdOld;
    }

    function setData($name, $value)
    {

        $user = $this->getUser();

        if ($user && 0) {

        } else {
            if (!isset($_SESSION['SESS_DATA'])) {
                $_SESSION['SESS_DATA'] = [];
            }
            $_SESSION['SESS_DATA'][$name] = $value;
        }
    }

    function getData($name, $def = null)
    {

        $user = $this->getUser();

        if ($user && 0) {

        } else {
            return $_SESSION['SESS_DATA'][$name] ?? $def;
        }
    }
}


