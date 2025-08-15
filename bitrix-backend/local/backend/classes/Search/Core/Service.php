<?php

namespace Search\Core;

use Bitrix;
use Bitrix\Main\Loader;
use Main\Service\BaseService;
use Search\Core\Entity\SearchResult;
use const TG\Search\Core\SITE_ID;

class Service extends BaseService
{
    public function search($query, $arParams = [], $exFilter = [])
    {
        $query = mb_strtoupper(trim($query));

        Loader::includeModule('search');

        $arParams += [
            'USE_TITLE_RANK' => true,
            'HOW' => 'rank'
        ];

        if ($arParams["USE_TITLE_RANK"]) {
            if ($arParams['HOW'] == "date")
                $aSort = array("DATE_CHANGE" => "DESC", "CUSTOM_RANK" => "DESC", "TITLE_RANK" => "DESC", "RANK" => "DESC");
            else
                $aSort = array("CUSTOM_RANK" => "DESC", "TITLE_RANK" => "DESC", "RANK" => "DESC", "DATE_CHANGE" => "DESC");
        } else {
            if ($arParams['HOW'] == "date")
                $aSort = array("DATE_CHANGE" => "DESC", "CUSTOM_RANK" => "DESC", "RANK" => "DESC");
            else
                $aSort = array("CUSTOM_RANK" => "DESC", "RANK" => "DESC", "DATE_CHANGE" => "DESC");
        }

        $obSearch = new \CSearch();

        //When restart option is set we will ignore error on query with only stop words
        $obSearch->SetOptions(array(
            "ERROR_ON_EMPTY_STEM" => $arParams["RESTART"] != "Y",
            "NO_WORD_LOGIC" => $arParams["NO_WORD_LOGIC"] == "Y",
        ));

        $arFilter = array(
            "SITE_ID" => SITE_ID,
            "QUERY" => $query,
        );

        $exFilter = [];

        if ($arParams['MODULE_ID']['IBLOCK']) {
            $exFilter[] = [
                "MODULE_ID" => "iblock",
                "PARAM2" => $arParams['MODULE_ID']['IBLOCK']
            ];
        }

        $obSearch->Search($arFilter, $aSort, $exFilter);

        $rows = [];
        $rowsByModule = [];
        $rowsByModuleBundle = [];

        $sectionIds = [];

        while ($row = $obSearch->GetNext()) {

            preg_match('/(S)?(\d+)$/', $row['ITEM_ID'], $mt);

            $pref = $mt[1];
            $id = $mt[2];

            if ($row['MODULE_ID'] === 'iblock') {

                $row['ENTITY_ID'] = $id;
                $row['ENTITY_TITLE'] = $row['TITLE'];
                $row['IBLOCK_ID'] = $row['PARAM2'];

                $iblock = $this->container->getIblockService()->getIblockCached($row['IBLOCK_ID']);

                if ($iblock) {
                    $row['IBLOCK_CODE'] = $iblock['CODE'];
                    $row['ENTITY_TYPE_NAME'] = $iblock['NAME'];
                    $row['ENTITY_TYPE_CODE'] = $iblock['CODE'];
                }

                $row['ENTITY_TYPE_ID'] = $row['PARAM2'];

                if ($pref === 'S') {
                    $row['ENTITY_ROLE'] = 'section';
                    $sectionIds[$id] = $id;
                } else {
                    $row['ENTITY_ROLE'] = 'element';

                    $elementRs = \CIBlockElement::getList([], [
                        'ID' => $id,
                        'ACTIVE' => 'Y',
                        '!IBLOCK_SECTION_ID' => 94,
                    ], false, false, ['ID', 'IBLOCK_ID']);

                    $element = $elementRs->fetch();

                    if  (!$element)
                        continue;
                }
            }

            $row = new SearchResult($row);
            $rows[] = $row;
        }

        $sections = [];

        if (!empty($sectionIds)) {
            $rsSections = \CIBlockSection::GetList([], ['ID' => $sectionIds], false, ['ID', 'IBLOCK_SECTION_ID']);
            while ($arSection = $rsSections->Fetch()) {
                $sections[$arSection['ID']] = $arSection;
            }
        }

        $rowsResult = [];
        foreach ($rows as $row) {
            if ($row['ENTITY_ROLE'] === 'section') {
                $arSection = $sections[$row['ENTITY_ID']];
                if (!$arSection || $arSection['IBLOCK_SECTION_ID']) {
                    continue;
                }
            }
            $rowsResult[] = $row;
            $rowsByModule[$row['MODULE_ID']][] = $row;
            $rowsByModuleBundle[$row['MODULE_ID']][$row['ENTITY_TYPE_ID']][] = $row;
        }

        return [
            'row' => $rowsResult,
            'rowsByModule' => $rowsByModule,
            'rowsByModuleBundle' => $rowsByModuleBundle
        ];
    }
}



