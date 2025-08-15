<?php

namespace Shop\Core\Helper;

use Bitrix\Main\Loader;
use CCatalogSku;
use CIBlockElement;
use CIBlockPriceTools;
use CModule;
use function TG\Shop\Core\Helper\htmlspecialcharsbx;

class Price
{
    public static function getPriceTypes($arPriceCode = [], $byUser = true)
    {
        global $USER;

        $arCatalogPrices = array();

        Loader::includeModule('catalog');

        $arCatalogGroupCodesFilter = array();

        foreach (array_filter($arPriceCode) as $value) {
            $arCatalogGroupCodesFilter[$value] = true;
        }

        $arCatalogGroupsFilter = array();
        $arCatalogGroups = \CCatalogGroup::GetListArray();

        foreach ($arCatalogGroups as $key => $value) {
            if (
                empty($arPriceCode) ||
                isset($arCatalogGroupCodesFilter[$value['NAME']])
                || isset($arCatalogGroupCodesFilter[$value['ID']])
                || isset($arCatalogGroupCodesFilter[$value['CODE']])
            ) {
                $arCatalogGroupsFilter[] = $key;
                $arCatalogPrices[$value["NAME"]] = array(
                    "ID" => (int)$value["ID"],
                    "CODE" => $value["NAME"],
                    "SORT" => (int)$value["SORT"],
                    "BASE" => $value["BASE"],
                    "XML_ID" => $value["XML_ID"],
                    "TITLE" => htmlspecialcharsbx($value["NAME_LANG"]),
                    "~TITLE" => $value["NAME_LANG"],
                    "SELECT" => "CATALOG_GROUP_" . $value["ID"],
                    "SELECT_EXTENDED" => array("PRICE_" . $value["ID"], "CURRENCY_" . $value["ID"], "SCALED_PRICE_" . $value["ID"])
                );
            }
        }

        $userGroups = array(2);

        if (isset($USER) && $USER instanceof \CUser)
            $userGroups = $USER->GetUserGroupArray();

        $arPriceGroups = \CCatalogGroup::GetGroupsPerms($userGroups, $arCatalogGroupsFilter);

        foreach ($arCatalogPrices as $name => $value) {
            $arCatalogPrices[$name]["CAN_VIEW"] = in_array($value["ID"], $arPriceGroups["view"]);
            $arCatalogPrices[$name]["CAN_BUY"] = in_array($value["ID"], $arPriceGroups["buy"]);
        }

        return $arCatalogPrices;
    }

    public static function getProductPrice($item_id, $sale_currency = 'RUB')
    {
        CModule::IncludeModule("iblock");
        CModule::IncludeModule("catalog");
        CModule::IncludeModule("sale");
        global $USER;

        $currency_code = 'RUB';

        // Проверяем, имеет ли товар торговые предложения?
        if (CCatalogSku::IsExistOffers($item_id)) {

            // Пытаемся найти цену среди торговых предложений
            $res = CIBlockElement::GetByID($item_id);

            if ($ar_res = $res->GetNext()) {

                if (isset($ar_res['IBLOCK_ID']) && $ar_res['IBLOCK_ID']) {

                    // Ищем все тогровые предложения
                    $offers = CIBlockPriceTools::GetOffersArray(array(
                        'IBLOCK_ID' => $ar_res['IBLOCK_ID'],
                        'HIDE_NOT_AVAILABLE' => 'Y',
                        'CHECK_PERMISSIONS' => 'Y'
                    ), array($item_id), null, null, null, null, null, null, array('CURRENCY_ID' => $sale_currency), $USER->getId(), null);

                    foreach ($offers as $offer) {

                        $price = \CCatalogProduct::GetOptimalPrice($offer['ID'], 1, $USER->GetUserGroupArray(), 'N');
                        if (isset($price['PRICE'])) {

                            $final_price = $price['PRICE']['PRICE'];
                            $currency_code = $price['PRICE']['CURRENCY'];

                            // Ищем скидки и высчитываем стоимость с учетом найденных
                            $arDiscounts = \CCatalogDiscount::GetDiscountByProduct($item_id, $USER->GetUserGroupArray(), "N");
                            if (is_array($arDiscounts) && sizeof($arDiscounts) > 0) {
                                $final_price = \CCatalogProduct::CountPriceWithDiscount($final_price, $currency_code, $arDiscounts);
                            }

                            // Конец цикла, используем найденные значения
                            break;
                        }

                    }
                }
            }

        } else {

            // Простой товар, без торговых предложений (для количества равному 1)
            $price = \CCatalogProduct::GetOptimalPrice($item_id, 1, $USER->GetUserGroupArray(), 'N');

            // Получили цену?
            if (!$price || !isset($price['PRICE'])) {
                return false;
            }

            // Меняем код валюты, если нашли
            if (isset($price['CURRENCY'])) {
                $currency_code = $price['CURRENCY'];
            }
            if (isset($price['PRICE']['CURRENCY'])) {
                $currency_code = $price['PRICE']['CURRENCY'];
            }

            // Получаем итоговую цену
            $final_price = $price['PRICE']['PRICE'];

            // Ищем скидки и пересчитываем цену товара с их учетом
            $arDiscounts = \CCatalogDiscount::GetDiscountByProduct($item_id, $USER->GetUserGroupArray(), "N", 2);
            if (is_array($arDiscounts) && sizeof($arDiscounts) > 0) {
                $final_price = \CCatalogProduct::CountPriceWithDiscount($final_price, $currency_code, $arDiscounts);
            }

        }

        // Если необходимо, конвертируем в нужную валюту
        if ($currency_code != $sale_currency) {
            $final_price = \CCurrencyRates::ConvertCurrency($final_price, $currency_code, $sale_currency);
        }

        return $final_price;
    }
}










