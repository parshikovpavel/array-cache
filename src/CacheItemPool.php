<?php

namespace ppCache;

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