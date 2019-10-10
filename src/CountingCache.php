<?php

namespace ppCache;

use Psr\SimpleCache\CacheInterface;

final class CountingCache implements CacheInterface, CounterInterface
{
    /**
     * @var Cache The simple cache instance
     */
    private $cache;

    /**
     * Cache constructor
     */
    public function __construct()
    {
        $this->cache = new Cache();
    }

    /**
     * @inheritDoc
     */
    public function get($key, $default = null)
    {
        return $this->cache->get($key, $default);
    }

    /**
     * @inheritDoc
     */
    public function set($key, $value, $ttl = null): bool
    {
        return $this->cache->set($key, $value, $ttl);
    }

    /**
     * @inheritDoc
     */
    public function delete($key): bool
    {
        return $this->cache->delete($key);
    }

    /**
     * @inheritDoc
     */
    public function clear(): bool
    {
        return $this->cache->clear();
    }

    /**
     * @inheritDoc
     */
    public function getMultiple($keys, $default = null): array
    {
        return $this->cache->getMultiple($keys, $default);
    }

    /**
     * @inheritDoc
     */
    public function setMultiple($values, $ttl = null): bool
    {
        return $this->cache->setMultiple($values, $ttl);
    }

    /**
     * @inheritDoc
     */
    public function deleteMultiple($keys): bool
    {
        return $this->cache->deleteMultiple($keys);
    }

    /**
     * @inheritDoc
     */
    public function has($key): bool
    {
        return $this->cache->has($key);
    }

    /**
     * @inheritDoc
     */
    public function increment($key, $step = 1): int
    {
        /* The two operators below are atomically executed because `$this->cache` is a PHP array-based cache
        and not a distributed cache */
        $value = $this->cache->get($key, 0);
        $newValue = $value + $step;
        $this->cache->set($key, $newValue);
        return $newValue;
    }

    /**
     * @inheritDoc
     */
    public function decrement($key, $step = 1): int
    {
        /* The two operators below are atomically executed  because `$this->cache` is a PHP array-based cache
        and not a distributed cache */
        $value = $this->cache->get($key, 0);
        $newValue = $value - $step;
        $this->cache->set($key, $newValue);
        return $newValue;
    }
}
