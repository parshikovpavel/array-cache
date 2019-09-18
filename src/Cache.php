<?php

namespace ppCache;

use Psr\SimpleCache\CacheInterface;

/**
 * @inheritDoc
 */
final class Cache implements CacheInterface
{
    /**
     * @var Psr6ToPsr16Adapter TODO!
     */
    private $simpleCache;

    /**
     * Cache constructor
     */
    public function __construct()
    {
        $pool = new CacheItemPool();

        $this->simpleCache = new Psr6ToPsr16Adapter($pool);
    }



}