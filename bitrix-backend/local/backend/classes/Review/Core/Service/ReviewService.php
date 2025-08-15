<?php

namespace Review\Core\Service;

use Bitrix;
use Main\Entity\IBlock\ElementModel;
use Main\Service\BaseService;

class ReviewService extends BaseService
{
    function OnBeforeIBlockElementDelete($elementId)
    {
        /*
        $rsElements = \CIBlockElement::GetList(
            [],
            [
                'ID' => $elementId
            ],
            false,
            false,
            [
                'ID',
                'IBLOCK_ID',
            ]
        );

        $arElement = $rsElements->Fetch();

        if (!$arElement)
            return;

        $iblockIds = ElementModel::iblockId();

        if (!in_array($arElement['IBLOCK_ID'], $iblockIds)) return;

        $element = ElementModel::query()->withViewPublic()->getById($elementId);

        if ($element && $element['PROPERTY_ELEMENT_ID_VALUE']) {
            $this->recalcProductRating($element['PROPERTY_ELEMENT_ID_VALUE']);
        }
        */
    }

    function OnAfterIBlockElementUpdate(&$arFields)
    {
        /*
        $iblockIds = ElementModel::iblockId();

        if (in_array($arFields['IBLOCK_ID'], $iblockIds)) {
            $element = ElementModel::query()->withViewPublic()->getById($arFields['ID']);
            if ($element && $element['PROPERTY_ELEMENT_ID_VALUE']) {
                $this->recalcProductRating($element['PROPERTY_ELEMENT_ID_VALUE']);
            }
        } else {

            //$this->recalcProductRating($arFields['ID']);
        }
        */
    }

    function recalcProductsRating()
    {

    }

    function recalcProductRating($productId)
    {
        $rsProducts = \CIBlockElement::GetList(
            [],
            [
                'ID' => $productId
            ],
            false,
            false,
            [
                'ID',
                'PROPERTY_RATING',
                'NAME'
            ]
        );

        $product = $rsProducts->Fetch();

        if (!$product) return;

        $res = \CIBlockElement::GetList(
            [],
            [
                "IBLOCK_ID" => ElementModel::iblockId(),
                "ACTIVE" => "Y",
                'PROPERTY_ELEMENT_ID' => $product['ID']
            ],
            false,
            false,
            [
                'ID',
                'PROPERTY_RATING'
            ]
        );

        $ratingSumm = 0;
        $ratingCount = 0;

        while ($review = $res->Fetch()) {

            if (($rating = $review['PROPERTY_RATING_VALUE']) && ($rating > 0)) {
                $ratingSumm += $rating;
                $ratingCount++;
            }

        }

        if ($ratingCount > 0) {
            $score = $ratingSumm / $ratingCount;
        } else {
            $score = 0;
        }

        \CIBlockElement::SetPropertyValuesEx($product['ID'], $product['IBLOCK_ID'], [
            'RATING' => $score,
            'RATING_VOTES' => $ratingCount
        ]);
    }

}



