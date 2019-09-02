<?php

namespace ppCache;

use Psr\SimpleCache\CacheInterface;
use Psr\Cache\CacheItemPoolInterface;

/**
 * Adapter-decorator to cast a PSR-6 pool implementation to the PSR-16 simple cache interface
 */
final class Psr6ToPsr16Adapter implements CacheInterface
{
    /**
     * A PSR-6 pool
     * @var CacheItemPoolInterface
     */
    private $pool;

    /**
     * Psr6ToPsr16Adapter constructor
     * @param CacheItemPoolInterface $pool
     */
    public function __construct(CacheItemPoolInterface $pool)
    {
        $this->pool = new class($pool) {
            private $pool;

            public function __construct(CacheItemPoolInterface $pool)
            {
                $this->pool = $pool;
            }

            public function __call($name, $arguments)
            {
                try {
                    $this->pool->$name(...$arguments);
                } catch (\Psr\Cache\InvalidArgumentException $e) {
                    throw new InvalidArgumentException($e->getMessage(), $e->getCode(), $e);
                }
            }
        };



}

    /**
     * @inheritDoc
     */
    public function get($key, $default = null)
    {
        $item = $this->pool->getItem($key);

        return $item->isHit() ? $item->get() : $default;
    }

    /**
     * @inheritDoc
     */
    public function set($key, $value, $ttl = null): bool
    {


}