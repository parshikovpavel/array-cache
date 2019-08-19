<?php

namespace ppCache;

use DateTimeInterface;
use Psr\Cache\CacheItemInterface;

/**
 * @inheritDoc
 */
final class CacheItem implements CacheItemInterface
{
    /**
     * @var string A string which uniquely identifies a cached item
     */
    private $key;

    /**
     * @var mixed Data of some serializable PHP data type
     */
    private $value;

    /**
     * @var DateTimeInterface The time when an item is set to go stale
     */
    private $expiration;

    /**
     * @var bool An item is found and has not expired
     */
    private $isHit;

    /**
     * CacheItem construct
     * @param string $key
     * @param bool $isHit
     */
    public function __construct(string $key, bool $isHit = false)
    {
        $this->key = $key;
        $this->isHit = $isHit;
    }

    /**
     * @inheritDoc
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * @inheritDoc
     */
    public function get()
    {
        if ($this->isHit()) {
            return $this->value;
        }
        else {
            return null;
        }
    }

}