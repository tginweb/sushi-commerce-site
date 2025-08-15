<?php

namespace Shop\Core\Service;

use Bitrix;
use Main\Service\BaseService;
use Shop\Core\Entity\ConstructorBuild;
use const TG\Shop\Core\Service\SITE_ID;

class CatalogService extends BaseService
{
    function OnAfterIBlockElementUpdate($arFields)
    {
        /*
        if ($arFields['IBLOCK_ID'] == 37) {
            $model = ConstructorBuild::query()->withViewList()->getById($arFields['ID']);
            $model->setPropValue('SOSTAV_HASH', $model->getSostavHash());
            $model->save();
        }
        */
    }

    function getViewedProductIds($limit = 10)
    {
        $skipUserInit = false;

        $basketUserId = (int)\CSaleBasket::GetBasketUserID($skipUserInit);
        if ($basketUserId <= 0) {
            return array();
        }

        $ids = array();
        $filter = array(
            '=FUSER_ID' => $basketUserId,
            '=SITE_ID' => SITE_ID
        );

        $viewedIterator = \Bitrix\Catalog\CatalogViewedProductTable::getList(array(
            'select' => array('ELEMENT_ID'),
            'filter' => $filter,
            'order' => array('DATE_VISIT' => 'DESC'),
            'limit' => $limit
        ));

        while ($viewedProduct = $viewedIterator->fetch()) {
            $ids[] = (int)$viewedProduct['ELEMENT_ID'];
        }

        return $ids;
    }

    function addProductView($productId)
    {

        // add record
        $id = \Bitrix\Catalog\CatalogViewedProductTable::refresh(
            $productId,
            \CSaleBasket::GetBasketUserID()
        );
    }

    public function searchSuggestions($query, $arParams = [], $exFilter = [])
    {
        $arParams = [
            'MODULE_ID' => [
                'IBLOCK' => [1, 56]
            ]
        ];
        return $this->container->getSearchService()->search($query, $arParams, $exFilter);
    }

    public function search($query, $arParams = [], $exFilter = [])
    {
        $arParams = [
            'MODULE_ID' => [
                'IBLOCK' => [1]
            ]
        ];

        return $this->container->getSearchService()->search($query, $arParams, $exFilter);
    }

    function ensureConstructorElement($args)
    {

        $model = new ConstructorBuild();
        $order = [];

        if ($args['items']) {
            foreach ($args['items'] as $elementId => $elementQuantity) {
                if ($elementQuantity)
                    $order[] = [
                        'ELEMENT_ID' => $elementId,
                        'QUANTITY' => $elementQuantity,
                    ];
            }
        } else {
            foreach ($args['constructor'] as $productSectionId => $productSectionElements) {
                foreach ($productSectionElements as $elementId => $elementQuantity) {
                    if ($elementQuantity)
                        $order[] = [
                            'ELEMENT_ID' => $elementId,
                            'QUANTITY' => $elementQuantity,
                        ];
                }
            }
        }

        $model->setSostav($order);

        $element = ConstructorBuild::query()->filter(['PROPERTY_SOSTAV_HASH' => $model->getSostavHash()])->withViewList()->first();

        if (!$element) {

            $fields = [

            ];

            if ($args['sectionCode'] === 'wok') {
                $fields['IBLOCK_SECTION_ID'] = 106;
                $fields['NAME'] = 'Сборка WOK';
                // $fields['DETAIL_PICTURE'] = 15573;
                $constructorType = 'wok';
            } else {
                $fields['IBLOCK_SECTION_ID'] = 112;
                $fields['NAME'] = 'Сборка Бенто';
                // $fields['DETAIL_PICTURE'] = 15573;
                $constructorType = 'bento';
            }

            $element = ConstructorBuild::create($fields);

            $element->setSostav($order);
            $element->sostavToProps();

            $element->setPropValue('SOSTAV_HASH', $element->getSostavHash());
            $element->setPropValue('CONSTRUCTOR', $constructorType, 'XML_ID');

            $element->save();
            $element->saveCatalogProduct();

            $element = ConstructorBuild::query()->withViewList()->getById($element['ID']);

        } else {
            if ($element->getPrice() !== $element->getSostavPrice() || true) {
                $element->saveCatalogProduct();
            }
        }

        return $element;
    }

    function getProductsGifts()
    {
        $productGifts = [
            548651 => [
                'NAME' => 'Шампанское',
                'IMAGE' => ['SRC' => 'https://irkutsk.sushi-studio.ru/upload/shamp.png'],
            ],
            604 => [
                'NAME' => 'Морс',
                'IMAGE' => ['SRC' => 'https://irkutsk.sushi-studio.ru/upload/iblock/162/1200x840_Drinks2022_8.png']
            ],
            '2040001' => [
                'NAME' => 'Напиток Sushi Studio',
                'IMAGE' => ['SRC' => 'https://irkutsk.sushi-studio.ru/upload/100029280793b0.webp']
            ],
            '2040001x1' => [
                'NAME' => 'Напиток Sushi Studio',
                'IMAGE' => ['SRC' => 'https://irkutsk.sushi-studio.ru/upload/100029280793b0.webp']
            ],
            '2040001x2' => [
                'NAME' => '2 х Напитка Sushi Studio',
                'IMAGE' => ['SRC' => 'https://irkutsk.sushi-studio.ru/upload/100029280793b0.webp']
            ]
        ];
        return $productGifts;
    }
}



