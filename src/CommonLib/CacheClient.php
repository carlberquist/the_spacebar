<?php
/**
 * Created by PhpStorm.
 * User: Mr
 * Date: 01/09/2018
 * Time: 10:48
 */

namespace App\CommonLib;


use Psr\Cache\CacheItemPoolInterface;

class CacheClient
{
    private $cacheAdapter;

    /**
     * CacheClient constructor.
     * @param $cacheAdapter
     */
    public function __construct(CacheItemPoolInterface $cacheAdapter)
    {
        $this->cacheAdapter = $cacheAdapter;
    }

    /**
     * @return CacheItemPoolInterface
     */
    public function getCacheAdapter(): CacheItemPoolInterface
    {
        return $this->cacheAdapter;
    }
}