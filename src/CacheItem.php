<?php

namespace ppCache;

use Exception;
use DateTime;
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
    private $value = null;

    /**
     * @var DateTimeInterface The time when an item is set to go stale
     */
    private $expiration = null;

    /**
     * @var bool An item is found and has not expired
     */
    private $isHit = false;

    /**
     * CacheItem construct
     * @param string $key
     */
    public function __construct(string $key)
    {
        $this->key = $key;
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
        return $this->isHit() ? $this->value : null;
    }

    /**
     * @inheritDoc
     */
    public function isHit(): bool
    {
        return $this->isHit;
    }

    /**
     * @inheritDoc
     */
    public function set($value): self
    {
        $this->value = is_object($value) ? clone $value : $value;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function expiresAt($expiration): self
    {
        $this->expiration = ($expiration instanceof DateTimeInterface) ? $expiration : null;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function expiresAfter($time): self
    {
        if ($time instanceof \DateInterval) {
            $expiration = new DateTime();
            $expiration->add($time);
            $this->expiration = $expiration;
        } elseif (is_numeric($time)) {
            $expires = new DateTime('now +' . $time . ' seconds');
            $this->expiration = $expires;
        } else {
            $this->expiration = null;
        }

        return $this;
    }

    /**
     * Sets that there is a cache hit
     */
    public function setHit(): void
    {
        $this->isHit = true;
    }

    /**
     * Checks that an item has expired
     * @return bool True - if item has expired, false - otherwise
     * @throws Exception Thrown by the Datetime class
     */
    public function isExpired(): bool
    {
        return null !== $this->expiration && new DateTime() > $this->expiration;
    }
}
