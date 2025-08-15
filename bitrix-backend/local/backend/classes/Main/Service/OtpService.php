<?php

namespace Main\Service;

use Main\Entity\Otp\OtpModel;
use Main\Error\UserError;
use function TG\Main\Service\ConvertTimeStamp;

class OtpService extends BaseService
{
    /**
     * @return OtpModel
     */
    function createAction($params, $cls = OtpModel::class)
    {
        $sess = $this->container->getSession();

        $actionProvider = $params['PROVIDER'];
        $actionName = $params['NAME'];
        $actionValue = $params['VALUE'];
        $actionData = $params['DATA'];
        $actionId = $params['SID'];
        $actionTtl = $params['TTL'] ?? 30 * 60;
        $actionFake = $params['FAKE'];
        $allowClientId = $params['ALLOW_CLIENT_ID'];

        $actionModel = new $cls(null, [
            'UF_PROVIDER' => $actionProvider,
            'UF_NAME' => $actionName,
            'UF_DATA' => $actionData,
            'UF_VALUE' => $actionValue,
            'UF_CLIENT_ID' => $this->container->getAppClientService()->getClientId(),
            'UF_SESSION_ID' => $sess->getSessionId(),
            'UF_USER_ID' => $sess->getUserId(),
            'UF_ATTEMPTS' => 10,
            'UF_EXPIRES' => ConvertTimeStamp(time() + $actionTtl, "FULL"),
            'UF_CONFIRMED' => 0,
            //'UF_FAKE' => $actionFake ? 1 : 0
        ]);

        return $actionModel;
    }

    function getStatusList()
    {
        return ['sa_wrong_code', 'sa_attempts_limit', 'sa_invalid'];
    }

    function loadActionAndCheck($params, $code)
    {
        $actions = $this->loadActions($params);

        if ($actions->count()) {
            /** @var OtpModel $action */
            foreach ($actions as $action) {
                if ($action->checkCode($code)) {
                    $validAction = $action;
                } else {
                    $action->hitAttempt();
                    $action->save();
                }
            }
            if (!$validAction) {
                /** @var OtpModel $action */
                $action = $actions->first();
                if (!$action->haveAttempts()) {
                    return new UserError('Количество попыток ввода исчерпано. Запросите код заново', 'SA_ATTEMPTS_LIMIT');
                } else if (!$action->checkCode($code)) {
                    $action->hitAttempt();
                    $action->save();
                    return new UserError('Неправильный код', 'SA_WRONG_CODE');
                }
            } else {
                return $validAction;
            }
        }
        return new UserError('Ошибка. Попробуйте запросить код заново', 'SA_INVALID');
    }

    function loadActions($params)
    {
        $query = $this->getActualActionsQuery($params);
        return $query->getList();
    }

    function getActualActionsQuery($params)
    {
        $sess = $this->container->getSession();

        $userId = $sess->getUserId();
        $sessionId = $sess->getSessionId();

        $actionName = $params['NAME'];
        $actionData = $params['DATA'];
        $actionValue = $params['VALUE'];
        $actionId = $params['SID'];

        $query = OtpModel::query();

        $filter = [];

        if ($actionId) {
            $filter['UF_SECURE_ID'] = $actionId;
        } else {
            $filter['UF_NAME'] = $actionName;
            if ($actionData) {
                $filter['UF_DATA_HASH'] = md5(json_encode($actionData));
            } else if ($actionValue) {
                $filter['UF_VALUE'] = $actionValue;
            }
        }

        if ($userId) {
            $filter[] = [
                'LOGIC' => 'OR',
                'UF_USER_ID' => $userId,
                [
                    'UF_USER_ID' => ''
                ]
            ];
        } else if ($sessionId) {
            //$filter['UF_SESSION_ID'] = $sessionId;
        }

        $filter['>UF_EXPIRES'] = ConvertTimeStamp(time(), "FULL");

        $query = $query->filter($filter);
        $query->sort('ID', 'DESC');
        return $query;
    }

    function loadAction($params)
    {
        $query = $this->getActualActionsQuery($params);
        return $query->first();
    }
}
