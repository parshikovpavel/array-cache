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
        $item = $this->pool->getItem($key);

        $item->set($value)
             ->expiresAfter($ttl);

        return $this->pool->save($item);
    }

    /**
     * @inheritDoc
     */
    public function delete($key): bool
    {
        return $this->pool->deleteItem($key);
    }

    /**
     * @inheritDoc
     */
    public function clear(): bool
    {
        return $this->pool->clear();
    }

    /**
     * @inheritDoc
     */
    public function getMultiple($keys, $default = null)
    {
        $result = [];

        //TODO Not completed


        if (!is_array($keys)) {
            if (!$keys instanceof \Traversable) {
                throw new InvalidArgumentException('$keys is neither an array nor a Traversable');
            }

            $keys = iterator_to_array($keys, false);
        }


        $items = $this->pool->getItems($keys);

        foreach ($items as $key => $item) {
            $result[$key] = $item->isHit() ? $item->get() : $default;
        }
        return $result;
    }

    /**
     * Validate iterable parameters of xxxMultiple functions
     *
     * An iterable parameter must be either an array or a Traversable
     * otherwise `InvalidArgumentException` is thrown
     *
     * @param $iterable The iterable parameter
     * @throws InvalidArgumentException Thrown if the iterable parameter is invalid
     * @return void
     */
    private function validateIterable($iterable): void
    {
        if (!is_array($iterable) && !($iterable instanceof \Traversable)) {
            throw new InvalidArgumentException('Invalid iterable parameter');
        }
    }





}