<?php

namespace Main\Lib\Cache;

use Bitrix\Main\Data\CacheEngineFiles;
use TG\Main\Com\Cacher\Debug\CacheDebugger;

class CPHPCache
{

    static $cacher;
    /**
     * @var \Bitrix\Main\Data\Cache
     */
    private $cache;

    public function __construct()
    {
        $this->cache = self::$cacher;
    }

    function setCacherFiles()
    {
        if (!self::$cacher) {
            $this->cache = self::$cacher = new \Bitrix\Main\Data\Cache(new CacheEngineFiles([]));
        }

    }

    public function Clean($uniq_str, $initdir = false, $basedir = "cache")
    {
        return $this->cache->clean($uniq_str, $initdir, $basedir);
    }

    public function CleanDir($initdir = false, $basedir = "cache")
    {
        return $this->cache->cleanDir($initdir, $basedir);
    }

    public function InitCache($TTL, $uniq_str, $initdir = false, $basedir = "cache")
    {
        return $this->cache->initCache($TTL, $uniq_str, $initdir, $basedir);
    }

    public function Output()
    {
        $this->cache->output();
    }

    public function GetVars()
    {
        return $this->cache->getVars();
    }

    public function StartDataCache($TTL = false, $uniq_str = false, $initdir = false, $vars = array(), $basedir = "cache")
    {
        $narg = func_num_args();
        if ($narg <= 0)
            return $this->cache->startDataCache();
        if ($narg <= 1)
            return $this->cache->startDataCache($TTL);
        if ($narg <= 2)
            return $this->cache->startDataCache($TTL, $uniq_str);
        if ($narg <= 3)
            return $this->cache->startDataCache($TTL, $uniq_str, $initdir);

        return $this->cache->startDataCache($TTL, $uniq_str, $initdir, $vars, $basedir);
    }

    function AbortDataCache()
    {
        $this->cache->abortDataCache();
    }

    /**
     * Saves the result of calculation to the cache.
     *
     * @param mixed $vars
     * @return void
     */
    function EndDataCache($vars = false)
    {

        $this->cache->endDataCache($vars);
    }

    function IsCacheExpired($path)
    {
        if (isset($this) && is_object($this) && ($this instanceof CPHPCache)) {
            return $this->cache->isCacheExpired($path);
        } else {
            $obCache = new CPHPCache();
            return $obCache->IsCacheExpired($path);
        }
    }
}
