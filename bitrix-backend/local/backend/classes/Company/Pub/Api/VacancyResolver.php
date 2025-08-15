<?php

namespace Company\Pub\Api;

use Company\Core\Entity\Vacancy;
use Company\Core\Entity\VacancyOrderModel;
use Company\Core\Graphql\VacancyType;
use Main\Graphql\Generator\ElementResolversGenerator;
use Main\Graphql\Types;
use Main\Model\Response;

class VacancyResolver extends ElementResolversGenerator
{
    public $ns = 'company_vacancy_';
    public string $modelClass = Vacancy::class;
    public string $modelTypeClass = VacancyType::class;

    function getQueryMap()
    {
        return parent::getQueryMap() + [];
    }

    function getMutationMap()
    {
        return parent::getMutationMap() + [
                'order' => function ($mutationName, $mutationParams) {
                    return [
                        'args' => [
                            'id' => Types::int(),
                            'model' => Types::JSON(),
                        ],
                        'resolve' => [$this, 'mutateVacancyOrder']
                    ];
                },
            ];
    }

    function mutateVacancyOrder($rootValue, $args, $ctx, $info, Response $response)
    {
        $user = $this->container->getUser();
        $model = $args['model'];

        // Валидация обязательных полей
        $required = [
            'fio' => 'Имя',
            'phone' => 'Телефон',
            'vacancyName' => 'Вакансия',
        ];

        $errors = [];
        foreach ($required as $key => $label) {
            if (empty($model[$key])) {
                $errors[] = 'Поле "' . $label . '" обязательно для заполнения';
            }
        }

        if (!empty($errors)) {
            foreach ($errors as $err) {
                $response->addError($err);
            }
            return $response->getGraphqlJson();
        }

        try {
            $element = VacancyOrderModel::create([
                'NAME' => 'Отклик на вакансию: ' . $model['vacancyName'],
                'CODE' => time(),
            ]);

            $element->setPropValue('CLIENT_NAME', $model['fio']);
            $element->setPropValue('CLIENT_PHONE', $model['phone']);
            $element->setPropValue('CLIENT_EMAIL', $model['email']);
            $element->setPropValue('CLIENT_COMMENT', $model['comment'] ?? '');

            $element->save();

            $response->addSuccess('Отклик успешно отправлен');
        } catch (\Throwable $e) {
            $response->addError('Ошибка при сохранении отклика: ' . $e->getMessage());
        }
    }
}
