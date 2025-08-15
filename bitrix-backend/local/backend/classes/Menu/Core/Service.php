<?php

namespace Menu\Core;

use Bitrix;
use Main\Service\BaseService;
use Menu\Core\Entity\MenuItem;
use Menu\Core\Entity\MenuItemSection;
use TG\Main\Helper;

function filterTree($nodes, $filterFn)
{
    $result = array_filter($nodes, function ($node) use ($filterFn) {
        return $filterFn($node);
    });

    foreach ($result as &$node) {
        if (empty($node['ID']))
            $node['ID'] = rand(1000, 9000);

        if (!empty($node['CHILDREN'])) {
            $node['CHILDREN'] = filterTree($node['CHILDREN'], $filterFn);
        }
    }

    return $result;
}

class Service extends BaseService
{

    function prepareActionsMenu($items)
    {

        usort($items, function ($item1, $item2) {
            return $item1['sort'] <=> $item2['sort'];
        });

        return $items;
    }

    function getAdminMenus($user = null)
    {
        $menus = $this->getHooks()->apply_filters('menu:admin.menus', []);

        array_walk_recursive($menus, function (&$val, $key) {
            if (in_array($key, ['URL', 'NAME'])) {
                $val = $this->getHooks()->apply_filters('main:compile_vars', $val);
            }
        });

        return $menus;
    }

    function getResultMenus($user = null)
    {
        $userRoles = [];

        $isShopAdmin = false;

        if ($user) {
            $userRoles = $user->getRoles();
            if ($user->isShopAdmin() || $user->isAdmin()) {
                $isShopAdmin = true;
                $userRoles[] = 'ADMIN';
                $userRoles[] = 'SHOP_ADMIN';
            }

            //$userRoles = ['SELLER_SCHOOL'];
            //$isShopAdmin = false;
        }

        $menus = $this->getHooks()->apply_filters('menu:menus', []);

        $menusItems = $this->getHooks()->apply_filters('menu:items', []);

        $menuItemsByMenu = [];

        foreach ($menusItems as $menuItem) {
            $menuItemsByMenu[$menuItem['menu']][] = $menuItem;
        }

        foreach ($menus as $menuCode => &$menu) {

            $menuItems = \Main\Helper\Tree::treeToArray($menu['children']);

            $menu['children'] = [];
            $menu['children_flatten'] = [];

            foreach ($menuItems as $menuItem) {
                $menuItem['menu'] = $menuCode;
                $menu['children_flatten'][] = $menuItem;
            }

            foreach ($menuItemsByMenu[$menuCode] as $menuItem) {
                $menu['children_flatten'][] = $menuItem;
            }

            $index = 1;

            $menu['children_flatten'] = \Main\Helper\Tree::filter($menu['children_flatten'], function (&$item) use ($userRoles, $isShopAdmin, &$index) {

                if (!$item['sort']) {
                    $item['sort'] = ($index++) * 5;
                }

                if ($isShopAdmin)
                    return true;

                if (!empty($item['roles']) && !count(array_intersect($userRoles, $item['roles']))) {
                    return false;
                }

                return true;

            }, 'children');
        }

        foreach ($menus as $menuCode => &$menu) {

            usort($menu['children_flatten'], function ($item1, $item2) {
                return ($item1['sort'] ?? 0) <=> ($item2['sort'] ?? 0);
            });

            $menus[$menuCode]['children'] = \Main\Helper\Tree::arrayToTree($menu['children_flatten']);
        }

        $this->container->getCompilerService()->compileTreeWalk($menus, 'children', ['url', 'label']);

        return $menus;
    }

    function fillMenusFromIblock($menus = [], $iblockId = null)
    {
        $clsElement = $this->container->getIblockService()->getElementIBlockClassById($iblockId, MenuItem::class, null);
        $clsSection = $this->container->getIblockService()->getSectionIBlockClassById($iblockId, MenuItemSection::class, null);

        $items = [];

        $elements = $clsElement::query()->withViewPublic()->filter([
            'ACTIVE' => 'Y',
            'SECTION_GLOBAL_ACTIVE' => 'Y',
        ])->getList();


        $sections = $clsSection::query()->withViewPublic()->filter([
            'ACTIVE' => 'Y',
        ])->getList();

        $sort = 1;

        foreach ($elements->all() as $element) {

            $sort++;

            if ($element['PROPERTY_CONTENT_IBLOCK_ID_VALUE']) {

                $cls = $this->container->getIblockService()->getSectionIBlockClassById($element['PROPERTY_CONTENT_IBLOCK_ID_VALUE']);

                if ($cls && class_exists($cls)) {

                    $expandSections = $cls::query()->filter([
                        'ACTIVE' => 'Y',
                        'CNT_ACTIVE' => 'Y',
                        'ELEMENT_SUBSECTIONS' => 'Y',
                        'UF_HIDDEN' => 0
                    ])
                        ->select('UF_*')
                        ->sort(['SORT' => 'ASC'])
                        ->countElements(true)
                        ->getList();

                    foreach ($expandSections as $section) {
                        $sort++;
                        if ($section['ELEMENT_CNT'] > 0) {
                            $item = $section->getMenuItemData($element);
                            $item['sort'] = $sort;
                            $items[] = $item;
                        }
                    }
                }
            } else {
                $item = $element->getMenuItemData();
                $item['sort'] = $sort;
                $items[] = $item;
            }
        }

        foreach ($sections->all() as $section) {
            $items[] = $section->getMenuItemData();
        }

        //die(\Safe\json_encode($items));

        $tree = \Main\Helper\Tree::arrayToTree($items);

        foreach ($tree as $rootItem) {

            foreach ($rootItem['children'] as &$child) {
                unset($child['parent']);
            }

            $menus[$rootItem['code']]['code'] = $rootItem['code'];
            $menus[$rootItem['code']]['children'] = $rootItem['children'];
        }

        return $menus;
    }

}



