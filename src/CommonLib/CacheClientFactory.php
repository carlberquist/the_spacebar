<?php
/**
 * Created by PhpStorm.
 * User: Mr
 * Date: 01/09/2018
 * Time: 10:48
 */

namespace App\CommonLib;


use Symfony\Component\Cache\Adapter\ApcuAdapter;

class CacheClientFactory
{
    /**
     * @param string $nameSpace
     * @param int $ttl
     * @param null $version
     * @return ApcuAdapter
     */
    public function createCache(string $nameSpace = 'space_inc/the_spacebar',
                                int $ttl = 300,
                                string $version = null){
        $apcuCache = new ApcuAdapter(
            $nameSpace,
            $ttl,
            $version);

        return new CacheClient($apcuCache);
    }

}