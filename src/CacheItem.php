<?php

namespace ppCache;

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
        return $this->isHit() ? $this->value : null;
    }

    /**
     * @inheritDoc
     */
    public function isHit(): bool
    {
        $this->isHit;
    }

    /**
     * @inheritDoc
     */
    public function set($value): self
    {
        $this->value = $value;
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
    }
}