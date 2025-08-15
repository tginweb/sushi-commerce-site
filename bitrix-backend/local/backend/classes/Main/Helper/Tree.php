<?php

namespace Main\Helper;

class Tree
{

    static $i = 0;

    static function arrayToTree($items, $idField = 'id', $parentField = 'parent', $childrenField = 'children')
    {
        $childs = [];

        foreach ($items as &$item) {
            $childs[$item[$parentField] ?? ''][] = &$item;
        }

        unset($item);

        foreach ($items as &$item) {
            if (isset($childs[$item[$idField]])) {
                $item[$childrenField] = $childs[$item[$idField]];
            }
        }

        return $childs[''] ?? [];
    }

    static function treeToArray($arr, $idField = 'id', $childrenField = 'children', $parentField = 'parent', $depthField = 'depth', $depth = 0, $parentId = null)
    {
        $result = [];
        foreach ($arr as $item) {

            if ($parentId)
                $item[$parentField] = $parentId;

            if (empty($item[$idField])) {
                $item[$idField] = 'r' . rand(1, 100000);
            }

            $item[$depthField] = $depth;

            if (isset($item[$childrenField])) {

                $children = static::treeToArray($item[$childrenField], $idField, $childrenField, $parentField, $depthField, $depth + 1, $item[$idField]);

                $result = array_merge($result, $children);
            }

            unset($item[$childrenField]);

            $result[] = $item;
        }
        return $result;
    }

    static function filter($arr, $cb, $childrenField = 'children')
    {

        $result = [];
        foreach ($arr as $key => &$item) {
            if ($cb($item)) {
                if ($item[$childrenField]) {
                    $item[$childrenField] = static::filter($item[$childrenField], $cb, $childrenField);
                }
                $result[$key] = $item;
            }
        }
        return $result;
    }

    static function reduce($tree, $cb, $map = [], $childrenField = 'children')
    {
        $path = [];

        $walk = function ($map, $nodes, $path) use ($cb, $childrenField, &$walk) {

            foreach ($nodes as $node) {
                $map = $cb($map, $node, $path);

                if (!empty($node[$childrenField])) {
                    $map = $walk($map, $node[$childrenField], array_merge($path, [$node]));
                }
            }

            return $map;
        };

        return $walk($map, $tree, $path);
    }
}
