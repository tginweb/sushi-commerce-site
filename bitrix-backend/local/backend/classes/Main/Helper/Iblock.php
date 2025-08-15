<?php

namespace Main\Helper;

use CIBlockSection;

class Iblock
{
    static $arSections;
    static $arSectionsRoot;

    static function getSectionsSchemaRoot()
    {
        self::getSectionsSchema();
        return self::$arSectionsRoot;
    }

    static function getSectionsSchema($sectionIds = [], $returnField = null)
    {
        if (!isset(self::$arSections)) {

            $rsSections = CIBlockSection::GetList([], [], false, ['ID', 'IBLOCK_SECTION_ID', 'IBLOCK_ID', 'LEFT_MARGIN', 'RIGHT_MARGIN']);
            $arSections = [];
            $arSectionsRoot = [];
            $childs = [];

            while ($arSection = $rsSections->Fetch()) {
                $arSections[$arSection['ID']] = $arSection;
                $childs[$arSection['IBLOCK_SECTION_ID'] ?? ''][] = &$arSections[$arSection['ID']];
            }

            foreach ($arSections as &$arSection) {
                if (isset($childs[$arSection['ID']])) {
                    $arSection['CHILDREN'] = $childs[$arSection['ID']];
                }

                if (!$arSection['IBLOCK_SECTION_ID']) {
                    $arSectionsRoot[$arSection['ID']] = &$arSections[$arSection['ID']];
                }
            }

            $scanTree = function (&$sections) use (&$arSections, &$scanTree) {

                $result = [];

                foreach ($sections as &$section) {

                    $result[] = $section;

                    if ($section['CHILDREN']) {
                        $section['CHILDREN_ALL'] = $scanTree($section['CHILDREN']);
                        $result = array_merge($result, $section['CHILDREN_ALL']);
                    }
                }

                return $result;
            };

            $scanTree($arSectionsRoot);

            self::$arSections = $arSections;
            self::$arSectionsRoot = $arSectionsRoot;
        }

        $result = [];

        if (!empty($sectionIds)) {
            foreach ($sectionIds as $sectionId) {
                $result[$sectionId] = self::$arSections[$sectionId];
            }
        } else {
            $result = self::$arSections;
        }

        return $result;
    }

    static function getSectionSchema($sectionId)
    {
        self::getSectionsSchema();
        return self::$arSections[$sectionId] ?? null;
    }

    static function getSectionChildrenIds($sectionId, $deep = false)
    {
        $result = [];

        $childrenSections = self::getSectionsSchema((array)$sectionId);

        foreach ($childrenSections as $childrenSection) {

            $result[] = $childrenSection['ID'];

            if (!empty($childrenSection['CHILDREN_ALL'])) {

                foreach ($childrenSection['CHILDREN_ALL'] as $childrenSubSection) {

                    $result[] = $childrenSubSection['ID'];
                }
            }
        }

        return $result;
    }
}
