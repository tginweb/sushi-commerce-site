<?php

namespace Main\Service;

use TG\Main\Helper;

class CompilerService extends BaseService
{
    public $config = [];

    function register()
    {
        $this->getHooks()->add_filter('main:compile_vars', function ($val) {
            return $this->container->getCompilerService()->compileVars($val);
        });
    }

    function compileTreeWalk(&$tree, $children = 'children', $fields = [])
    {
        foreach ($tree as &$node) {
            foreach ($fields as $field) {
                $node[$field] = $this->compileVars($node[$field]);
            }
            if (!empty($node[$children])) {
                $this->compileTreeWalk($node[$children], $children, $fields);
            }
        }
    }

    function compileVars($val, &$vars = [])
    {
        if (is_array($val)) {
            foreach ($val as $k => $v) {
                $val[$k] = $this->compileVars($v, $vars);
            }
        } else {
            $config = $this->container->getConfigService();
            $settings = $this->container->getSettingsService();

            if (strpos($val, '~') !== false) {
                $val = preg_replace_callback('/\~(\w+?\:)?(.+?)\~/', function ($mt) use ($config, $settings, $vars) {
                    $ns = trim($mt[1], ':');
                    $key = $mt[2];

                    $keyPath = explode('.', $key);

                    if (!$ns) {
                        if (isset($vars[$key])) {
                            return $vars[$key];
                        } else if (isset($vars[$keyPath[0]]) && is_array($vars[$keyPath[0]])) {
                            return \Main\Helper\Common::arrayGetNestedValue($vars, $keyPath);
                        }
                    }

                    switch ($ns) {
                        case 'C':
                            $val = $config->get($key);
                            break;
                        case 'S':
                        default:
                            $val = $settings->get($key);
                    }
                    return $val;
                }, $val);
            }
        }
        return $val;
    }

    function mergeConfigFile($file)
    {
        $res = include($file);
        if (is_array($res)) {
            $this->config = array_merge_recursive($this->config, $res);
        }
    }
}


