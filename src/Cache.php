<?php

namespace ppCache;

use Psr\SimpleCache\CacheInterface;

/**
 * @inheritDoc
 */
final class Cache implements CacheInterface
{
    /**
     * @var Psr6ToPsr16Adapter The adapter that decorates a PSR-6 pool
     * and converts it to a PSR-16 simple cache interface
     */
    private $adapter;

    /**
     * Cache constructor
     */
    public function __construct()
    {
        $pool = new CacheItemPool();

        $this->adapter = new Psr6ToPsr16Adapter($pool);
    }

    /**
     * @inheritDoc
     */
    public function get($key, $default = null)
    {
        return $this->adapter->get($key, $default);
    }

    /**
     * @inheritDoc
     */
    public function set($key, $value, $ttl = null): bool
    {
        return $this->adapter->set($key, $value, $ttl);
    }

    /**
     * @inheritDoc
     */
    public function delete($key): bool
    {
        return $this->adapter->delete($key);
    }

    /**
     * @inheritDoc
     */
    public function clear(): bool
    {
        return $this->adapter->clear();
    }

    /**
     * @inheritDoc
     */
    public function getMultiple($keys, $default = null): array
    {
        return $this->adapter->getMultiple($keys, $default);
    }

    /**
     * @inheritDoc
     */
    public function setMultiple($values, $ttl = null): bool
    {
        return $this->adapter->setMultiple($values, $ttl);
    }

    /**
     * @inheritDoc
     */
    public function deleteMultiple($keys): bool
    {
        return $this->adapter->deleteMultiple($keys);
    }

    /**
     * @inheritDoc
     */
    public function has($key): bool
    {
        return $this->adapter->has($key);
    }
}
