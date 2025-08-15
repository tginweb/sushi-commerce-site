<?php

namespace Main\Entity\User;

use Main\DI\Containerable;
use Main\Entity\D7\D7Model;
use function TG\Main\Entity\User\randString;

class AppClientModel extends D7Model
{
    use Containerable;

    public $jsonFields = [
        'UF_DEBUG' => [],
    ];

    public static function tableName()
    {
        return 'app_client';
    }

    public function logout()
    {
        $this->setUserId(0);
        $this->save();
        $this->container->getAppClientService()->queueUpdatedAdd($this);
        return $this;
    }

    public function setUserId($userId)
    {
        $this['UF_USER_ID'] = $userId;
        return $this;
    }

    public function authorize($userId)
    {
        $this->setUserId($userId);
        $this->save();
        $this->container->getAppClientService()->queueUpdatedAdd($this);
        return $this;
    }

    public function getPushToken()
    {
        return $this['UF_PUSH_TOKEN'];
    }

    public function setPushToken($token)
    {
        $this['UF_PUSH_TOKEN'] = $token;
        return $this;
    }

    public function getWebPushToken()
    {
        return $this['UF_WEB_PUSH_TOKEN'];
    }

    public function setWebPushToken($token)
    {
        $this['UF_WEB_PUSH_TOKEN'] = $token;
        return $this;
    }

    public function getClientData()
    {
        return [
            'id' => intval($this->id),
            'clientId' => $this->getClientId(),
            'userId' => $this->getUserId(),
            'token' => $this->getToken(),
            'sessionId' => $this->getSessionId(),
        ];
    }

    public function getClientId()
    {
        return $this['UF_CLIENT_ID'];
    }

    public function getUserId()
    {
        return intval($this['UF_USER_ID']);
    }

    public function getToken()
    {
        return $this->ensureToken();
    }

    public function ensureToken()
    {
        if (!$this['UF_TOKEN']) {
            $this['UF_TOKEN'] = randString(100);
        }
        return $this['UF_TOKEN'];
    }

    public function getSessionId()
    {
        return $this['SESSION_ID'];
    }

    public function assignDebugParams($params)
    {
        $data = $this->getDebugParams();
        foreach ($params as $paramKey => $paramValue) {
            $data[$paramKey] = $paramValue;
        }
        $this->setDebugParams($data);
        return $this;
    }

    public function getDebugParams()
    {
        return $this['UF_DEBUG'] ?: [];
    }

    public function setDebugParams($data)
    {
        return $this['UF_DEBUG'] = $data;
    }

    public function setDebugParam($param, $paramData)
    {
        $data = $this->getDebugParams();
        $data[$param] = $paramData;
        $this->setDebugParams($data);
    }


}
