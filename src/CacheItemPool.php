<?php

namespace ppCache;

use Psr\Cache\CacheItemInterface;
use Psr\Cache\CacheItemPoolInterface;

/**
 * @inheritDoc
 */
final class CacheItemPool implements CacheItemPoolInterface
{
    /**
     * @var CacheItem[] An array of cached items
     */
    private $items;

    /**
     * @inheritDoc
     */
    public function getItem($key): CacheItem
    {
        $this->validateKey($key);

        if (isset($this->items[$key])) {
            if (!$this->items[$key]->isExpired()) {
                return $this->items[$key];
            }

            unset($this->items[$key]);
        }

        return new CacheItem($key);
    }

    /**
     * @inheritDoc
     */
    public function getItems(array $keys = []): array
    {
        $items = [];

        foreach ($keys as $key) {
            $items[$key] = $this->getItem($key);
        }

        return $items;
    }

    /**
     * @inheritDoc
     */
    public function hasItem($key): bool
    {
        return $this->getItem($key)->isHit();
    }

    /**
     * @inheritDoc
     */
    public function clear(): bool
    {
        $this->items = [];
        return true;
    }

    /**
     * @inheritDoc
     */
    public function deleteItem($key): bool
    {
        $this->validateKey($key);

        unset($this->items[$key]);

        return true;
    }

    /**
     * @inheritDoc
     */
    public function deleteItems(array $keys): bool
    {
        foreach ($keys as $key) {
            $this->deleteItem($key);
        }

        return true;
    }

    /**
     * {@inheritDoc}
     * @throws InvalidArgumentException Thrown if $item is not an instance of CacheItem class
     */
    public function save(CacheItemInterface $item): bool
    {
        if (!($item instanceof CacheItem)) {
            throw new InvalidArgumentException('Pool saves only CacheItem instances');
        }

        $item->setHit();
        $this->items[$item->getKey()] = $item;
        return true;
    }

    /**
     * {@inheritDoc}
     * @throws InvalidArgumentException Thrown if $item is not an instance of CacheItem class
     */
    public function saveDeferred(CacheItemInterface $item): bool
    {
        return $this->save($item);
    }

    /**
     * @inheritDoc
     */
    public function commit(): bool
    {
        return true;
    }

    /**
     * Validates key of an item
     *
     * The key must consist of at least one character
     * The following characters are reserved for future extensions and MUST NOT be supported by implementing libraries: {}()/\@:
     *
     * @param string $key The offered key
     * @throws InvalidArgumentException Thrown if the offered key is invalid
     * @return void
     */
    private function validateKey(string $key): void
    {
        $unsupportedCharacters = '{}()/\@:';

        if ($key === '' || false !== strpbrk($key, $unsupportedCharacters)) {
            throw new InvalidArgumentException('Invalid key');
        }
    }

}